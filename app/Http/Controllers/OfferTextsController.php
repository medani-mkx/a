<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OfferText;

class OfferTextsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $offerTexts = OfferText::all();
        
        return view('pages.offerTexts', [
            'offers'        => $offerTexts,
        ]);
    }
    
    public function create()    {}
    
    public function store(Request $request)    {}
    
    public function show($id)    {}
    
    public function edit($id)    {}
    
    public function update(Request $request, $id)    {}
    
    public function destroy($id)    {}
}
