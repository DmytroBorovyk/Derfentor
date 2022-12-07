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
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RecordService
{
    public function __construct(private RedisService $redisService)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $records = Record::paginate(10);

        return RecordResource::collection($records);
    }

    public function show(Record $record): RecordResource
    {
        $slug = Str::random();
        $this->redisService->setImgPath($slug, $record->file->name);
        $record->img = config('app.url').'/api/img/'.$slug;

        return new RecordResource($record->load('file'));
    }

    public function create(RecordCreateRequest $request): RecordResource|JsonResponse
    {
        $record = Record::create($request->validated());
        if ($request->file) {
            $fileName = time().'_'.$request->file->getClientOriginalName();
            Storage::disk('private_files')->put($fileName, $request->file('file')->getContent());
            File::create(['name' => $fileName, 'record_id' => $record->getKey()]);
        }

        return new RecordResource($record);
    }

    public function getImg(string $slug)
    {
        $name = $this->redisService->getImgPath($slug);
        if ($name) {
            $file = Storage::disk('private_files')->path($name);
            if ($file) {
                return response()->file($file);
            } else {
                return abort(404);
            }
        }

        return abort(404);
    }
}
