@extends('layouts.master')
@section('content')
    <h1>
        A list of users:
    </h1>
    @if(isset($users))
        <ul>
            @foreach($users as $user)
                <li>
                    <a href="{{route('send_balance', $user)}}">{{$user->name}}</a>
                </li>
            @endforeach
        </ul>
    @else
        <p>
            Users not created
        </p>
    @endif
@endsection
