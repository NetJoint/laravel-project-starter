<?php

namespace App\Http\Controllers\Api;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use App\Models\Attachment;
use Illuminate\Http\Request;
use App\Http\Requests\UploadRequest;
use App\Http\Transformers\UserTransformer;

class UeditorApiController extends ApiController
{

    public function __construct()
    {
        
    }

    public function index(Request $request)
    {
        $action = $request->input('action');
        switch ($action) {
            case 'listimage':
                return $this->listFile($request, 'image');
                break;
            case 'listfile':
                return $this->listFile($request);
                break;
            default:
                return $this->response->errorBadRequest('不支持该操作');
        }
    }

    public function listFile(Request $request, $type = 'file')
    {
        $start = $request->input('start');
        $size = $request->input('size');
        $user = Sentinel::getUser();
        $total = Attachment::where('user_id', $user->id)->where('type', $type)->count();
        if (!$total) {
            $data = ["state" => "no match file",
                "list" => [],
                "start" => $start,
                "total" => 0
            ];
        } else {
            $files = Attachment::where('user_id', $user->id)->where('type', $type)->orderBy('created_at', 'desc')->limit($size)->offset($start)->get()->toArray();
            $list = [];
            foreach ($files as $file) {
                $list[] = [
                    'url' => $file['url'],
                    'original' => $file['original'],
                ];
            }
            $data = ["state" => "SUCCESS",
                "list" => $list,
                "start" => $start,
                "total" => $total
            ];
        }
        return $this->response->array($data);
    }

    public function postFile(UploadRequest $request)
    {
        $user = Sentinel::getUser();
        $file = $request->file('file');
        $action = $request->input('action');
        $ext = $file->getClientOriginalExtension();
        switch ($action) {
            case 'uploadfile':
                $mimes = ["png", "jpeg", "gif", "bmp",
                    "flv", "swf", "mkv", "avi", "rm", "rmvb", "mpeg", "mpg",
                    "ogg", "ogv", "mov", "wmv", "mp4", "webm", "mp3", "wav", "mid",
                    "rar", "zip", "tar", "gz", "7z", "iso",
                    "doc", "docx", "xls", "xlsx", "csv", "ppt", "pptx", "pdf", "txt", "md"
                ];
                $validator = \Validator::make($request->all(), [
                            'file' => 'mimes:' . implode(',', $mimes) . '|max:102400'
                ]);
                if ($validator->fails()) {
                    return $this->response->error('错误的文件类型或大小', 422);
                }
                $directory = '/attachment/user/' . $user['id'] . '/file/';
                $fileName = Date('Ymd') . str_random(4) . '.' . $ext;
                $path = $directory . $fileName;
                \Storage::put($path, file_get_contents($file->getRealPath()));
                $data = [
                    "user_id" => $user->id,
                    "url" => $path,
                    "title" => $fileName,
                    "original" => $file->getClientOriginalName(),
                    "mimetype" => $file->getClientMimeType(),
                    "type" => 'file',
                    "size" => $file->getClientSize()
                ];
                Attachment::create($data);
                $data['state'] = 'SUCCESS';
                unset($data['user_id']);
                return $this->response->array($data);
                break;
            case 'uploadimage':
            default:
                $validator = \Validator::make($request->all(), [
                            'file' => 'mimes:jpeg,png,gif,bmp|max:20480'
                ]);
                if ($validator->fails()) {
                    return $this->response->error('错误的文件类型或大小', 422);
                }
                $directory = '/attachment/user/' . $user['id'] . '/img/';
                $fileName = Date('Ymd') . str_random(4) . '.' . $ext;
                $path = $directory . $fileName;
                $crop = $request->input('crop', false);
                if ($crop) {
                    $crop = json_decode($crop, 1);
                    if (is_array($crop)) {
                        $img = \Image::make(\Input::file('file'));
                        if (isset($crop['rotate']) && $crop['rotate']) {
                            $crop['rotate'] = 0 - $crop['rotate'];
                            $img->rotate($crop['rotate']);
                        }
                        if(isset($crop['width'])){
                            $img->crop((int) $crop['width'], (int) $crop['height'], (int) $crop['x'], (int) $crop['y']);
                        }
                        if ($img->width() > 650) {
                            $img->resize(650, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        }
                        $content = (string) $img->encode(null, 80);
                        \Storage::put($path, $content);                        
                        $size = strlen($content);

                        $thumb_path = '/attachment/user/' . $user['id'] . '/thumb/' . $fileName;
                        if ($img->width() > 70) {
                            $img->resize(70, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        }
                        \Storage::put($thumb_path, (string) $img->encode(null, 80));

                        # 1:1 icon
                        if ($img->width() == $img->height()) {
                            $icon_path = '/attachment/user/' . $user['id'] . '/icon/' . $fileName;
                            if ($img->width() > 18) {
                                $img->resize(18, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                });
                            }
                            \Storage::put($icon_path, (string) $img->encode(null, 80));
                        }
                    } else {
                        return $this->response->errorBadRequest('错误的图片操作参数');
                    }
                } else {
                    \Storage::put($path, file_get_contents($file->getRealPath()));
                    $size = $file->getClientSize();
                }

                $data = [
                    "user_id" => $user->id,
                    "url" => $path,
                    "title" => $fileName,
                    "original" => $file->getClientOriginalName(),
                    "mimetype" => $file->getClientMimeType(),
                    "type" => 'image',
                    "size" => $size
                ];
                Attachment::create($data);
                $data['state'] = 'SUCCESS';
                unset($data['user_id']);
                return $this->response->array($data);
                break;
        }
    }

}
