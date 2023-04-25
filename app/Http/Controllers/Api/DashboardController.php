<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchasingOrder;
use App\Models\Invoice;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:dashboard-index|dashboard-create|dashboard-edit|dashboard-delete', ['only' => ['index']]);
        $this->middleware('permission:dashboard-show', ['only' => ['show']]);
        $this->middleware('permission:dashboard-create', ['only' => ['create','store']]);
        $this->middleware('permission:dashboard-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:dashboard-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request = Request();
        $request->show = 'unreceived';
        $poCount = PurchasingOrder::searchDataCount($request);
        $invCount = Invoice::searchDataCount($request);

        return response()->json([
            'status'                    => true,
            'poCount'          => (int)$poCount,
            'invCount'         => (int)$invCount,
        ], 200);
    }
}
