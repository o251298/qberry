@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h1>
                Freezer Search
            </h1>
            @if(!isset($locations))
                <div class="alert alert-warning" role="alert">
                    To start the project, you need to run migrations and run the command to fill the tables with fake ones data :
                    <pre>php artisan db:seed</pre>
                </div>
            @endif
            <form method="post" action="{{route('client_get_block')}}">
                @csrf
                <div class="mb-3">
                    <label for="location" class="form-label">Location</label>
                    <select class="form-select" name="location" id="location" aria-label="Default select example">
                        <option selected>Open this select menu</option>
                        @foreach($locations as $location)
                            <option value="{{$location->id}}">{{$location->location}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="location" class="form-label">Volume</label>
                    <input type="text" name="volume" class="form-control" id="volume" aria-describedby="volume">
                </div>
                <div class="mb-3">
                    <label for="location" class="form-label">Temperature</label>
                    <input type="text" name="temperature" class="form-control" id="temperature"
                           aria-describedby="temperature">
                </div>
                <div class="mb-3">
                    <label for="location" class="form-label">Date start</label>
                    <input type="text" class="form-control" name="date_start" id="datetime1"
                           placeholder="Choose Date and Time"/>
                </div>
                <div class="mb-3">
                    <label for="location" class="form-label">Date end</label>
                    <input type="text" class="form-control" name="date_end" id="datetime2"
                           placeholder="Choose Date and Time"/>
                </div>
                <button type="submit" class="btn btn-primary">Calculate</button>
            </form>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#datetime1", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
        flatpickr("#datetime2", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
    </script>
@endsection
