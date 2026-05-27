<?php

namespace App\Http\Controllers\dashboard\users\requests;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RequestsController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }

    public function index()
    {
        $userId = auth()->id() ?? 1; // used coelcing (?? 1) for testing purposes only
        $requests = ServiceRequest::with(['user', 'categories'])
            ->where('user_id', $userId)
            ->latest()
            ->get();

        if (request()->expectsJson()) {
            return response()->json($requests);
        }

        return view('dashboard.users.requests', compact('requests'));
    }

    /**
     * Display the specified request along with its offers.
     */
    public function show(ServiceRequest $serviceRequest)
    {
        $serviceRequest->load(['user', 'categories', 'offers.user']);

        if (request()->expectsJson()) {
            return response()->json($serviceRequest);
        }

        return view('dashboard.users.request_show', compact('serviceRequest'));
    }


    /**
     * Store a newly created request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'categories'   => 'required|array',
            'categories.*' => 'exists:categories,id',
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'pricing_type' => 'required|in:fixed,hourly,negotiable',
            'budget'       => 'nullable|numeric|min:0',
            'currency_code'=> 'nullable|string|size:3',
            'expires_at'   => 'nullable|date|after:now',
            'status'       => 'nullable|in:open,draft',
        ], [
            'categories.required'  => 'الفئات مطلوبة',
            'title.required'       => 'العنوان مطلوب',
            'description.required' => 'الوصف مطلوب',
            'pricing_type.required'=> 'نوع التسعير مطلوب',
        ]);

        $validated['user_id'] = auth()->id();

        $validated['status'] = $request->input('status', 'draft');
        if ($validated['status'] === 'draft') { $validated['published_at'] = null; }

        $serviceRequest = ServiceRequest::create($validated);
        $serviceRequest->categories()->sync($validated['categories']);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'تم إنشاء الطلب بنجاح',
                'request' => $serviceRequest->load(['user', 'categories']),
            ], 201);
        }

        return redirect()->back()->with('success', 'تم إنشاء الطلب بنجاح');
    }

    /**
     * Update the specified request.
     */
    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        $validated = $request->validate([
            'categories'   => 'sometimes|array',
            'categories.*' => 'exists:categories,id',
            'title'        => 'sometimes|string|max:255',
            'description'  => 'sometimes|string',
            'pricing_type' => 'sometimes|in:fixed,hourly,negotiable',
            'budget'       => 'nullable|numeric|min:0',
            'currency_code'=> 'nullable|string|size:3',
            'status'       => 'nullable|in:draft,open,assigned,completed,cancelled,closed',
            'expires_at'   => 'nullable|date|after:now',
        ]);

        $serviceRequest->update($validated);
        if (isset($validated['categories'])) {
            $serviceRequest->categories()->sync($validated['categories']);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'تم تحديث الطلب بنجاح',
                'request' => $serviceRequest->fresh(['user', 'categories']),
            ]);
        }

        return redirect()->back()->with('success', 'تم تحديث الطلب بنجاح');
    }

    /**
     * Remove the specified request (soft delete).
     */
    public function destroy(ServiceRequest $serviceRequest)
    {
        $serviceRequest->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'تم حذف الطلب بنجاح']);
        }

        return redirect()->back()->with('success', 'تم حذف الطلب بنجاح');
    }
}
