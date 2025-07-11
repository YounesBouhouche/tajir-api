<?php

namespace App\Http\Controllers;

use App\Mail\SendCodeResetPassword;
use App\Models\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends BaseController
{
    public function __invoke(Request $request)
    {
        // Check for email existence
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users'
        ]);
        if ($validator->fails())
            return $this->sendError('Invalid credentials', $validator->errors());

        // Delete previous codes
        $data = $validator->getData();
        $email = $request['email'];
        ResetPassword::where('email', $email)->delete();

        // Generate new code
        $data['code'] = mt_rand(100000, 999999);
        ResetPassword::create($data);
        Mail::to($data['email'])->send((new SendCodeResetPassword($data['code']))->build());

        return $this->sendResponse('Sent');
    }
}
