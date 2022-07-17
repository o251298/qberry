<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use App\Models\Location;
class ClientController extends Controller
{
    public function index()
    {
        $locations = Location::all();
        return view('welcome', ['locations' => $locations]);
    }

    public function getBlocks(Request $request)
    {
        $client   = new Client();
        $response = Http::withHeaders([
            'Accept' => 'application/json'
        ])->withToken('10|EodsUwT15JZ1uc7PlovzG6hRAKHZL9XQIgidtpuB')->post('http://qberry-nginx/api/v1/booking-blocks-by-location', [
            'location'    => $request->location,
            'volume'      => $request->volume,
            'temperature' => $request->temperature,
            'date_start'  => $request->date_start,
            'date_end'    => $request->date_end,
        ]);
        $response = json_decode($response->body());
        return view('booking_confirm', ['response' => $response]);
    }

    public function confirmBooking(Request $request)
    {
        $client   = new Client();
        $response = Http::withHeaders([
            'Accept' => 'application/json'
        ])->withToken('10|EodsUwT15JZ1uc7PlovzG6hRAKHZL9XQIgidtpuB')->post('http://qberry-nginx/api/v1/confirm-booking', [
            'hash'    => $request->hash,
        ]);
        $response = json_decode($response->body());
        return view('result', ['response' => $response]);
    }
}
