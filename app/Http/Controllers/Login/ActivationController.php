<?php

namespace App\Http\Controllers\Login;


use App\Http\Controllers\Controller;
use App\Models\User\User;
use Activation;
use Sentinel;

class ActivationController extends Controller
{
    public function activateUser($email, $activationCode){

        $user = User::whereEmail($email)->first();

        //$sentinelUser = Sentinel::findById($user->id);

        if(Activation::complete($user, $activationCode)){
            return redirect('/login')->with(['success' => 'Activation Successful']);
        }else{
            return redirect('/login')->with(['error' => 'Invalid Activation Key']);
        }

    }
}
