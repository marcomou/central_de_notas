<?php

namespace App\Http\Controllers;

use App\Models\Passport\RefreshToken;
use App\Models\Passport\Token;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function logout(Request $request)
    {
        $tokenId = $request->user()->token()->id;
        
        Token::findOrFail($tokenId)->update(['revoked' => true]);
        RefreshToken::where('access_token_id', $tokenId)->firstOrFail()->update(['revoked' => true]);

        return response()->noContent();
    }
}
