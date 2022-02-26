<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\URL;

class VerifyEmailController extends Controller
{
    public function __construct()
    {
        // $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify','resend');
    }
    public function verify(Request $request, User $user)
    {
        // check if the url is a valid signed url
        if(! URL::hasValidSignature($request)){
            return response()->json([
                "errors" => [
                    "message" => "Invalid verification link"
                ]
                ], 422);
        }

        // check if user is already verified account
        if($user->hasVerifiedEmail()){
            return response()->json([
                "errors" => [
                    "message" => "Email address already verified"
                ]
                ], 422);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return response()->json(['message' => 'Email successfully verified'],200);
    }

    public function resend(Request $request)
    {
        $this->validate($request,[
            'email' => 'required'
        ]);

        $user = User::where('email',$request->email)->first();
        if(!$user){
            return response()->json(["errors" => [
                "email" => "No user could be found with this email address"
            ]], 422);
        }

        // check if user is already verified account
        if($user->hasVerifiedEmail()){
            return response()->json([
                "errors" => [
                    "message" => "Email address already verified"
                ]
                ], 422);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['status' => 'Verification Link Sent']);

    }
    // public function __invoke(Request $request): RedirectResponse
    // {
    //     $user = User::find($request->route('id'));

    //     if ($user->hasVerifiedEmail()) {
    //         return redirect(env('FRONT_URL') . '/email/verify/already-success');
    //     }

    //     if ($user->markEmailAsVerified()) {
    //         event(new Verified($user));
    //     }

    //     return redirect(env('FRONT_URL') . '/email/verify/success');
    // }
}
