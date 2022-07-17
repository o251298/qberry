@extends('layouts.master')
@section('content')
    <div class="container">
        <ul>
            @foreach($users as $user)
                <li>
                    <a href="{{route('send_balance', $user)}}">{{$user->name}}</a>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
