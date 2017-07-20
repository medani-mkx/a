@extends('layouts.main')

@section('content')
    
    <div class="row">
        <h3>
            <span>Angebote&nbsp;&nbsp;&nbsp;</span>
            <a id="mwd-new-offer" href="{{ url('offers/new') }}" class="btn btn-success" role="button">
                <span class="glyphicon glyphicon-plus"></span><span class="align-middle">&nbsp;&nbsp;&nbsp;Neu</span>
            </a>
        </h3>
    </div>
    
    <div class="row">
        
        <!-- Angebote nach Status wählen -->
        <div class="col-sm-2">
            <ul class="nav nav-pills nav-stacked mwd-offer-statuses">
                <li role="presentation" data-status="inPreparationOffers" class="active"><a href="#">In Vorbereitung</a></li>
                <li role="presentation" data-status="offeredOffers"><a href="#">Angeboten</a></li>
                <li role="presentation" data-status="acceptedOffers"><a href="#">Angenommen</a></li>
                <li role="presentation" data-status="rejectedOffers"><a href="#">Abgelehnt</a></li>
            </ul>
        </div>
        
        <!-- Space -->
        <div class="col-sm-1"></div>
        
        <!-- Liste der Angebote -->
        <div class="col-sm-9">
            
            <!-- Headings -->
            <div class="row hidden-xs hidden-sm">
                <div class="col-sm-1">
                    <strong>Datum</strong>
                </div>
                <div class="col-sm-3">
                    <strong>Kunde</strong>
                </div>
                <div class="col-sm-3">
                    <strong>Name</strong>
                </div>
                <div class="col-sm-2">
                    <strong>Preis</strong>
                </div>
            </div>
            
            <hr>
            
            <div class="mwd-offer-data">
                <!-- Angebote in Vorbereitung -->
                <div id="inPreparationOffers">
                    @foreach ($inPreparationOffers as $offer)
                        @include('model.offer')
                        <hr>
                    @endforeach
                </div>

                <!-- Angebotene Angebote -->
                <div id="offeredOffers" class="hidden">
                    @foreach ($offeredOffers as $offer)
                        @include('model.offer')
                        <hr>
                    @endforeach
                </div>

                <!-- Angenommene Angebote -->
                <div id="acceptedOffers" class="hidden">
                    @foreach ($acceptedOffers as $offer)
                        @include('model.offer')
                        <hr>
                    @endforeach
                </div>

                <!-- Abgelehnte Angebote -->
                <div id="rejectedOffers" class="hidden">
                    @foreach ($rejectedOffers as $offer)
                        @include('model.offer')
                        <hr>
                    @endforeach
                </div>
            </div>
            
        </div>
        
    </div>
    
    <!-- Modal -->
    @extends('layouts.modal', ['id'  => 'create-offer', 'label' => 'Neues Angebot'])
    @section('modal-body')
        <form class="form-horizontal" method="POST" action="{{ url('/offers') }}">
            {{ csrf_field() }}
            <div class="form-group">
                <label class="control-label col-sm-2" for="wrike_offer_id">Wrike-ID</label>
                <div class="col-sm-10">
                    <input id="wrike_offer_id" name="wrike_offer_id" class="custom-select form-control" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">Angebot anlegen</button>
                </div>
            </div>
        </form>
    @endsection
    
@endsection