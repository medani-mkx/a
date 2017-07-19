<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;

class CustomersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $customers = Customer::all();
        
        return view('pages.customers', [
            'offers'        => $customers,
        ]);
    }
    
    public function create()    {}
    
    public function store(Request $request)    {}
    
    public function show($id)    {}
    
    public function edit($id)    {}
    
    public function update(Request $request, $id)    {}
    
    public function destroy($id)    {}
}
