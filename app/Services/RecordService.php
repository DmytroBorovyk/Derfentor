<?php

namespace App\Services;

use App\Http\Requests\RecordCreateRequest;
use App\Http\Resources\RecordResource;
use App\Models\File;
use App\Models\Record;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RecordService
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $records = Record::paginate(10);

        return RecordResource::collection($records);
    }

    public function show(Record $record, RedisService $redisService): RecordResource
    {
        $slug = Str::random(16);
        $redisService->setImgPath($slug, $record->file->path);
        $record->img = env('APP_URL').'/api/img/'.$slug;

        return new RecordResource($record->load('file'));
    }

    public function create(RecordCreateRequest $request): RecordResource|JsonResponse
    {
        $record = new Record($request->all());
        $record->save();
        if ($request->file) {
            try {
                $file = new File();
                $fileName = time().'_'.$request->file->getClientOriginalName();
                $request->file('file')->storeAs('/', $fileName, 'private_files');
                $file->path = $fileName;
                $file->record_id = $record->getKey();
                $file->save();
            } catch (\Exception $ex) {
                return response()->json([
                    'status' => false,
                    'message' => 'Something went wrong',
                ], 500);
            }
        }

        return new RecordResource($record);
    }

    public function getImg(RedisService $redisService, string $slug)
    {
        $path = $redisService->getImgPath($slug);
        if($path){
            $file = Storage::disk('private_files')->get($path);
            if($file){
                return $file;
            } else {
                return abort(404);
            }
        }

        return abort(404);

    }
}
