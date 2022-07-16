<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index() : JsonResponse
    {
        $user = User::find(1);
        return new JsonResponse(BookingResource::collection($user->getUserBookings()->get()));
    }
}
