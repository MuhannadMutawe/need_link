<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.user-login');
    }

    public function login(Request $request)
    {
        $data = $request->validate(
            [
                'email' => 'required|email',
                'password' => ['required', \Illuminate\Validation\Rules\Password::defaults()]
            ],
            [
                'email.required' => 'حقل الايميل مطلوب',
                'email.email' => 'يجب أن تكون صيغة الايميل صحيحة',
                'password.required' => 'أدخل كلمة المرور',
            ]
        );
        try {
            if (\Illuminate\Support\Facades\Auth::attempt($data)) {
                $request->session()->regenerate();
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'user_id' => auth()->id(),
                        'redirect' => route('dashboard.main'),
                    ]);
                }
            } else {
                return response()->json(['errors' => ['status' => ['كلمة المرور او الايميل غير صحيح']]] , 422);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Login Error: ' . $e->getMessage());
            return response()->json(['errors' => ['status' => ['حدث خطأ غير متوقع يرجى المحاولة لاحقا']]] , 422);
        }

    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('auth.user-login'),
            ]);
        }
        return view('auth.user-login');
    }
}
