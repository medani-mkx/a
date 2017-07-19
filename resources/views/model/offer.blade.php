<div class="row">
    <div class="col-sm-1">
        <span class="hidden-md hidden-lg"><strong>Datum: </strong></span>
        {{ $offer->date }}
    </div>
    <div class="col-sm-3">
        <span class="hidden-md hidden-lg"><strong>Kunde: </strong></span>
        {{ $offer->customer_id }}
    </div>
    <div class="col-sm-3">
        <span class="hidden-md hidden-lg"><strong>Name: </strong></span>
        {{ $offer->title }}
    </div>
    <div class="col-sm-2">
        <span class="hidden-md hidden-lg"><strong>Preis: </strong></span>
        {{ $offer->price }}
    </div>
</div>