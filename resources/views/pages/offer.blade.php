@extends('layouts.main')

@section('content')

    <form class="form-horizontal" method="POST" action="{{ url('offers') }}">
	
        @include('layouts.error')
            
        {{ csrf_field() }}
        
        <input type="hidden" name="id" id="id" value="{{ isset($offer->id) ? $offer->id : '' }}">
        
        <input type="hidden" name="alternativeDate" id="alternativeDate">
        
        <div class="row">

            <div class="col-sm-9">

                <!-- Titel -->
                <div class="form-group">
                    <label class="control-label" for="title">Titel</label>
                    <input required type="text" class="form-control" id="title" name="title" value="{{ isset($offer->title) ? $offer->title : old('title') }}">
                </div>

                <!-- Anforderung -->
                <div class="form-group">
                    <label class="control-label" for="requirement">Anforderung</label>
                    <textarea class="form-control" id="requirement" name="requirement" rows="10" value="{{ isset($offer->requirement) ? $offer->requirement : old('requirement') }}"></textarea>
                </div>

            </div>

            <div class="col-sm-3">

                <!-- Kunde -->
                <div class="form-group">
                    <label class="control-label col-sm-3" for="customer_id">Kunde</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="customer_id" name="customer_id" value="{{ isset($offer->customer_id) ? $offer->customer_id : old('customer_id') }}">
                    </div>
                </div>

                <!-- Datum -->
                <div class="form-group">
                    <label class="control-label col-sm-3" for="date">Datum</label>
                    <div class="col-sm-9">
                        <input class="col-sm-3 custom-select form-control" id="date" name="date" value="{{ isset($offer->date) ? $offer->date : old('date') }}" />
                    </div>
                </div>

                <!-- Status -->
                <div class="form-group">
                    <label class="control-label col-sm-3" for="status">Status</label>
                    <div class="col-sm-9">                
                        <select required class="custom-select form-control" id="status" name="status">
                            @if( ! old('status') )<option selected disabled hidden value>Bitte auswählen</option>@endif
                            <option {{ isset($offer->status) && $offer->status == 'in_Vorbereitung' ? 'selected' : (old('status') == 'in_Vorbereitung'  ? 'selected' : '') }} value="in_Vorbereitung"   >in Vorbereitung</option>
                            <option {{ isset($offer->status) && $offer->status == 'angeboten'       ? 'selected' : (old('status') == 'angeboten'        ? 'selected' : '') }} value="angeboten"         >angeboten</option>
                            <option {{ isset($offer->status) && $offer->status == 'angenommen'      ? 'selected' : (old('status') == 'angenommen'       ? 'selected' : '') }} value="angenommen"        >angenommen</option>
                            <option {{ isset($offer->status) && $offer->status == 'abgelehnt'       ? 'selected' : (old('status') == 'abgelehnt'        ? 'selected' : '') }} value="abgelehnt"         >abgelehnt</option>
                        </select>
                    </div>
                </div>

                <!-- Preis -->
                <div class="form-group">
                    <label class="control-label col-sm-3" for="price">Preis</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="price" name="price" value="{{ isset($offer->price) ? $offer->price : old('price') }}">
                    </div>
                </div>

                <!-- RPH (Rate Per Hour) -->
                <div class="form-group">
                    <label class="control-label col-sm-3" for="rph">RPH</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="rph" name="rph" value="{{ isset($offer->rph) ? $offer->rph : old('rph') }}">
                    </div>
                </div>

                <!-- Submit-Button -->
                <div id="mwd-save-offer-button" class="form-group">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-primary">Speichern</button>
                    </div>
                </div>

            </div>
                
        </div>
                 
        <div class="row">
                
            <!-- Wrike-ID -->
            <div class="form-group">
                <label class="control-label col-sm-1" for="wrike_project_id_v2">Wrike-ID</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="wrike_project_id_v2" name="wrike_project_id_v2" value="{{ isset($offer->wrike_project_id_v2) ? $offer->wrike_project_id_v2 : old('wrike_project_id_v2') }}">
                </div>
                @isset($offer->id)
                <a id="mwd-new-offer col-sm-2" href="{{ url('tasks/import/offer/' . $offer->id) }}" class="btn btn-success" role="button">
                    <span class="align-middle">Import</span>
                </a>
                @endisset
            </div>

            <!-- PDF-Button -->
            <div id="mwd-offer-pdf-button" class="form-group">
                <a href="{{ url('offer/' . $offer->id . '/pdf') }}" class="btn btn-primary" role="button">
                    <span class="align-middle">PDF</span>
                </a>
            </div>
            
        </div>
        
    </form>

    <div id="mwd-task-list" class="row">
        @isset($tasks)
            @foreach ($tasks as $task)
                @include('model.task', ['space' => ''])
            @endforeach
        @else
            Keine Tasks vorhanden.
        @endisset
    </div>
    
@endsection