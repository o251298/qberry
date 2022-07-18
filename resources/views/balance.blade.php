@extends('layouts.master')
@section('content')
    <h1>
        Balance:
    </h1>
    <p>
        Amount for the current month : {{$sumInvoice}}
    </p>
    <ul>
        @foreach($payments as $payment)
            <li>{{$payment}}</li>
        @endforeach
    </ul>
@endsection
