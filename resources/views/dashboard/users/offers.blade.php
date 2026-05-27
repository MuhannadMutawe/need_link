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
            <h3>Create Offer</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.offers.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Request:</label>
                    <select name="request_id" class="form-select request-select" required>
                        <option value="">-- Select Request --</option>
                    </select>
                </div>
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
                <button type="submit" class="btn btn-primary">Create Offer</button>
            </form>
        </div>
    </div>

    <hr>

    <h3 class="mb-4">My Offers</h3>
    @if(isset($offers) && $offers->count() > 0)
        <div class="row">
            @foreach($offers as $offer)
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Offer #{{ $offer->id }}</h5>
                            <span class="badge bg-{{ $offer->status === 'accepted' ? 'success' : ($offer->status === 'rejected' ? 'danger' : 'secondary') }}">{{ ucfirst($offer->status) }}</span>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Request:</strong> {{ $offer->serviceRequest ? $offer->serviceRequest->title : 'N/A' }}</p>
                            <p class="mb-2"><strong>User:</strong> {{ $offer->user ? $offer->user->name : 'N/A' }}</p>
                            <p class="mb-2"><strong>Message:</strong> {{ $offer->message }}</p>
                            <p class="mb-2"><strong>Proposed Price:</strong> {{ $offer->proposed_price }} {{ $offer->currency_code }}</p>
                            <p class="mb-2"><strong>Estimated Time:</strong> {{ $offer->estimated_time }} {{ $offer->time_unit }}</p>
                            <p class="mb-2"><strong>Expires At:</strong> {{ $offer->expires_at }}</p>

                            <hr>

                            <h5 class="mt-3">Update Offer</h5>
                            <form action="{{ route('dashboard.offers.update', $offer->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label">Request:</label>
                                    <select name="request_id" class="form-select request-select" data-selected="{{ $offer->request_id }}" required>
                                        <option value="">-- Select Request --</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Message:</label>
                                    <textarea name="message" class="form-control" rows="3" required>{{ $offer->message }}</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Proposed Price:</label>
                                        <input type="number" step="0.01" name="proposed_price" class="form-control" value="{{ $offer->proposed_price }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Currency Code:</label>
                                        <input type="text" name="currency_code" maxlength="3" class="form-control" value="{{ $offer->currency_code }}" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Estimated Time:</label>
                                        <input type="number" name="estimated_time" min="1" class="form-control" value="{{ $offer->estimated_time }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Time Unit:</label>
                                        <select name="time_unit" class="form-select" required>
                                            <option value="hours" {{ $offer->time_unit == 'hours' ? 'selected' : '' }}>Hours</option>
                                            <option value="days" {{ $offer->time_unit == 'days' ? 'selected' : '' }}>Days</option>
                                            <option value="weeks" {{ $offer->time_unit == 'weeks' ? 'selected' : '' }}>Weeks</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Expires At:</label>
                                    <input type="datetime-local" name="expires_at" class="form-control" value="{{ $offer->expires_at ? \Carbon\Carbon::parse($offer->expires_at)->format('Y-m-d\TH:i') : '' }}">
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-warning">Update</button>
                            </form>
                                    <form action="{{ route('dashboard.offers.destroy', $offer->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">No offers found.</div>
    @endif
</div>

<script>
    document.addEventListener("DOMContentLoaded", async () => {
        try {
            const response = await fetch(
                '{{ route("dashboard.requests.index") }}',
                {
                    headers: {
                        Accept: 'application/json'
                    }
                }
            );

            const requests = await response.json();

            const selects = document.querySelectorAll('.request-select');

            selects.forEach(select => {
                const selectedVal = select.dataset.selected;

                requests.forEach(req => {
                    const option = document.createElement('option');

                    option.value = req.id;
                    option.textContent = req.title;

                    if (
                        selectedVal &&
                        selectedVal == req.id
                    ) {
                        option.selected = true;
                    }

                    select.appendChild(option);
                });
            });

        } catch (error) {
            console.error(
                'Error fetching requests:',
                error
            );
        }
    });
</script>
@endsection
