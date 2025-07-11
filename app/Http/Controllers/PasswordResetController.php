<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class PasswordResetController extends BaseController
{
    public function sendLink(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|exists:users'
        ]);
        if ($validation->fails())
            return $this->sendError('Error', $validation->errors());
        $email = $validation->getValue('email');
        $status = Password::sendResetLink($email);
        if ($status != Password::RESET_LINK_SENT)
            return $this->sendError('Error', [], $status);
        return $this->sendResponse(['email' => $email], 'Reset link sent');
    }
}
