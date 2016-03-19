<?php

namespace App\Http\Transformers\Adapter;

use Dingo\Api\Http\Request;
use Dingo\Api\Transformer\Binding;
use Illuminate\Contracts\Pagination\Paginator as IlluminatePaginator;
use League\Fractal\Pagination\Cursor;

use Dingo\Api\Transformer\Adapter\Fractal as BaseFractal;


class Fractal extends BaseFractal
{  
    /**
     * Transform a response with a transformer.
     *
     * @param mixed                          $response
     * @param object                         $transformer
     * @param \Dingo\Api\Transformer\Binding $binding
     * @param \Dingo\Api\Http\Request        $request
     *
     * @return array
     */
    public function transform($response, $transformer, Binding $binding, Request $request)
    {
        $this->parseFractalIncludes($request);

        $resource = $this->createResource($response, $transformer, $binding->getParameters());

        # 提供两种分页方式：Laravel Paginator, Cursor
        if ($response instanceof IlluminatePaginator) {
            # Laravel Paginator
            $paginator = $this->createPaginatorAdapter($response);
            $resource->setPaginator($paginator);
        }else{
            # Cursor
            $take = $request->input('take', false);
            if ($take) {
                $current = $request->input('cursor', 0);
                $prev = $request->input('prev', 0);
                $next = $response->last()->id;
                $cursor = new Cursor($current, $prev, $next, $response->count());
                $resource->setCursor($cursor);
            }            
        }
        if ($this->shouldEagerLoad($response)) {
            $eagerLoads = $this->mergeEagerLoads($transformer, $this->fractal->getRequestedIncludes());

            $response->load($eagerLoads);
        }

        foreach ($binding->getMeta() as $key => $value) {
            $resource->setMetaValue($key, $value);
        }

        $binding->fireCallback($resource, $this->fractal);

        return $this->fractal->createData($resource)->toArray();
    }
}
