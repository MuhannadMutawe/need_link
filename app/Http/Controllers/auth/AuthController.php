<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            if (\Illuminate\Support\Facades\Auth::attempt($data)) {
                return response()->json(['success' => true, 'redirect' => route('dashbaord.main')]);
            } else {
                return response()->json(['errors' => ['status' => ['كلمة المرور او الايميل غير صحيح']]] , 422);
            }
        } catch (Throwable $e) {
            return response()->json(['errors' => ['status' => ['حدث خطأ غير متوقع يرجى المحاولة لاحقا']]] , 422);
        }

    }

    public function indexRegister()
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
                'password' => 'required|string|min:7|max:20|confirmed',
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
                'password.min' => 'يجب أن لا تقل كلمة المرور عن 7 ارقام او احرف لضمان قوتها',
                'password.max' => 'يجب أن لا تتجاوز كلمتك ال 20 حرفا او رقما',
                'password.confirmed' => 'يجب أن تكون كلمة المرور و تأكيدها متطابقة'
            ]
        );
        // dd($request->all());
        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['errors' => ['status' => [$e->getMessage()]]] , 422);
        }

        return response()->json(['success' => true , 'redirect' => route('auth.login')]);

    }
}
