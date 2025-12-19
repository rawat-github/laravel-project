<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTestRequest;
use App\Http\Requests\UpdateTestRequest;
use App\Services\TestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;
use ValueResearch\Scaffold\Controllers\BaseController;
use ValueResearch\Scaffold\Support\HttpResponse;

class TestController extends BaseController
{
    protected TestService $service;

    public function __construct(TestService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
     #[OA\Get(
         path: '',
         operationId: '',
         description: '',
         summary: '',
         tags: [''],
         responses: [
             new OA\Response(
                 response: Response::HTTP_OK,
                 description: 'success',
                 content: new OA\JsonContent(
                     properties: [
                         new OA\Property(property: 'success', type: 'boolean', example: true),
                         new OA\Property(property: 'message', type: 'string', example: 'success'),
                         new OA\Property(property: 'data',
                             type: 'array',
                             items: new OA\Items(),
                             example: []),
                     ]
                 )
             ),
         ]
     )]
    public function index(): JsonResponse
    {
        return HttpResponse::fromBaseResponse($this->service->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
     #[OA\Post(
          path: '',
          operationId: '',
          description: '',
          summary: '',
          requestBody: new OA\RequestBody(
              required: true,
              content: new OA\JsonContent(
                  properties: [

                  ]
              )
          ),
          tags: [''],
          responses: [
              new OA\Response(
                  response: Response::HTTP_OK,
                  description: 'success',
                  content: new OA\JsonContent(
                      properties: [
                          new OA\Property(property: 'success', type: 'boolean', example: true),
                          new OA\Property(property: 'message', type: 'string', example: 'success'),
                          new OA\Property(property: 'data',
                              type: 'array',
                              items: new OA\Items(),
                              example: []),
                      ]
                  )
              ),
          ]
     )]
    public function store(StoreTestRequest $request): JsonResponse
    {
        return HttpResponse::fromBaseResponse($this->service->create($request->all()));
    }

    /**
     * Display the specified resource.
     */
     #[OA\Get(
         path: '',
         operationId: '',
         description: '',
         summary: '',
         tags: [''],
         parameters: [
             new OA\Parameter(
                 name: '',
                 description: '',
                 in: 'query',
                 required: true,
                 schema: new OA\Schema(
                     type: 'integer',
                 )
             )
         ],
         responses: [
             new OA\Response(
                 response: Response::HTTP_OK,
                 description: 'success',
                 content: new OA\JsonContent(
                     properties: [
                         new OA\Property(property: 'success', type: 'boolean', example: true),
                         new OA\Property(property: 'message', type: 'string', example: 'success'),
                         new OA\Property(property: 'data',
                             type: 'array',
                             items: new OA\Items(),
                             example: []),
                     ]
                 )
             ),
         ]
     )]
    public function show(int $id): JsonResponse
    {
        $conditionData = ['id' => $id];
        return HttpResponse::fromBaseResponse($this->service->findByCondition($conditionData));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTestRequest $request, int $id): JsonResponse
    {
        return HttpResponse::fromBaseResponse($this->service->updateOnCondition(['id' => $id], $request->all()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $conditionData = ['id' => $id];
        return HttpResponse::fromBaseResponse($this->service->deleteOnCondition($conditionData));
    }


      /**
       * Filter the resource with specified values
       */

      #[OA\Get(
          path: '',
          operationId: '',
          description: '',
          summary: '',
          tags: [''],
          parameters: [
              new OA\Parameter(
                  name: '',
                  description: '',
                  in: 'query',
                  required: true,
                  schema: new OA\Schema(
                      type: 'string',
                  )
              )
          ],
          responses: [
              new OA\Response(
                  response: Response::HTTP_OK,
                  description: 'success',
                  content: new OA\JsonContent(
                      properties: [
                          new OA\Property(property: 'success', type: 'boolean', example: true),
                          new OA\Property(property: 'message', type: 'string', example: 'success'),
                          new OA\Property(property: 'data',
                              type: 'array',
                              example: [],
                              items: new OA\Items()),
                      ]
                  )
              ),
          ]
      )]


    public function filter(string $requestHash): JsonResponse
    {
       return HttpResponse::fromBaseResponse($this->service->getFilteredRecords($requestHash));
    }

    public function filterCustom(string $requestJson): JsonResponse
    {
        return HttpResponse::fromBaseResponse($this->service->getFilteredRecordsUrl($requestJson));
    }
}
