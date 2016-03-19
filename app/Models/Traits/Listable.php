<?php

namespace App\Models\Traits;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

trait Listable
{

    //返回所有字段
    public static function getTableColumns()
    {
        $instance = new static;
        if (isset($instance->columns)) {
            return $instance->columns;
        }
        return \Schema::getColumnListing($instance->getTable());
    }

    //字段是否存在
    public function hasColumn($name)
    {
        if (!isset($this->columns)) {
            $this->columns = $this->getTableColumns();
        }
        return in_array($name, $this->columns);
    }

    //将查询条件处理成数组形式，可多重嵌套
    protected function processFilters($filters, $filterArray = [], $tmp = '')
    {
        $filters = trim($filters);
        $strlen = mb_strlen($filters);
        $cursor = mb_substr($filters, 0, 1, "UTF-8");
        $filters = mb_substr($filters, 1, $strlen, "UTF-8");
        switch ($cursor) {
            case '&':
                if ($tmp !== '') {
                    $filterArray[] = $tmp;
                }
                $filterArray[] = '&and&';
                $tmp = '';
                break;
            case '|':
                if ($tmp !== '') {
                    $filterArray[] = $tmp;
                }
                $filterArray[] = '|or|';
                $tmp = '';
                break;
            case '[';
                list($filters, $childArray) = $this->processFilters($filters);
                $filterArray[] = $childArray;
                break;
            case ']';
                if ($tmp !== '') {
                    $filterArray[] = $tmp;
                }
                return [$filters, $filterArray];
                break;
            default:
                $tmp .= $cursor;
        }
        if (mb_strlen($filters) > 0) {
            list($filters, $filterArray) = $this->processFilters($filters, $filterArray, $tmp);
        } else {
            if ($tmp !== '') {
                $filterArray[] = $tmp;
            }
        }
        return [$filters, $filterArray];
    }

    //应用查询数组
    protected function withFilters($query, $filterArray, $pre = 'and')
    {
        if (!isset($this->columns)) {
            $this->columns = $this->getTableColumns();
        }
        foreach ($filterArray as $filter) {
            if (is_array($filter)) {
                $instance = new static;
                $query->where(function($query) use ($filter, $instance) {
                    $query = $instance->withFilters($query, $filter);
                }, $pre);
            } else {
                switch ($filter) {
                    case '&and&':
                        $pre = 'and';
                        break;
                    case '|or|':
                        $pre = 'or';
                        break;
                    default:
                        $f = explode(',', $filter);
                        if (trim($f[0]) === '') {
                            throw new UnprocessableEntityHttpException("column not found");
                        }
                        if (!in_array($f[0], $this->columns)) {
                            throw new UnprocessableEntityHttpException("column [" . $f[0] . "] illegal");
                        }
                        $f[1] = isset($f[1]) ? $f[1] : null;
                        $f[2] = isset($f[2]) ? $f[2] : null;
                        //处理like
                        if (strtolower($f[1]) === 'like') {
                            $f[2] = mb_ereg_replace("%25$", "%", $f[2]);
                            $f[2] = mb_ereg_replace("^%25", "%", $f[2]);
                        }
                        $query->where($f[0], $f[1], $f[2], $pre);
                }
            }
        }
        return $query;
    }

    //处理列表请求：查询、排序、分页、聚类
    public static function processRequest($request, $where = null, $whereHas = null)
    {
        $instance = new static;
        $query = $instance->newQuery();
        
        //关系聚类搜索，$whereHas = ['users'=>[['user_id', $user_id]]]
        if ($whereHas !== null) {
            foreach ($whereHas as $field => $where2) {
                $query->whereHas($field, function ($query2) use ($where2) {
                    foreach ($where2 as $f) {
                        $f[1] = isset($f[1]) ? $f[1] : null;
                        $f[2] = isset($f[2]) ? $f[2] : null;
                        $f[3] = isset($f[3]) ? $f[3] : 'and';
                        $f[4] = isset($f[4]) ? $f[4] : false;
                        if ($f[1] == 'in') {
                            $query2->whereIn($f[0], $f[2], $f[3], $f[4]);
                        } else {
                            $query2->where($f[0], $f[1], $f[2], $f[3]);
                        }
                    }
                });
            }
        }

        if ($where !== null) {
            foreach ($where as $f) {
                $f[1] = isset($f[1]) ? $f[1] : null;
                $f[2] = isset($f[2]) ? $f[2] : null;
                $f[3] = isset($f[3]) ? $f[3] : 'and';
                $f[4] = isset($f[4]) ? $f[4] : false;
                if ($f[1] == 'in') {
                    $query->whereIn($f[0], $f[2], $f[3], $f[4]);
                } else {
                    $query->where($f[0], $f[1], $f[2], $f[3]);
                }
            }
        }

        //快速查询 filter
        $filter = $request->input('filter', null);
        if ($filter) {
            $filter = json_decode($filter, true);
            //and
            foreach ($filter as $field => $kw) {
                if ($instance->hasColumn($field)) {
                    if (is_array($kw)) {
                        if ($kw[0] == 'like') {
                            $kw[1] = '%' . $kw[1] . '%';
                        }
                        $query->where($field, $kw[0], $kw[1]);
                    } else {
                        $query->where($field, 'like', '%' . $kw . '%');
                    }
                }
            }
        }
        $search = $request->input('search', null);
        if ($search) {
            //or
            $query->where(function($query) use ($search, $instance) {
                        $filter = [
                        'id' => $search,
                        'title' => $search,
                        'name' => $search
                    ];
                    foreach ($filter as $field => $kw) {
                        if ($instance->hasColumn($field)) {
                            $query->where($field, 'like', '%' . $kw . '%', 'or');
                        }
                    }
                }, 'and');
        }

        //高级查询 filters=id,>,1000&id,<,10000&[a,like,kw|b,like,kw]
        $filters = $request->input('filters', null);
        if ($filters) {
            list(, $filterArray) = $instance->processFilters($filters);
            if (count($filterArray) > 0) {
                $query = $instance->withFilters($query, $filterArray);
            }
        }

        $order = $request->input('order', null);
        $sort = $request->input('sort', null);
        if ($sort) {
            if ($order) {
                //单项排序 sort=id&order=desc
                $order = ($order === 'desc') ? 'desc' : 'asc';
                if (!$instance->hasColumn($sort)) {
                    throw new UnprocessableEntityHttpException("column [" . $sort . "] illegal");
                }
                $query->orderBy($sort, $order);
            } else {
                //组合排序 sort=-created,title
                foreach (explode(',', $sort) as $order) {
                    if (substr($order, 0, 1) === '-') {
                        $order = substr($order, 1);
                        if (!$instance->hasColumn($order)) {
                            throw new UnprocessableEntityHttpException("column [" . $order . "] illegal");
                        }
                        $query->orderBy($order, 'desc');
                    } else {
                        if (!$instance->hasColumn($order)) {
                            throw new UnprocessableEntityHttpException("column [" . $order . "] illegal");
                        }
                        $query->orderBy($order, 'asc');
                    }
                }
            }
        }

        //dd($query->getQuery()->toSql());
        //laravel page翻页加载
        $page = $request->input('page', null);
        if ($page) {
            $perPage = $request->input('per_page', false);
            return $query->paginate($perPage);
        }
        //limit offset翻页加载
        $limit = $request->input('limit', null);
        if ($limit) {
            $offset = $request->input('offset', 0);
            $page = floor($offset / $limit) + 1;
            return $query->paginate($limit, ['*'], 'page', $page);
        }

        //瀑布流加载
        $take = $request->input('take', null);
        if ($take) {
            $key = $instance->getKeyName();
            $currentCursor = $request->input('cursor', 0);
            $dir = $request->input('dir', 'asc');
            if ($dir === 'asc') {
                $query->where($key, '>', $currentCursor)
                        ->orderBy($key, 'asc');
            } else {
                if ($currentCursor > 0) {
                    $query->where($key, '<', $currentCursor);
                }
                $query->orderBy($key, 'desc');
            }
            $query->take($take);
        }
        return $query->get();
    }

    public static function processUpdateRequest($request, $where, $except = null, $only = null)
    {
        $instance = new static;
        $query = $instance->newQuery();

        if ($where !== null) {
            foreach ($where as $f) {
                $f[1] = isset($f[1]) ? $f[1] : null;
                $f[2] = isset($f[2]) ? $f[2] : null;
                $f[3] = isset($f[3]) ? $f[3] : 'and';
                $f[4] = isset($f[4]) ? $f[4] : false;
                if ($f[1] == 'in') {
                    $query->whereIn($f[0], $f[2], $f[3], $f[4]);
                } else {
                    $query->where($f[0], $f[1], $f[2], $f[3]);
                }
            }
        }
        if ($except) {
            $input = $request->except($except);
        } elseif ($only) {
            $input = $request->only($except);
        } else {
            $input = $request->all();
        }
        $data = [];
        foreach ($input as $key => $value) {
            if ($instance->hasColumn($key)) {
                $data[$key] = $value;
            }
        }
        if (count($data)) {
            return $query->update($data);
        } else {
            return 0;
        }
    }

}
