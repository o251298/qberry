@extends('layouts.master')
@section('content')
<p>
    {{$sumInvoice}}
</p>
<ul>
    @foreach($payments as $payment)
        <li>{!! dump($payment); !!}</li>
    @endforeach
</ul>
@endsection
