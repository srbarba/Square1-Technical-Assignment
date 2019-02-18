<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\RegisterFormRequest;
use App\Http\Controllers\Controller;
use App\User;

class RegisterController extends Controller
{

    /**
     * TODO: Write doc
     */
    public function index(RegisterFormRequest $request)
    {
        $user = new User;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->save();

        return response([
            'status' => 'success',
            'data' => $user
        ], 200);
     }

}
