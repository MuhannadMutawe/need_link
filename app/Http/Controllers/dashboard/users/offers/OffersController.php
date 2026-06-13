<?php

namespace App\Http\Controllers\dashboard\users\offers;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Http\Request;

class OffersController extends Controller
{
    /**
     * Display a listing of all offers for a specific request.
     */
    public function index(ServiceRequest $serviceRequest)
    {
        $offers = $serviceRequest->offers()->with('user')->latest()->get();

        if (request()->expectsJson()) {
            return response()->json($offers);
        }

    return view('dashboard.users.offers', compact('offers'));

    }

    /**
     * Display a listing of all offers for the authenticated user.
     */
    public function myOffers()
    {
        $user = auth()->user();
        // TODO: Fallback for when auth is not fully configured yet
        if (!$user) {
            $user = User::first(); 
        }

        $offers = $user->offers()->with(['serviceRequest', 'user'])->latest()->get();

        if (request()->expectsJson()) {
            return response()->json($offers);
        }

        return view('dashboard.users.offers', compact('offers'));
    }

    /**
     * Store a newly created offer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'request_id'     => 'required|exists:requests,id',
            'message'        => 'required|string',
            'proposed_price' => 'required|numeric|min:0',
            'currency_code'  => 'nullable|string|size:3',
            'estimated_time' => 'nullable|integer|min:1',
            'time_unit'      => 'nullable|in:hours,days,weeks',
            'expires_at'     => 'nullable|date|after:now',
        ], [
            'request_id.required'     => 'الطلب مطلوب',
            'message.required'        => 'الرسالة مطلوبة',
            'proposed_price.required' => 'السعر المقترح مطلوب',
        ]);

        // TODO: replace with auth()->id() when auth middleware is added
        $validated['user_id'] = $request->input('user_id', auth()->id());

        $offer = Offer::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'تم إرسال العرض بنجاح',
                'offer'   => $offer->load(['user', 'serviceRequest']),
            ], 201);
        }

        return redirect()->back()->with('success', 'تم إرسال العرض بنجاح');
    }

    /**
     * Update the specified offer.
     */
    public function update(Request $request, Offer $offer)
    {
        $validated = $request->validate([
            'request_id'     => 'sometimes|exists:requests,id',
            'message'        => 'sometimes|string',
            'proposed_price' => 'sometimes|numeric|min:0',
            'currency_code'  => 'nullable|string|max:4',
            'estimated_time' => 'nullable|integer|min:1',
            'time_unit'      => 'nullable|in:hours,days,weeks',
            'status'         => 'nullable|in:pending,accepted,rejected,withdrawn,submitted,draft',
            'expires_at'     => 'nullable|date|after:now',
        ]);

        $offer->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'تم تحديث العرض بنجاح',
                'offer'   => $offer->fresh(['user', 'serviceRequest']),
            ]);
        }

        return redirect()->back()->with('success', 'تم تحديث العرض بنجاح');
    }

    /**
     * Remove the specified offer.
     */
    public function destroy(Offer $offer)
    {
        $offer->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'تم حذف العرض بنجاح']);
        }

        return redirect()->back()->with('success', 'تم حذف العرض بنجاح');
    }
    
    public function accept(ServiceRequest $serviceRequest, Offer $offer)
    {
        $user = auth()->user() ?? \App\Models\User::first();

        if ($serviceRequest->user_id != $user->id) {
            return response()->json(['error' => 'غير مصرح لك باتخاذ هذا الإجراء'], 403);
        }

        if ($offer->status != "pending") {
            return response()->json(['error' => 'لا يمكن قبول عرض ليس قيد الانتظار'], 400);
        }

        if ($serviceRequest->status != 'open') {
            $msg = 'حالة الطلب لا تسمح بقبول العروض';
            if ($serviceRequest->status == 'assigned') {
                $msg = 'لا يمكن قبول أكثر من عرض واحد للطلب الواحد';
            }
            return response()->json(['error' => $msg], 400);
        }
        if ($offer->request_id !== $serviceRequest->id) {
            return response()->json(['error' => 'العرض غير تابع لهذا الطلب'], 400);
        }
        \DB::transaction(function () use ($serviceRequest, $offer) {
            $serviceRequest->update(['status' => 'assigned']);
            $offer->update(['status' => 'accepted']);
            
            \App\Models\Order::create([
                'request_id' => $serviceRequest->id,
                'offer_id' => $offer->id,
                'client_id' => $serviceRequest->user_id,
                'provider_id' => $offer->user_id,
                'agreed_price' => $offer->proposed_price,
                'currency_code' => $offer->currency_code ?? 'USD',
                // For now, default to service type
                'order_type' => 'service', 
                'status' => 'in_progress',
                'started_at' => now(),
            ]);
        });

        if (request()->expectsJson()) {
            return response()->json(['message' => 'تم قبول العرض بنجاح', 'status' => 'accepted']);
        }

        return redirect()->back()->with('success', 'تم قبول العرض بنجاح');
    }

    public function reject(ServiceRequest $serviceRequest, Offer $offer)
    {
        $user = auth()->user() ?? \App\Models\User::first();

        if ($serviceRequest->user_id != $user->id) {
            return response()->json(['error' => 'غير مصرح لك باتخاذ هذا الإجراء'], 403);
        }

        if ($offer->status != "pending") {
            return response()->json(['error' => 'هذا العرض غير متاح حاليا'], 400);
        }
        
        if ($offer->request_id !== $serviceRequest->id) {
            return response()->json(['error' => 'العرض غير تابع لهذا الطلب'], 400);
        }
        $offer->update(['status' => 'rejected']);

        if (request()->expectsJson()) {
            return response()->json(['message' => 'تم رفض العرض', 'status' => 'rejected']);
        }

        return redirect()->back()->with('success', 'تم رفض العرض');
    }

    public function reset(ServiceRequest $serviceRequest, Offer $offer)
    {
        $user = auth()->user() ?? \App\Models\User::first();

        if ($serviceRequest->user_id != $user->id) {
            return response()->json(['error' => 'غير مصرح لك باتخاذ هذا الإجراء'], 403);
        }

        if (!in_array($offer->status, ['accepted', 'rejected'])) {
            return response()->json(['error' => 'يمكن فقط إعادة تعيين العروض المقبولة أو المرفوضة'], 400);
        }
        if ($offer->request_id !== $serviceRequest->id) {
            return response()->json(['error' => 'العرض غير تابع لهذا الطلب'], 400);
        }
        \DB::transaction(function () use ($serviceRequest, $offer) {
            $serviceRequest->update(['status'=>'open']);
            $offer->update(['status' => 'pending']);
        });

        if (request()->expectsJson()) {
            return response()->json(['message' => 'تم إعادة العرض لقيد الانتظار', 'status' => 'pending']);
        }

        return redirect()->back()->with('success', 'تم إعادة العرض لقيد الانتظار');
    }
}
