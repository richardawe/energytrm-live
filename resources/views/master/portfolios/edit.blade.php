<x-app-layout><x-slot name="title">Edit Portfolio</x-slot>
<div class="mb-3"><a href="{{ route('master.portfolios.index') }}" class="text-muted small text-decoration-none">← Portfolios</a></div>
<div class="card card-etrm" style="max-width:600px;"><div class="card-header">Edit — {{ $portfolio->name }}</div><div class="card-body">
<form method="POST" action="{{ route('master.portfolios.update', $portfolio) }}">@csrf @method('PUT')
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Short Name *</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $portfolio->name) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Long Name</label>
        <input type="text" name="long_name" class="form-control" value="{{ old('long_name', $portfolio->long_name) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Business Unit</label>
        <select name="business_unit_id" class="form-select">
            <option value="">— None —</option>
            @foreach($businessUnits as $bu)
            <option value="{{ $bu->id }}" {{ old('business_unit_id', $portfolio->business_unit_id) == $bu->id ? 'selected' : '' }}>{{ $bu->short_name }} — {{ $bu->long_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Type</label>
        <select name="type" class="form-select">
            <option value="">— None —</option>
            @foreach(['Trading','Hedging','Treasury','Risk Management','Other'] as $t)
            <option value="{{ $t }}" {{ old('type', $portfolio->type) == $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Base Currency</label>
        <select name="currency_id" class="form-select">
            <option value="">— None —</option>
            @foreach($currencies as $c)
            <option value="{{ $c->id }}" {{ old('currency_id', $portfolio->currency_id) == $c->id ? 'selected' : '' }}>{{ $c->code }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Linked Portfolio</label>
        <select name="linked_portfolio_id" class="form-select">
            <option value="">— None —</option>
            @foreach($linkedPortfolios as $lp)
            <option value="{{ $lp->id }}" {{ old('linked_portfolio_id', $portfolio->linked_portfolio_id) == $lp->id ? 'selected' : '' }}>{{ $lp->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Status *</label>
        <select name="status" class="form-select" required>
            @foreach(['Authorized','Auth Pending','Do Not Use'] as $s)
            <option value="{{ $s }}" {{ old('status', $portfolio->status) == $s ? 'selected' : '' }}>{{ $s }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12 d-flex gap-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_restricted" value="1" {{ old('is_restricted', $portfolio->is_restricted) ? 'checked' : '' }}>
            <label class="form-check-label">Restricted Portfolio</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="requires_strategy" value="1" {{ old('requires_strategy', $portfolio->requires_strategy) ? 'checked' : '' }}>
            <label class="form-check-label">Requires Strategy</label>
        </div>
    </div>
</div>
<div class="d-flex gap-2 mt-3">
    <button type="submit" class="btn btn-primary" style="background:var(--etrm-secondary);border-color:var(--etrm-secondary);">Update</button>
    <a href="{{ route('master.portfolios.index') }}" class="btn btn-outline-secondary">Cancel</a>
    <form method="POST" action="{{ route('master.portfolios.destroy', $portfolio) }}" class="ms-auto" onsubmit="return confirm('Delete?')">
        @csrf @method('DELETE')<button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
    </form>
</div>
</form></div></div></x-app-layout>
