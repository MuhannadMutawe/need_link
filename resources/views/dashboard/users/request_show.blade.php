@extends('layout.dash')
@section('content')
<div class="container" style="padding: 20px;">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <h3>Request: {{ $serviceRequest->title }}</h3>
        </div>
        <div class="card-body">
            <p><strong>Description:</strong> {{ $serviceRequest->description }}</p>
            <p><strong>Pricing Type:</strong> {{ $serviceRequest->pricing_type }}</p>
            <p><strong>Budget:</strong> {{ $serviceRequest->budget ?? 'N/A' }} {{ $serviceRequest->currency_code }}</p>
            <p><strong>Status:</strong> <span class="badge bg-{{ $serviceRequest->status === 'open' ? 'success' : 'secondary' }}">{{ ucfirst($serviceRequest->status) }}</span></p>
            <p><strong>Categories:</strong> 
                @foreach($serviceRequest->categories as $category)
                    <span class="badge bg-info text-dark">{{ $category->name }}</span>
                @endforeach
            </p>
        </div>
    </div>

    <hr>

    <h4>Add Your Offer</h4>
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('dashboard.offers.store') }}" method="POST">
                @csrf
                <input type="hidden" name="request_id" value="{{ $serviceRequest->id }}">
                
                <div class="mb-3">
                    <label class="form-label">Message:</label>
                    <textarea name="message" class="form-control" rows="3" required></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Proposed Price:</label>
                        <input type="number" step="0.01" name="proposed_price" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Currency Code:</label>
                        <input type="text" name="currency_code" maxlength="3" class="form-control" placeholder="e.g. USD">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Estimated Time:</label>
                        <input type="number" name="estimated_time" min="1" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Time Unit:</label>
                        <select name="time_unit" class="form-select">
                            <option value="hours">Hours</option>
                            <option value="days">Days</option>
                            <option value="weeks">Weeks</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Expires At (optional):</label>
                    <input type="datetime-local" name="expires_at" class="form-control">
                </div>
                
                <button type="submit" class="btn btn-primary">Submit Offer</button>
            </form>
        </div>
    </div>

    <h4>Offers ({{ $serviceRequest->offers->count() }})</h4>
    @if($serviceRequest->offers->count() > 0)
        <div class="row">
            @foreach($serviceRequest->offers as $offer)
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Offer from: {{ $offer->user->name ?? 'Unknown' }}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Price: {{ $offer->proposed_price }} {{ $offer->currency_code }}</h6>
                        <p class="card-text">{{ $offer->message }}</p>
                        <p class="card-text"><small class="text-muted">Estimated Time: {{ $offer->estimated_time }} {{ $offer->time_unit }}</small></p>
                        <p class="card-text"><small class="text-muted">Status: {{ ucfirst($offer->status) }}</small></p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">No offers have been submitted for this request yet.</div>
    @endif
</div>
@endsection
