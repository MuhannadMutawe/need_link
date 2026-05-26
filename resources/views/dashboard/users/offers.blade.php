@extends('layout.dash')
@section('content')
<div style="padding: 20px; width: 80%; margin: 0 auto; text-align: center;">
    <h2>Create Offer</h2>
    <form action="{{ route('offers.store') }}" method="POST">
        @csrf
        <div>
            <label>Request:</label>
            <select name="request_id" class="request-select" required>
                <option value="">-- Select Request --</option>
            </select>
        </div>
        <div>
            <label>Message:</label>
            <textarea name="message" required></textarea>
        </div>
        <div>
            <label>Proposed Price:</label>
            <input type="number" step="0.01" name="proposed_price" required>
        </div>
        <div>
            <label>Currency Code:</label>
            <input type="text" name="currency_code" maxlength="3" placeholder="e.g. USD">
        </div>
        <div>
            <label>Estimated Time:</label>
            <input type="number" name="estimated_time" min="1" >
        </div>
        <div>
            <label>Time Unit:</label>
            <select name="time_unit">
                <option value="hours">Hours</option>
                <option value="days">Days</option>
                <option value="weeks">Weeks</option>
            </select>
        </div>
        <div>
            <label>Expires At:</label>
            <input type="datetime-local" name="expires_at">
        </div>
        <button type="submit">Create Offer</button>
    </form>

    <hr>

    <h2>My Offers</h2>
    @if(isset($offers) && $offers->count() > 0)
        @foreach($offers as $offer)
            <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                <h3>Offer #{{ $offer->id }}</h3>
                <p>Request: {{ $offer->serviceRequest ? $offer->serviceRequest->title : 'N/A' }}</p>
                <p>User: {{ $offer->user ? $offer->user->first_name . ' ' . $offer->user->last_name : 'N/A' }}</p>
                <p>Message: {{ $offer->message }}</p>
                <p>Proposed Price: {{ $offer->proposed_price }} {{ $offer->currency_code }}</p>
                <p>Estimated Time: {{ $offer->estimated_time }} {{ $offer->time_unit }}</p>
                <p>Expires At: {{ $offer->expires_at }}</p>
                <p>Status: {{ $offer->status }}</p>

                <h4>Update</h4>
                <form action="{{ route('offers.update', $offer->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div>
                        <label>Request:</label>
                        <select name="request_id" class="request-select" data-selected="{{ $offer->request_id }}" required>
                            <option value="">-- Select Request --</option>
                        </select>
                    </div>
                    <div>
                        <label>Message:</label>
                        <textarea name="message" required>{{ $offer->message }}</textarea>
                    </div>
                    <div>
                        <label>Proposed Price:</label>
                        <input type="number" step="0.01" name="proposed_price" value="{{ $offer->proposed_price }}" required>
                    </div>
                    <div>
                        <label>Currency Code:</label>
                        <input type="text" name="currency_code" maxlength="3" value="{{ $offer->currency_code }}" required>
                    </div>
                    <div>
                        <label>Estimated Time:</label>
                        <input type="number" name="estimated_time" min="1" value="{{ $offer->estimated_time }}" required>
                    </div>
                    <div>
                        <label>Time Unit:</label>
                        <select name="time_unit" required>
                            <option value="hours" {{ $offer->time_unit == 'hours' ? 'selected' : '' }}>Hours</option>
                            <option value="days" {{ $offer->time_unit == 'days' ? 'selected' : '' }}>Days</option>
                            <option value="weeks" {{ $offer->time_unit == 'weeks' ? 'selected' : '' }}>Weeks</option>
                        </select>
                    </div>
                    <div>
                        <label>Expires At:</label>
                        <input type="datetime-local" name="expires_at" value="{{ $offer->expires_at ? \Carbon\Carbon::parse($offer->expires_at)->format('Y-m-d\TH:i') : '' }}">
                    </div>
                    <button type="submit">Update</button>
                </form>

                <h4>Delete</h4>
                <form action="{{ route('offers.destroy', $offer->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </div>
        @endforeach
    @else
        <p>No offers found.</p>
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
