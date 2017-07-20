@extends('layouts.main')

@section('content')

    <form class="form-horizontal" method="POST" action="{{ url('offers') }}">
	
        @include('layouts.error')
            
        {{ csrf_field() }}
        
        <input type="hidden" name="alternativeDate" id="alternativeDate">
        
        <div class="col-sm-9">
            
            <div class="col-sm-8">
                
                <!-- Titel -->
                <div class="form-group">
                    <label class="control-label" for="title">Titel</label>
                    <input required type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
                </div>
                
                <!-- Wrike-ID -->
                <div class="form-group">
                    <label class="control-label" for="wrike_offer_id">Wrike-ID</label>
                    <input type="text" class="form-control" id="wrike_offer_id" name="wrike_offer_id" value="{{ old('wrike_offer_id') }}">
                </div>
                    
            </div>

            <div class="col-sm-4">
                    
                <!-- Datum -->
                <div class="form-group">
                    <label class="control-label col-sm-3" for="date">Datum</label>
                    <div class="col-sm-9">
                        <input class="col-sm-3 custom-select form-control" id="date" name="date" value="{{ old('date') }}" />
                    </div>
                </div>
                    
                <!-- Status -->
                <div class="form-group">
                    <label class="control-label col-sm-3" for="status">Status</label>
                    <div class="col-sm-9">                
                        <select required class="custom-select form-control" id="status" name="status">
                            @if( ! old('status') )<option selected disabled hidden value>Bitte auswählen</option>@endif
                            <option {{ (old('status') == 'in_Vorbereitung' ? 'selected' : '') }} value="in_Vorbereitung">in Vorbereitung</option>
                            <option {{ (old('status') == 'angeboten' ? 'selected' : '') }} value="angeboten">angeboten</option>
                            <option {{ (old('status') == 'angenommen' ? 'selected' : '') }} value="angenommen">angenommen</option>
                            <option {{ (old('status') == 'abgelehnt' ? 'selected' : '') }} value="abgelehnt">abgelehnt</option>
                        </select>
                    </div>
                </div>
                    
                <!-- Preis -->
                <div class="form-group">
                    <label class="control-label col-sm-3" for="price">Preis</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="price" name="price" value="{{ old('price') }}">
                    </div>
                </div>
                    
                <!-- RPH (Rate Per Hour) -->
                <div class="form-group">
                    <label class="control-label col-sm-3" for="rph">RPH</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="rph" name="rph" value="{{ old('rph') }}">
                    </div>
                </div>
                    
            </div>
                
        </div>
        
        <div class="col-sm-3">
                
            <!-- Kunde -->
            <div class="form-group">
                <label class="control-label" for="customer_id">Kunde</label>
                <div class="">
                    <input type="text" class="form-control" id="customer_id" name="customer_id" value="{{ old('customer_id') }}">
                </div>
            </div>
            
        </div>
                 
        <div class="col-sm-9">
            <div class="col-sm-12">
                    
                    <!-- Anforderung -->
                    <div class="form-group">
                        <label class="control-label" for="requirement">Anforderung</label>
                        <textarea class="form-control" id="requirement" name="requirement" rows="10" value="{{ old('requirement') }}"></textarea>
                    </div>
                    
            </div>
        </div>
                
        <!-- Submit-Button -->
        <div class="form-group">
            <div class="col-sm-offset-1 col-sm-10">
                <button type="submit" class="btn btn-primary">Angebot anlegen</button>
            </div>
        </div>
        
    </form>
    
@endsection