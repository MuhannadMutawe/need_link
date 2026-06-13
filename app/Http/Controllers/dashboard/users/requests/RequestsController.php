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

    public function index(Request $request)
    {
        $sortField = $request->query('sort', 'created_at');
        $sortField = in_array($sortField, ['created_at', 'updated_at']) ? $sortField : 'created_at';

        $requests = ServiceRequest::with(['categories'])
            ->withCount('offers')
            ->where('user_id', auth()->id())
            ->orderByDesc($sortField)
            ->paginate(3);

        $categories = \App\Models\Category::all();

        if ($request->expectsJson()) {
            return response()->json($requests);
        }

        return view('dashboard.users.requests', compact('requests', 'sortField', 'categories'));
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
            'image'        => 'nullable|image|max:5120',
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

        $validated['status'] = $request->input('status', 'open');
        if ($validated['status'] === 'draft') { $validated['published_at'] = null; }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('request_images');
        }

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
            'image'        => 'nullable|image|max:5120',
            'pricing_type' => 'sometimes|in:fixed,hourly,negotiable',
            'budget'       => 'nullable|numeric|min:0',
            'currency_code'=> 'nullable|string|size:3',
            'status'       => 'nullable|in:draft,open,assigned,completed,cancelled,closed',
            'expires_at'   => 'nullable|date|after:now',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('request_images');
        }

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
        if ($serviceRequest->user_id !== auth()->id()) {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'غير مصرح'], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        if (in_array($serviceRequest->status, ['completed', 'closed'])) {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'لا يمكن إلغاء طلب مكتمل أو مغلق'], 400);
            }
            return redirect()->back()->with('error', 'لا يمكن إلغاء طلب مكتمل أو مغلق');
        }

        $serviceRequest->update(['status' => 'cancelled']);
        $serviceRequest->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'تم إلغاء الطلب بنجاح']);
        }

        return redirect()->back()->with('success', 'تم إلغاء الطلب بنجاح');
    }
    /**
     * Displays a form to create new request.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();
        return view("dashboard.users.requests_create", compact("categories"));
    }
}
