<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocationResource;
use App\Models\Block;
use App\Models\Location;
use App\Services\Block\BlockBuilder;
use App\Services\Block\BlockValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class LocationController extends Controller
{
    public function index(): JsonResponse
    {
        $LocationsCollection = Location::all();
        return new JsonResponse(LocationResource::collection($LocationsCollection), Response::HTTP_OK);
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

    public function create(Request $request): JsonResponse
    {

//        $location = Location::find(1);
//        foreach ($location->getBlocks()->get() as $block)
//        {
//            if ($block->status('2022-11-16', '2022-11-30') == Block::FREE_BLOCK) dump($block);
//
//        }
//
//        dd(1);
        $location    = (int)$request->get('location');
        $dataStart   = (string)$request->get('data_start');
        $dataEnd     = (string)$request->get('data_end');
        $volume      = (int)$request->get('volume');
        $temperature = (int)$request->get('temperature');
        try {
            $data       = new BlockValidator('2022-11-10', '2022-11-30', 10, -17);
            $collection = (new BlockBuilder())->location(2)
                ->fridges('temperature', $data->bottomTemp, $data->upperTemp)
                ->blocks($data->dataStart, $data->dataEnd)
                ->collection($data->needBlocks());
            return new JsonResponse([
                'info'     => [
                    'you_need_blocks' => $data->needBlocks(),
                    'we_have_block'   => count($collection),
                    'start_data'      => $data->dataStart,
                    'end_data'        => $data->dataEnd,
                    'hash'            => Hash::make(serialize($collection))
                ],
                'response' => $collection
            ], 200);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
