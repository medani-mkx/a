<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Offer;

class OffersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $inPreparationOffers = Offer::all();
        $offeredOffers = Offer::all();
        $acceptedOffers = Offer::all();
        $rejectedOffers = Offer::all();
        
        return view('pages.offers', [
            'inPreparationOffers'   => $inPreparationOffers,
            'offeredOffers'         => $offeredOffers,
            'acceptedOffers'        => $acceptedOffers,
            'rejectedOffers'        => $rejectedOffers,
        ]);
    }
    
    public function create()
    {
        //
    }
    
    public function store(Request $request)
    {
        // Validation
        $this->validate(request(), [
            'wrike_offer_id'   => 'required',
        ]);
        
        // Build and save
        $offer = new Offer();
        $offer->wrike_offer_id = request('wrike_offer_id');
        $offer->status = 'in_Vorbereitung';
        $offer->save();
        
        // Redirect
        return redirect('angebote');
    }
    
    public function show($id)
    {
    }
    
    public function edit($id)
    {
        //
    }
    
    public function update(Request $request, $id)
    {
        //
    }
    
    public function destroy($id)
    {
        //
    }
}
