<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchasingOrder;
use App\Models\Invoice;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $request = Request();
        $request->show = 'unreceived';
        $poUnrecivedCount = PurchasingOrder::searchDataCount($request);
        $invUnrecivedCount = Invoice::searchDataCount($request);
        return view('home', compact('poUnrecivedCount', 'invUnrecivedCount'));
    }
}
