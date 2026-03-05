<?php

namespace Webkul\CustomInpostPaczkomatyShipping\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class InpostPaczkomatyController extends Controller
{
    public function store(Request $request)
    {
        session()->put('inpost_locker', [
            'id' => $request->paczkomat_id,
            'details' => $request->paczkomat_details
        ]);

        return response()->json([
            'success' => true
        ]);
    }
}