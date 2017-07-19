<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $settings = Setting::all();
        
        return view('pages.settings', [
            'offers'        => $settings,
        ]);
    }
    
    public function create()    {}
    
    public function store(Request $request)    {}
    
    public function show($id)    {}
    
    public function edit($id)    {}
    
    public function update(Request $request, $id)    {}
    
    public function destroy($id)    {}
}
