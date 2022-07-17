<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocationResource;
use App\Models\Block;
use App\Models\Location;
use App\Services\Block\BlockPrepare;
use App\Services\Order\BookingCreator;
use App\Services\Order\OrderCreator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class LocationController extends Controller
{
    /**
     * Get all the locations that are on the service
     * @OA\Get (
     *     path="/api/v1/locations",
     *     tags={"Locations"},
     *     summary="Get all the locations that are on the service",
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property( property="status",type="string", example="success"),
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
     * )
     *      ),
     * @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property( property="message",type="string", example="Unauthenticated"),
     *          )
     *      ),
     *  )
     */
    public function index(): JsonResponse
    {
        $LocationsCollection = Location::all();
        return new JsonResponse([
            'status' => "success", "data" => LocationResource::collection($LocationsCollection)
        ], Response::HTTP_OK);
    }


    /**
     * @OA\Post (
     *     path="/api/v1/booking-blocks-by-location",
     *     tags={"Get blocks by location"},
     *     summary="Get all available blocks by using data filtering",
     *       @OA\Parameter(
     *         description="ID location",
     *         in="path",
     *         name="location",
     *         required=true,
     *
     *         @OA\Examples(example="int", value="1", summary="An int value."),
     *     ),
     *     @OA\Parameter(
     *         description="Block lease start date. The start date cannot be less than the current date.",
     *         in="path",
     *         name="date_start",
     *         required=true,
     *
     *         @OA\Examples(example="int", value="2022-11-04", summary="date starts"),
     *     ),
     *     @OA\Parameter(
     *         description="Block lease ends date",
     *         in="path",
     *         name="date_end",
     *         required=true,
     *
     *         @OA\Examples(example="int", value="2022-11-20", summary="date ends"),
     *     ),
     *     @OA\Parameter(
     *         description="Volume of products in m3",
     *         in="path",
     *         name="volume",
     *         required=true,
     *
     *         @OA\Examples(example="int", value="2", summary="volume"),
     *     ),
     *     @OA\Parameter(
     *         description="Refrigerator temperature. The temperature cannot be more than 0 degrees Celsius. If you need to specify temperature 0 - specify -2",
     *         in="path",
     *         name="temperature",
     *         required=true,
     *
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
     *                          type="int",
     *                          description="ID location"
     *                      ),
     *                      @OA\Property(
     *                          property="date_start",
     *                          type="string",
     *                          description="Block lease start date. The start date cannot be less than the current date."
     *                      ),
     *                      @OA\Property(
     *                          property="date_end",
     *                          type="string",
     *                          description="Block lease ends date"
     *                      ),
     *                      @OA\Property(
     *                          property="volume",
     *                          type="int",
     *                          description="Volume of products in m3"
     *                      ),
     *                      @OA\Property(
     *                          property="temperature",
     *                          type="int",
     *                          description="Refrigerator temperature. The temperature cannot be more than 0 degrees Celsius. If you need to specify temperature 0 - specify -2"
     *                      ),
     *                 ),
     *                 example={
     *                         "location" : 1,
    "date_start" : "2022-11-01 12:00:00",
    "date_end" : "2022-11-20 12:00:00",
    "volume" : 2,
    "temperature" : -3
     *                          }
     *             )
     *         )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property( property="status",type="string", example="success"),
     *              @OA\Property( property="you_need_blocks",type="number", example="2"),
     *              @OA\Property( property="we_have_block",type="number", example="2"),
     *              @OA\Property( property="start_data",type="string", example="2022-11-01 12:00:00"),
     *              @OA\Property( property="end_data",type="string", example="2022-11-02 12:00:00"),
     *              @OA\Property( property="location",type="string", example="Wilmington"),
     *              @OA\Property( property="sum",type="float", example="10"),
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
     *                          @OA\Property( property="status",type="number", example="0"),
     *                      ),
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *      response=400,
     *      description="Bad request",
     *          @OA\JsonContent(
     *              @OA\Property( property="status",type="string", example="error"),
     *              @OA\Property( property="error",type="string", example="Описание ошибки"),
     *          )
     *      ),
     *      @OA\Response(
     *      response=401,
     *      description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property( property="message",type="string", example="Unauthenticated"),
     *          )
     *      ),
     *  )
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
                'sum'             => Block::PAYMENT_PER_DAY * count($prepare->collection) * ($prepare->data->diff->days ? $prepare->data->diff->days : 1),
                'location'        => Location::find($location)->first()['location'],
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
     *     path="/api/v1/confirm-booking",
     *     tags={"Confirm order"},
     *       @OA\Parameter(
     *         description="This is a token that is issued once when an order is created. This token can be obtained in the endpoint '/api/v1/order-blocks-by-location'",
     *         in="path",
     *         name="hash",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="hash", value="$2y$10$ch/XPMjQ0ZAEAibE.ko5iOSOUFcLi4h2K.bDfwac8HpFhd/QsN3F", summary="hash"),
     *     ),
     *       @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="hash",
     *                          type="string"
     *                      ),
     *                 ),
     *                 example={"hash" : "$2y$10$ch/XPMjQ0ZAEAibE.ko5iOSOUFcLi4h2K.bDfwac8HpFhd/QsN3F"}
     *             )
     *         )
     *      ),
     *     @OA\Response(
     *         response=201,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property( property="status",type="string", example="success"),
     *              @OA\Property(
     *                 type="array",
     *                 property="data",
     *                      @OA\Items(
     *                      type="object",
     *                          @OA\Property( property="user_id",type="number", example="2"),
     *                          @OA\Property( property="hash",type="string", example="$2y$10$ch/XPMjQ0ZAEAibE.ko5iOSOUFcLi4h2K.bDfwac8HpFhd/QsN3F"),
     *                          @OA\Property( property="status",type="number", example="1"),
     *                          @OA\Property( property="amount",type="number", example="10"),
     *                          @OA\Property( property="date_payment",type="string", example="2022-07-22T18:00:00.000000Z"),
     *                          @OA\Property( property="updated_at",type="number", example="2022-07-17T10:51:49.000000Z"),
     *                          @OA\Property( property="created_at",type="number", example="2022-07-17T10:51:49.000000Z"),
     *                          @OA\Property( property="id",type="number", example="1"),
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
     *          ),
     *      ),
     *       @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property( property="message",type="string", example="Unauthenticated"),
     *          ),
     *      ),
     *      ),
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $hash           = (string)$request->get('hash');
            $bookingCreator = new BookingCreator($hash);
            $booking        = $bookingCreator->save();
            $orderCreator   = new OrderCreator($booking);
            $orderCreator->save($bookingCreator->objects, $bookingCreator->time);
            return new JsonResponse([
                'status' => "success",
                'data'   => $booking
            ], Response::HTTP_CREATED);
        } catch (QueryException $exceptionDB) {
            Log::error($exceptionDB);
            return new JsonResponse([
                'status' => "error",
                'error'  => 'Error saving to database',
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
