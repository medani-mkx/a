<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facades\WrikeApiFacade as Wrike;
use App\Offer;
use App\Task;

class OffersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $inPreparationOffers = Offer::where('status', 'in_Vorbereitung')->get();
        $offeredOffers = Offer::where('status', 'angeboten')->get();
        $acceptedOffers = Offer::where('status', 'angenommen')->get();
        $rejectedOffers = Offer::where('status', 'abgelehnt')->get();
        
        return view('pages.offers', [
            'inPreparationOffers'   => $inPreparationOffers,
            'offeredOffers'         => $offeredOffers,
            'acceptedOffers'        => $acceptedOffers,
            'rejectedOffers'        => $rejectedOffers,
        ]);
    }
    
    public function create()    {}
    
    public function store(Request $request)
    {
        // Validation
        $this->validate(request(), [
            'title'     => 'required',
            'status'    => 'required',
        ]);
        
        if(request('id')) {
            $offer = Offer::find(request('id'));
        }
        else {
            $offer = new Offer();
        }
        
        $offer->title = request('title');
        $offer->wrike_project_id_v2 = request('wrike_project_id_v2');
        $offer->wrike_project_id_v3 = Wrike::convertLegacyId($offer->wrike_project_id_v2, 'Folder');
        $offer->date = request('alternativeDate');
        $offer->status = request('status');
        $offer->price = request('price');
        $offer->rph = request('rph');
        $offer->customer_id = request('customer_id');
        $offer->requirement = request('requirement');
        
        $offer->save();
        
        // Redirect
        return redirect('offers');
    }
    
    public function show($id)
    {
        if($id === 'new') {
            return view('pages.offer');
        }
        
        $offer = Offer::find($id);
        
        return view('pages.offer', [
            'offer' => $offer,
        ]);
    }
    
    public function edit($id)    {}
    
    public function update(Request $request, $id)    {}
    
    public function destroy($id)    {}
    
    public function importTasks(Request $request, $id)
    {
        $offer = Offer::find($id)->first();
        
        $wrikeProjectId = $offer->wrike_project_id_v3;
        
        $tasks = Wrike::getProjectTasks($wrikeProjectId);
        
        foreach($tasks as $task) {
            
        }
        
        return 'HHHHHHHHHHHHHHHHHIER';
    }
    
}
