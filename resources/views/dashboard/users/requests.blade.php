@extends('layout.dash')
@section('content')
<div style="padding: 20px; width: 80%; margin: 0 auto; text-align: center;">
<h2>Create Request</h2>
        <form action="{{ route('dashboard.requests.store') }}" method="POST">
            @csrf
            <div>
                <label>Categories:</label>
                <div class="categories-container" style="max-height: 150px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; text-align: left; background: #fff;">
                </div>
            </div>
            <div>
                <label>Title:</label>
                <input type="text" name="title" required>
            </div>
            <div>
                <label>Description:</label>
                <textarea name="description" required></textarea>
            </div>
            <div>
                <label>Pricing Type:</label>
                <select name="pricing_type" required>
                    <option value="fixed">Fixed</option>
                    <option value="hourly">Hourly</option>
                    <option value="negotiable">Negotiable</option>
                </select>
            </div>
            <div>
                <label>Budget:</label>
                <input type="number" step="0.01" name="budget">
            </div>
            <div>
                <label>Currency Code:</label>
                <input type="text" name="currency_code" maxlength="3" placeholder="e.g. USD">
            </div>
            <div>
                <label>Expires At:</label>
                <input type="date" name="expires_at">
            </div>
            <div style="display:flex; align-items:center; justify-content:center; gap:8px; margin: 15px 0;">
                <label style="margin:0;">
                    Publish immediately (unchecked = draft)
                </label>

                <input
                    type="checkbox"
                    name="status"
                    value="open"
                    style="transform:scale(1.2); margin:0;"
                >
            </div>
            <button type="submit">Create Request</button>
        </form>

        <hr>

        <h2>My Requests</h2>
        @if(isset($requests) && $requests->count() > 0)
            @foreach($requests as $req)
                <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                    <h3>{{ $req->title }} (ID: {{ $req->id }})</h3>
                    <p>{{ $req->description }}</p>
                    <p>Pricing: {{ $req->pricing_type }} | Budget: {{ $req->budget }} {{ $req->currency_code }}</p>
                    <p>Expires At: {{ $req->expires_at }}</p>
                    <p>Status: {{ $req->status }}</p>
                    <p>Categories: {{ $req->categories->pluck('name')->implode(', ') ?: 'N/A' }}</p>

                    <h4>Update</h4>
                    <form action="{{ route('dashboard.requests.update', $req->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div>
                            <label>Categories:</label>
                            <div class="categories-container" data-selected="{{ json_encode($req->categories->pluck('id')) }}" style="max-height: 150px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; text-align: left; background: #fff;">
                            </div>
                        </div>
                        <input type="text" name="title" value="{{ $req->title }}" required>
                        <textarea name="description" required>{{ $req->description }}</textarea>
                        <select name="pricing_type" required>
                            <option value="fixed" {{ $req->pricing_type == 'fixed' ? 'selected' : '' }}>Fixed</option>
                            <option value="hourly" {{ $req->pricing_type == 'hourly' ? 'selected' : '' }}>Hourly</option>
                            <option value="negotiable" {{ $req->pricing_type == 'negotiable' ? 'selected' : '' }}>Negotiable</option>
                        </select>
                        <input type="number" step="0.01" name="budget" value="{{ $req->budget }}" placeholder="Budget">
                        <input type="text" name="currency_code" maxlength="3" value="{{ $req->currency_code }}" placeholder="Currency (e.g. USD)">
                        <input type="date" name="expires_at" value="{{ $req->expires_at ? \Carbon\Carbon::parse($req->expires_at)->format('Y-m-d') : '' }}">
                        <select name="status">
                            <option value="draft" {{ $req->status == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="open" {{ $req->status == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="assigned" {{ $req->status == 'assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="completed" {{ $req->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $req->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="closed" {{ $req->status == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                        <button type="submit">Update</button>
                    </form>

                    <h4>Delete</h4>
                    <form action="{{ route('dashboard.requests.destroy', $req->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </div>
            @endforeach
        @else
            <p>No requests found.</p>
        @endif
    </div>

<script>
    document.addEventListener("DOMContentLoaded", async () => {
        try {
            const response = await fetch(
                '{{ route("dashboard.categories.index") }}',
                {
                    headers: {
                        Accept: 'application/json'
                    }
                }
            );

            const categories = await response.json();

            const containers = document.querySelectorAll('.categories-container');

            containers.forEach(container => {
                let selectedVal = container.dataset.selected;
                if (selectedVal) {
                    try {
                        selectedVal = JSON.parse(selectedVal);
                    } catch(e) {
                        selectedVal = [selectedVal];
                    }
                } else {
                    selectedVal = [];
                }

                // Ensure it's an array of strings for easier comparison
                if (!Array.isArray(selectedVal)) {
                    selectedVal = [selectedVal];
                }
                selectedVal = selectedVal.map(String);

                categories.forEach(category => {
    const label = document.createElement('label');
    label.style.display = 'flex';
    label.style.alignItems = 'center';
    label.style.gap = '8px';
    label.style.marginBottom = '8px';
    label.style.cursor = 'pointer';

    const checkbox = document.createElement('input');
    checkbox.type = 'checkbox';
    checkbox.name = 'categories[]';
    checkbox.value = category.id;

    if (selectedVal.includes(String(category.id))) {
        checkbox.checked = true;
    }

    const text = document.createElement('span');
    text.textContent = category.name;

    label.append(checkbox, text);
    container.appendChild(label);
});
            });

        } catch (error) {
            console.error(
                'Error fetching categories:',
                error
            );
        }
    });
</script>
@endsection