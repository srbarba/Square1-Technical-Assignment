<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;

class RefreshController extends Controller
{

    /**
     * TODO: Write doc
     */
    public function index()
    {
        return response([
            'status' => 'success'
        ], 200);
    }

}
