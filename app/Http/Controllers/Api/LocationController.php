<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocationResource;
use App\Models\BlockBooking;
use App\Models\Booking;
use App\Models\Location;
use App\Services\Block\BlockBuilder;
use App\Services\Block\BlockPrepare;
use App\Services\Block\BlockValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\QueryException;

class LocationController extends Controller
{
    /**
     * Get List Locations
     * @OA\Get (
     *     path="/api/locations",
     *     tags={"Locations"},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                 type="array",
     *                 property="data",
     *                      @OA\Items(
     *                      type="object",
     *                          @OA\Property( property="id",type="number", example="1"),
     *                          @OA\Property( property="location",type="string", example="Wilmington"),
     *                          @OA\Property( property="timezone",type="string", example="GMT-4"),
     *                          @OA\Property( property="count_free_blocks",type="number", example="14"),
     *                      ),
     *              ),
     *          )
     *      )
     *  )
     */
    public function index(): JsonResponse
    {
        $LocationsCollection = Location::all();
        return new JsonResponse(['status' => "success", "data" => LocationResource::collection($LocationsCollection)], Response::HTTP_OK);
    }


    public function show($id): JsonResponse
    {
        $response = [];
        if ($getLocationById = Location::find((int)$id)) {
            $response                = $getLocationById->toArray();
            $response["blocks_info"] = $getLocationById->getFreeBlocks()->get()->toArray();
        }
        return new JsonResponse($response, Response::HTTP_OK);
    }

    /**
     * @OA\Post (
     *     path="/api/get-blocks-by-location",
     *     tags={"Get blocks"},
     *       @OA\Parameter(
     *         description="Parameter with mutliple examples",
     *         in="path",
     *         name="location",
     *         required=true,
     *         @OA\Schema(type="int"),
     *         @OA\Examples(example="int", value="1", summary="An int value."),
     *     ),
     *     @OA\Parameter(
     *         description="Parameter with mutliple examples",
     *         in="path",
     *         name="date_start",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="int", value="2022-11-04", summary="date starts"),
     *     ),
     *     @OA\Parameter(
     *         description="Parameter with mutliple examples",
     *         in="path",
     *         name="date_end",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="int", value="2022-11-20", summary="date ends"),
     *     ),
     *     @OA\Parameter(
     *         description="Parameter with mutliple examples",
     *         in="path",
     *         name="volume",
     *         required=true,
     *         @OA\Schema(type="int"),
     *         @OA\Examples(example="int", value="2", summary="volume"),
     *     ),
     *     @OA\Parameter(
     *         description="Parameter with mutliple examples",
     *         in="path",
     *         name="temperature",
     *         required=true,
     *         @OA\Schema(type="int"),
     *         @OA\Examples(example="int", value="-3", summary="temperature"),
     *     ),
     *       @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="location",
     *                          type="int"
     *                      ),
     *                      @OA\Property(
     *                          property="date_start",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="date_end",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="volume",
     *                          type="int"
     *                      ),
     *                      @OA\Property(
     *                          property="temperature",
     *                          type="int"
     *                      ),
     *                 ),
     *                 example={
     *                         "location" : 1,
    "date_start" : "2022-11-01",
    "date_end" : "2022-11-20",
    "volume" : 2,
    "temperature" : -3
     *                }
     *             )
     *         )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property( property="status",type="string", example="success"),
     *              @OA\Property( property="you_need_blocks",type="number", example="1"),
     *              @OA\Property( property="we_have_block",type="number", example="1"),
     *              @OA\Property( property="start_data",type="string", example="2022-11-01"),
     *              @OA\Property( property="end_data",type="string", example="2022-11-02"),
     *              @OA\Property( property="location",type="string", example="Wilmington"),
     *              @OA\Property( property="hash",type="string", example="$2y$10$ch/XPMjQ0ZAEAibE.ko5iOSOUFcLi4h2K.bDfwac8HpFhd/QsN3F"),
     *              @OA\Property(
     *                 type="array",
     *                 property="data",
     *                      @OA\Items(
     *                      type="object",
     *                          @OA\Property( property="id",type="number", example="id"),
     *                          @OA\Property( property="fridge_id",type="number", example="23"),
     *                          @OA\Property( property="length",type="number", example="2"),
     *                          @OA\Property( property="width",type="number", example="1"),
     *                          @OA\Property( property="height",type="number", example="1"),
     *                          @OA\Property( property="volume",type="number", example="2"),
     *                      ),
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *     response=400,
     *     description="Bad request",
     *          @OA\JsonContent(
     *              @OA\Property( property="status",type="string", example="error"),
     *              @OA\Property( property="error",type="string", example="Описание ошибки"),
     *          )
     *      ),
     *  )
     *
     *
     */
    public function create(Request $request): JsonResponse
    {
        $location    = (int)$request->get('location');
        $dateStart   = (string)$request->get('date_start');
        $dateEnd     = (string)$request->get('date_end');
        $volume      = (int)$request->get('volume');
        $temperature = (int)$request->get('temperature');
        try {
            $prepare = new BlockPrepare($dateStart, $dateEnd, $volume, $temperature, $location);
            return new JsonResponse([
                'status'          => "success",
                'you_need_blocks' => $prepare->data->needBlocks(),
                'we_have_block'   => count($prepare->collection),
                'start_data'      => $prepare->data->dataStart,
                'end_data'        => $prepare->data->dataEnd,
                'hash'            => $prepare->hash,
                'data'            => $prepare->collection
            ], 200);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'status' => "error",
                'error'  => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }


    /**
     * @OA\Post (
     *     path="/api/location/get-blocks",
     *     tags={"Get blocks"},
     *       @OA\Parameter(
     *         description="Parameter with mutliple examples",
     *         in="path",
     *         name="location",
     *         required=true,
     *         @OA\Schema(type="int"),
     *         @OA\Examples(example="int", value="1", summary="An int value."),
     *     ),
     *     @OA\Parameter(
     *         description="Parameter with mutliple examples",
     *         in="path",
     *         name="date_start",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="int", value="2022-11-04", summary="date starts"),
     *     ),
     *     @OA\Parameter(
     *         description="Parameter with mutliple examples",
     *         in="path",
     *         name="date_end",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="int", value="2022-11-20", summary="date ends"),
     *     ),
     *     @OA\Parameter(
     *         description="Parameter with mutliple examples",
     *         in="path",
     *         name="volume",
     *         required=true,
     *         @OA\Schema(type="int"),
     *         @OA\Examples(example="int", value="2", summary="volume"),
     *     ),
     *     @OA\Parameter(
     *         description="Parameter with mutliple examples",
     *         in="path",
     *         name="temperature",
     *         required=true,
     *         @OA\Schema(type="int"),
     *         @OA\Examples(example="int", value="-3", summary="temperature"),
     *     ),
     *       @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="location",
     *                          type="int"
     *                      ),
     *                      @OA\Property(
     *                          property="date_start",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="date_end",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="volume",
     *                          type="int"
     *                      ),
     *                      @OA\Property(
     *                          property="temperature",
     *                          type="int"
     *                      ),
     *                 ),
     *                 example={
     *                         "location" : 1,
    "date_start" : "2022-11-01",
    "date_end" : "2022-11-20",
    "volume" : 2,
    "temperature" : -3
     *                }
     *             )
     *         )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property( property="status",type="string", example="success"),
     *              @OA\Property( property="you_need_blocks",type="number", example="1"),
     *              @OA\Property( property="we_have_block",type="number", example="1"),
     *              @OA\Property( property="start_data",type="string", example="2022-11-01"),
     *              @OA\Property( property="end_data",type="string", example="2022-11-02"),
     *              @OA\Property( property="location",type="string", example="Wilmington"),
     *              @OA\Property( property="hash",type="string", example="$2y$10$ch/XPMjQ0ZAEAibE.ko5iOSOUFcLi4h2K.bDfwac8HpFhd/QsN3F"),
     *              @OA\Property(
     *                 type="array",
     *                 property="data",
     *                      @OA\Items(
     *                      type="object",
     *                          @OA\Property( property="id",type="number", example="id"),
     *                          @OA\Property( property="fridge_id",type="number", example="23"),
     *                          @OA\Property( property="length",type="number", example="2"),
     *                          @OA\Property( property="width",type="number", example="1"),
     *                          @OA\Property( property="height",type="number", example="1"),
     *                          @OA\Property( property="volume",type="number", example="2"),
     *                      ),
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *     response=400,
     *     description="Bad request",
     *          @OA\JsonContent(
     *              @OA\Property( property="status",type="string", example="error"),
     *              @OA\Property( property="error",type="string", example="Описание ошибки"),
     *          )
     *      ),
     *  )
     *
     *
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $key      = (string)$request->get('hash');
            $objs     = unserialize(Redis::get($key));
            $objsTime = (array)json_decode(Redis::get($key . "&time"));

            $booking = Booking::create([
                'user_id' => 1,
                'hash'    => $key,
                'status'  => 1,
                'amount'  => 100
            ]);
            foreach ($objs as $item) {
                $newProduct             = new BlockBooking();
                $newProduct->booking_id = $booking->id;
                $newProduct->block_id   = $item->id;
                $newProduct->start      = $objsTime['start'];
                $newProduct->end        = $objsTime['end'];
                $newProduct->save();
            }
            return new JsonResponse([
                'status' => "success",
                'data'   => $booking
            ], Response::HTTP_CREATED);
        } catch (QueryException $exceptionDB){
            Log::error($exceptionDB);
            return new JsonResponse([
                'status' => "error",
                'error'  => 'Error saving to database'
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $exception) {
            Log::error($exception);
            return new JsonResponse([
                'status' => "error",
                'error'  => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
