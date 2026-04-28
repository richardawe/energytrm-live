<x-app-layout><x-slot name="title">New Functional Group</x-slot>
<div class="mb-3">
    <a href="{{ route('master.functional-groups.index') }}" class="text-muted small text-decoration-none">← Functional Groups</a>
</div>
<div class="card card-etrm" style="max-width:500px;"><div class="card-header">New Functional Group</div><div class="card-body">
<form method="POST" action="{{ route('master.functional-groups.store') }}">@csrf
<div class="mb-3">
    <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" maxlength="100" required>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">Description</label>
    <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') }}" maxlength="255">
    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', '1') ? 'checked' : '' }}>
        <label class="form-check-label" for="is_active">Active</label>
    </div>
</div>
<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary" style="background:var(--etrm-secondary);border-color:var(--etrm-secondary);">Save</button>
    <a href="{{ route('master.functional-groups.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>
</form>
</div></div>
</x-app-layout>
