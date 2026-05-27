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
            <h3>Create Request</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.requests.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Categories:</label>
                    <div class="categories-container border rounded p-2" style="max-height: 150px; overflow-y: auto; background: #fff;"></div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Title:</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Description:</label>
                    <textarea name="description" class="form-control" rows="3" required></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Pricing Type:</label>
                        <select name="pricing_type" class="form-select" required>
                            <option value="fixed">Fixed</option>
                            <option value="hourly">Hourly</option>
                            <option value="negotiable">Negotiable</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Budget:</label>
                        <input type="number" step="0.01" name="budget" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Currency Code:</label>
                        <input type="text" name="currency_code" maxlength="3" class="form-control" placeholder="e.g. USD">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Expires At:</label>
                        <input type="date" name="expires_at" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3 d-flex align-items-center mt-md-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" value="open" id="statusSwitch" style="transform: scale(1.2);">
                            <label class="form-check-label ms-2" for="statusSwitch">Publish immediately (unchecked = draft)</label>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">Create Request</button>
            </form>
        </div>
    </div>

    <hr>

    <h3 class="mb-4">My Requests</h3>
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
                            <p class="mb-2"><strong>Expires At:</strong> {{ $req->expires_at ?? 'N/A' }}</p>
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
                            
                            <hr>
                            
                            <h5 class="mt-3">Update Request</h5>
                            <form action="{{ route('dashboard.requests.update', $req->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label class="form-label">Categories:</label>
                                    <div class="categories-container border rounded p-2" data-selected="{{ json_encode($req->categories->pluck('id')) }}" style="max-height: 150px; overflow-y: auto; background: #fff;"></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Title:</label>
                                    <input type="text" name="title" class="form-control" value="{{ $req->title }}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Description:</label>
                                    <textarea name="description" class="form-control" rows="3" required>{{ $req->description }}</textarea>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Pricing Type:</label>
                                        <select name="pricing_type" class="form-select" required>
                                            <option value="fixed" {{ $req->pricing_type == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                            <option value="hourly" {{ $req->pricing_type == 'hourly' ? 'selected' : '' }}>Hourly</option>
                                            <option value="negotiable" {{ $req->pricing_type == 'negotiable' ? 'selected' : '' }}>Negotiable</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Budget:</label>
                                        <input type="number" step="0.01" name="budget" class="form-control" value="{{ $req->budget }}" placeholder="Budget">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Currency Code:</label>
                                        <input type="text" name="currency_code" maxlength="3" class="form-control" value="{{ $req->currency_code }}" placeholder="Currency (e.g. USD)">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Expires At:</label>
                                        <input type="date" name="expires_at" class="form-control" value="{{ $req->expires_at ? \Carbon\Carbon::parse($req->expires_at)->format('Y-m-d') : '' }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Status:</label>
                                        <select name="status" class="form-select">
                                            <option value="draft" {{ $req->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="open" {{ $req->status == 'open' ? 'selected' : '' }}>Open</option>
                                            <option value="assigned" {{ $req->status == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                            <option value="completed" {{ $req->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ $req->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            <option value="closed" {{ $req->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-warning">Update</button>
                            </form>
                                    <form action="{{ route('dashboard.requests.destroy', $req->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
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
        <div class="alert alert-info">No requests found.</div>
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

                if (!Array.isArray(selectedVal)) {
                    selectedVal = [selectedVal];
                }
                selectedVal = selectedVal.map(String);

                categories.forEach(category => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'form-check mb-2';

                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = 'categories[]';
                    checkbox.value = category.id;
                    checkbox.className = 'form-check-input';
                    checkbox.id = 'cat_' + category.id + '_' + Math.random().toString(36).substr(2, 9);

                    if (selectedVal.includes(String(category.id))) {
                        checkbox.checked = true;
                    }

                    const label = document.createElement('label');
                    label.className = 'form-check-label';
                    label.htmlFor = checkbox.id;
                    label.textContent = category.name;

                    wrapper.append(checkbox, label);
                    container.appendChild(wrapper);
                });
            });

        } catch (error) {
            console.error('Error fetching categories:', error);
        }
    });
</script>
@endsection