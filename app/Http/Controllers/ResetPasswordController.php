<?php

namespace App\Http\Controllers;

use App\Models\ResetPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends BaseController
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:reset_passwords',
            'new-password' => 'required|confirmed:confirm-new-password',
            'confirm-new-password' => 'required'
        ]);

        if ($validator->fails())
            return $this->sendError('Invalid credentials', $validator->errors());

        // Check if code is verified
        $passwordReset = ResetPassword::firstWhere($request->only(['email']));

        if (!$passwordReset->verified) {
            return $this->sendError('Not verified');
        }

        // Reset password
        User::firstWhere('email', $request->email)->update(['password' => $request->get('new-password')]);

        $passwordReset->delete();

        return $this->sendResponse('Success');
    }
}
