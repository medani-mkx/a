@extends('layouts.main')

@section('content')

    <!-- Keine-Berechtigung-Meldung 
    <div class="flash-message">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))
            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
            @endif
        @endforeach
    </div>-->
    
    <!-- Angebote -->
    @foreach ($offers as $offer)
        <div class="col-md-4 m-b-lg">
            <div class="panel panel-default panel-profile m-b-0">
                <div id="mwd-branch-container" class="panel-body text-center">
                    @include('model.offer')
                    <a href="{{ url('/offer/'.$offer->id) }}" class="btn btn-primary" role="button">
                        Details
                    </a>
                </div>
            </div>
        </div>
    @endforeach
    
@endsection