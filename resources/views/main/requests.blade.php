@extends('layout.master')
@section('title')
  NeedLink - تصفح الطلبات
@endsection

@section('content')
<div class="container" style="padding: 20px;">
    <h3 class="mb-4">الطلبات المتاحة</h3>
    
    @if(isset($requests) && $requests->count() > 0)
        <div class="row">
            @foreach($requests as $req)
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">{{ $req->title }} (ID: {{ $req->id }})</h5>
                            <span class="badge bg-{{ $req->status === 'open' ? 'success' : ($req->status === 'draft' ? 'secondary' : 'info') }}">{{ ucfirst($req->status) }}</span>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Description:</strong> {{ $req->description }}</p>
                            <p class="mb-2"><strong>Pricing:</strong> {{ ucfirst($req->pricing_type) }} | <strong>Budget:</strong> {{ $req->budget ?? 'N/A' }} {{ $req->currency_code }}</p>
                            <p class="mb-2"><strong>Expires At:</strong> {{ $req->expires_at ? \Carbon\Carbon::parse($req->expires_at)->format('Y-m-d') : 'N/A' }}</p>
                            <p class="mb-2"><strong>Categories:</strong> 
                                @if($req->categories->count() > 0)
                                    @foreach($req->categories as $category)
                                        <span class="badge bg-info text-dark">{{ $category->name }}</span>
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </p>
                            <div class="mt-3">
                                <a href="{{ route('dashboard.requests.show', $req->id) }}" class="btn btn-info btn-sm text-white">View Details & Offers</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">لا توجد طلبات متاحة حالياً.</div>
    @endif
    
    <div class="mt-4">
        {{ $requests->links() }}
    </div>
</div>
@endsection
