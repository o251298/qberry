<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $user = auth()->user();
            return new JsonResponse([
                'status' => 'success',
                'data'   => BookingResource::collection($user->getUserBookings()->get())], Response::HTTP_OK);
        } catch (\Exception $exception)
        {
            Log::error($exception);
            return new JsonResponse([
                'status' => "error",
                'error'  => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

    }
}
