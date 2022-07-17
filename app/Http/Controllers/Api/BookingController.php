<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{

    /**
     * Get all my bookings.
     * @OA\Get (
     *     path="/api/v1/my-bookings",
     *     tags={"My bookings"},
     *     summary="Get all my bookings.",
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *         @OA\Property( property="status",type="string", example="success"),
     *         @OA\Property( property="debt_for_the_current_month",type="int", example="250"),
     *              @OA\Property(
     *                 type="array",
     *                 property="data",
     *                      @OA\Items(
     *                      type="object",
     *                          @OA\Property( property="id",type="number", example="1"),
     *                          @OA\Property( property="user_id",type="number", example="1"),
     *                          @OA\Property( property="hash",type="string", example="$2y$10$2tAK97EjD7C2vjzVB1DBG.qZstWITxntkAdYXoqVqpDYarfIK6PF."),
     *                          @OA\Property( property="password_for_booking",type="string", example="FTI3Esra3EXJ"),
     *                          @OA\Property( property="amount",type="string", example="250"),
     *                          @OA\Property( property="blocks",type="array",
     *                          @OA\Items(type="object",
     *                              @OA\Property( property="id",type="number", example="1"),
     *                              @OA\Property( property="booking_id",type="number", example="1"),
     *                              @OA\Property( property="block_id",type="number", example="1"),
     *                              @OA\Property( property="start",type="string", example="2022-11-01 08:40:00"),
     *                              @OA\Property( property="end",type="string", example="2022-11-01 08:40:00"),
     *                              )
     *                          ),
     *                      ),
     *              ),
     *          )
     *      ),
     *     @OA\Response(
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
        try {
            $user = auth()->user();
            return new JsonResponse([
                'status'                     => 'success',
                'debt_for_the_current_month' => Booking::createInvoice($user)->sum('amount'),
                'data'                       => BookingResource::collection($user->getUserBookings()->get())], Response::HTTP_OK);
        } catch (\Exception $exception) {
            Log::error($exception);
            return new JsonResponse([
                'status' => "error",
                'error'  => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

    }


    /**
     * Get booking by id.
     * @OA\Get (
     *     path="/api/v1/booking/{booking_id}",
     *     tags={"Get booking by id"},
     *     summary="Get booking by id",
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *         @OA\Property( property="status",type="string", example="success"),
     *              @OA\Property(
     *                 type="array",
     *                 property="data",
     *                      @OA\Items(
     *                      type="object",
     *                          @OA\Property( property="id",type="number", example="1"),
     *                          @OA\Property( property="user_id",type="number", example="1"),
     *                          @OA\Property( property="status",type="number", example="1"),
     *                          @OA\Property( property="hash",type="string", example="$2y$10$2tAK97EjD7C2vjzVB1DBG.qZstWITxntkAdYXoqVqpDYarfIK6PF."),
     *                          @OA\Property( property="password_for_booking",type="string", example="FTI3Esra3EXJ"),
     *                          @OA\Property( property="amount",type="string", example="250"),
     *                          @OA\Property( property="date_payment",type="string", example="2022-07-27 19:00:00"),
     *                          @OA\Property( property="created_at",type="string", example="2022-07-27 19:00:00"),
     *                          @OA\Property( property="updated_at",type="string", example="2022-07-27 19:00:00"),
     *                      ),
     *              ),
     *          )
     *      ),
     *     @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property( property="message",type="string", example="Unauthenticated"),
     *          )
     *      ),
     *  )
     */
    public function show($id)
    {
        try {
            $user = auth()->user();
            $booking = $user->getUserBookings()->where('id', $id)->first();
            return new JsonResponse([
                'status' => 'success',
                'data' => $booking
            ]);
        } catch (\Exception $exception) {
            Log::error($exception);
            return new JsonResponse([
                'status' => "error",
                'error'  => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

    }
}
