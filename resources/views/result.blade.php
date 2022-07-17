@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row">
        @if(isset($response))
            @if($response->status == 'success')
                <div class="alert alert-success" role="alert">
                    <strong>Success</strong> <br>
                    {{$response->data->password_for_booking}}
                </div>
            @else
                <div class="alert alert-danger" role="alert">
                    <strong>Error</strong><br>
                    {{$response->error}}
                </div>
            @endif
        @endif
        </div>
    </div>
@endsection
