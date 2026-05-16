<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Throwable;

class AuthController extends Controller
{
    public function indexlogin()
    {
        return view('auth.user-login');
    }

    public function login(Request $request)
    {
        $data = $request->validate(
            [
                'email' => 'required|email|exists:users,email',
                'password' => 'required|string'
            ],
            [
                'email.required' => 'حقل الايميل مطلوب',
                'email.email' => 'يجب أن تكون صيغة الايميل صحيحة',
                'email.exists' => 'الايميل المدخل غير متوفر',
                'password.required' => 'أدخل كلمة المرور',
                'password.string' => 'يجب أن تكون كلمة المرور ارقاما و حروف و علامات خاصة'
            ]
        );
        try {
            if (Auth::attempt($data)) {
                return response()->json(['success' => true , 'redirect' => route('main.showLanding')]);
            }else{
                return response()->json(['errors' => ['status' => ['كلمة المرور او الايميل غير صحيح']]]);
            }
        }catch(Throwable $e){
            return response()->json(['errors' => ['status' => ['حدث خطأ غير متوقع يرجى المحاولة لاحقا']]]);
        }

    }

    public function indexRegister()
    {
        return view('auth.user-register');
    }
}
