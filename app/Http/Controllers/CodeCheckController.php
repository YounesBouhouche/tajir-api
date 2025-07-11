<?php

namespace App\Http\Controllers;

use App\Models\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CodeCheckController extends BaseController
{
    public function __invoke(Request $request)
    {
        // Check for code existence
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:reset_passwords',
            'code' => 'required|numeric'
        ]);

        if ($validator->fails())
            return $this->sendError('Invalid credentials', $validator->errors());

        // Check for code
        $data = $validator->getData();
        $passwordReset = ResetPassword::firstWhere(['email' => $data['email'], 'code' => $data['code']]);

        if (!isset($passwordReset))
            return $this->sendError('Invalid credentials', $validator->errors());

        // Check for expiration
        if ($passwordReset->created_at->addMinutes(10) < now()) {
            $passwordReset->delete();
            return $this->sendError('Expired code', code: 422);
        }

        $passwordReset->update(['verified' => 1]);

        return $this->sendResponse('Verified code');
    }
}
