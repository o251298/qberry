@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h1>
                Booking confirmation
            </h1>
            @if($response->status == 'success')
                <div class="card">
                    <div class="card-header">
                        {{$response->location}}
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Special title treatment</h5>
                        <p class="card-text">you_need_blocks: {{$response->you_need_blocks}}</p>
                        <p class="card-text">we_have_block: {{$response->we_have_block}}</p>
                        <p class="card-text">start_data: {{$response->start_data}}</p>
                        <p class="card-text">end_data: {{$response->end_data}}</p>
                        <p class="card-text">sum: {{$response->sum}}</p>
                    </div>
                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Block id</th>
                        <th scope="col">Fridge id</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($response->data as $item)
                        <tr>
                            <th scope="row">1</th>
                            <td>{{$item->id}}</td>
                            <td>{{$item->length}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <form method="post" action="{{route('client_confirm_booking')}}">
                    @csrf
                    <input type="hidden" name="hash" value="{{$response->hash}}">
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </form>
            @else
                <div class="alert alert-danger" role="alert">
                    {{$response->status}}
                </div>
            @endif
        </div>
    </div>
@endsection
