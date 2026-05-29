<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.user-register');
    }

    public function register(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:50|min:8',
                'username' => 'required|unique:users,username|max:50|min:8',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|string|max:11|unique:users,phone',
                'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            ],
            [
                'name.required' => 'أدخل اسمك كاملا',
                'name.string' => 'اسمك يجب أن يكون من البيانات النصية',
                'name.max' => 'اقصى حد لعدد الحروف هو 50 حرف',
                'name.min' => 'اقل حد للحروف هو 8 أحرف',
                'username.required' => 'أدخل اسم مستخدم جديد',
                'username.unique' => 'اسم المستخدم هذا مستخدم مسبقا يرجى اختيار اسم مستخدم اخر',
                'username.max' => 'اقصى حد لعدد الحروف هو 50 حرف',
                'username.min' => 'اقل حد للحروف هو 8 احرف',
                'email.required' => 'أدخل البريد الالكتروني الخاص بك',
                'email.email' => 'يجب أن تكون صيغة الايميل مثل : example@gmail.com',
                'email.unique' => 'البريد الالكتروني هذا مستخدم مسبقا يرجى اختيار حساب أخر',
                'phone.required' => 'أدخل رقمك الخاص',
                'phone.max' => 'يجب أن لا يتجاوز رقم الهاتف ال10 أرقام',
                'phone.unique' => 'رقم الهانف هذا مستخدم مسبقا يرجى اضافة رقم هاتف اخر',
                'password.required' => 'أدخل كلمة المرور',
                'password.confirmed' => 'يجب أن تكون كلمة المرور و تأكيدها متطابقة'
            ]
        );
        try {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => $request->password,
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Registration Error: ' . $e->getMessage());
            return response()->json(['errors' => ['status' => ['حدث خطأ أثناء إنشاء الحساب، يرجى المحاولة لاحقاً']]] , 422);
        }

        Auth::login($user);
        request()->session()->regenerate();

        return response()->json([
            'success' => true,
            'redirect' => route('dashboard.main'),
        ]);

    }
}
