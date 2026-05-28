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
            'request_id'     => 'required|exists:requests,id',
            'message'        => 'sometimes|string',
            'proposed_price' => 'sometimes|numeric|min:0',
            'currency_code'  => 'nullable|string|size:3',
            'estimated_time' => 'nullable|integer|min:1',
            'time_unit'      => 'nullable|in:hours,days,weeks',
            'status'         => 'nullable|in:pending,accepted,rejected,withdrawn',
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
}
