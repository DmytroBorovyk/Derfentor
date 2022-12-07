<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecordCreateRequest;
use App\Http\Resources\RecordResource;
use App\Models\Record;
use App\Services\RecordService;
use App\Services\RedisService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Annotations as OA;

class RecordController extends Controller
{
    private RecordService $recordService;

    public function __construct(RecordService $service, RedisService $redisService)
    {
        $this->recordService = $service;
    }

    /**
     * @OA\Info(title="Records api controller", version="1")
     *
     * @OA\Get(
     *      path="/api/records",
     *      operationId="RecordsPagination",
     *      summary="Records pagination",
     *      tags={"Records"},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *               @OA\Items(ref="#/components/schemas/RecordResource")
     *          )
     *      )
     *  )
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return $this->recordService->index($request);
    }

    /**
     * @OA\Get(
     *      path="/api/record/{record}",
     *      operationId="RecordDetail",
     *      summary="Record detail info",
     *      tags={"Records"},
     *      @OA\Parameter(
     *          name="record",
     *          description="Record id",
     *          required=true,
     *          in="path",
     *          example="1"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *               @OA\Items(ref="#/components/schemas/RecordResource")
     *          )
     *      )
     *  )
     */
    public function show(Record $record): RecordResource
    {
        return $this->recordService->show($record);
    }

    /**
     * @OA\Schema( schema="RecordCreateRequest",
     *      @OA\Property(property="name", type="string", example="name"),
     *      @OA\Property(property="type", type="string", example="1"),
     *      @OA\Property(property="description", type="string", example="description"),
     *      @OA\Property(property="file", type="file")
     *  ),
     * @OA\Post(
     *      path="/api/record",
     *      operationId="RecordCreate",
     *      summary="Create record",
     *      tags={"Records"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/RecordCreateRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *               @OA\Items(ref="#/components/schemas/RecordResource")
     *          )
     *      )
     *  )
     */
    public function create(RecordCreateRequest $request): RecordResource|JsonResponse
    {
        return $this->recordService->create($request);
    }

    public function getImg(string $slug)
    {
        return $this->recordService->getImg($slug);
    }
}
