<x-app-layout>
<x-slot name="title">Edit Index</x-slot>
<div class="mb-3"><a href="{{ route('master.indices.show', $index) }}" class="text-muted small text-decoration-none">← {{ $index->index_name }}</a></div>

<form method="POST" action="{{ route('master.indices.update', $index) }}">@csrf @method('PUT')

{{-- Core Fields --}}
<div class="card card-etrm mb-3" style="max-width:760px;">
    <div class="card-header">Edit Index — {{ $index->index_name }}</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-semibold">Index Name *</label>
                <input type="text" name="index_name" class="form-control" value="{{ old('index_name', $index->index_name) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Label</label>
                <input type="text" name="label" class="form-control" value="{{ old('label', $index->label) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Market</label>
                <input type="text" name="market" class="form-control" value="{{ old('market', $index->market) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Index Group</label>
                <input type="text" name="index_group" class="form-control" value="{{ old('index_group', $index->index_group) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Index Sub-Group</label>
                <input type="text" name="index_subgroup" class="form-control" value="{{ old('index_subgroup', $index->index_subgroup) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Format *</label>
                <select name="format" class="form-select" required>
                    @foreach(['Monthly','Daily','Quarterly','Annual'] as $f)
                    <option value="{{ $f }}" {{ old('format', $index->format) == $f ? 'selected' : '' }}>{{ $f }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Class</label>
                <input type="text" name="class" class="form-control" value="{{ old('class', $index->class) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Index Type</label>
                <select name="index_type" class="form-select">
                    <option value="">— None —</option>
                    @foreach(['Standard','Composite'] as $t)
                    <option value="{{ $t }}" {{ old('index_type', $index->index_type) == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Base Currency</label>
                <select name="base_currency_id" class="form-select">
                    <option value="">— None —</option>
                    @foreach($currencies as $c)
                    <option value="{{ $c->id }}" {{ old('base_currency_id', $index->base_currency_id) == $c->id ? 'selected' : '' }}>{{ $c->code }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">UOM</label>
                <select name="uom_id" class="form-select">
                    <option value="">— None —</option>
                    @foreach($uoms as $u)
                    <option value="{{ $u->id }}" {{ old('uom_id', $index->uom_id) == $u->id ? 'selected' : '' }}>{{ $u->code }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Index Status *</label>
                <select name="status" class="form-select" required>
                    @foreach(['Custom','Official','Template'] as $s)
                    <option value="{{ $s }}" {{ old('status', $index->status) == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Record Status *</label>
                <select name="rec_status" class="form-select" required>
                    @foreach(['Authorized','Auth Pending','Do Not Use'] as $s)
                    <option value="{{ $s }}" {{ old('rec_status', $index->rec_status) == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Version Status</label>
                <select name="version_status" class="form-select">
                    @foreach(['Pending','Authorized','Superseded'] as $s)
                    <option value="{{ $s }}" {{ old('version_status', $index->version_status ?? 'Pending') == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

{{-- Curve Configuration --}}
<div class="card card-etrm mb-3" style="max-width:760px;">
    <div class="card-header">Curve Configuration</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Delivery Unit</label>
                <input type="text" name="delivery_unit" class="form-control" value="{{ old('delivery_unit', $index->delivery_unit) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Date Sequence</label>
                <input type="text" name="date_sequence" class="form-control" value="{{ old('date_sequence', $index->date_sequence) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Payment Convention</label>
                <input type="text" name="payment_convention" class="form-control" value="{{ old('payment_convention', $index->payment_convention) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Coverage End Date</label>
                <input type="date" name="coverage_end_date" class="form-control"
                       value="{{ old('coverage_end_date', $index->coverage_end_date?->format('Y-m-d')) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Interpolation</label>
                <select name="interpolation" class="form-select">
                    <option value="">— None —</option>
                    @foreach(['Back-Step','Front-Step','Linear'] as $i)
                    <option value="{{ $i }}" {{ old('interpolation', $index->interpolation) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Projection Method</label>
                <input type="text" name="projection_method" class="form-control" value="{{ old('projection_method', $index->projection_method) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Reference Source</label>
                <input type="text" name="reference_source" class="form-control" value="{{ old('reference_source', $index->reference_source) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Day Start Time</label>
                <input type="time" name="day_start_time" class="form-control" value="{{ old('day_start_time', $index->day_start_time) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Holiday Schedule</label>
                <input type="text" name="holiday_schedule" class="form-control" value="{{ old('holiday_schedule', $index->holiday_schedule) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Discount Index</label>
                <select name="discount_index_id" class="form-select">
                    <option value="">— None —</option>
                    @foreach($allIndices as $idx)
                    <option value="{{ $idx->id }}" {{ old('discount_index_id', $index->discount_index_id) == $idx->id ? 'selected' : '' }}>{{ $idx->index_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="inheritance" value="1" id="inheritance"
                           {{ old('inheritance', $index->inheritance) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="inheritance">Inheritance</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mb-4" style="max-width:760px;">
    <button type="submit" class="btn btn-primary" style="background:var(--etrm-secondary);border-color:var(--etrm-secondary);">Update</button>
    <a href="{{ route('master.indices.show', $index) }}" class="btn btn-outline-secondary">Cancel</a>
    <form method="POST" action="{{ route('master.indices.destroy', $index) }}" class="ms-auto"
          onsubmit="return confirm('Delete this index and all its price data?')">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
    </form>
</div>
</form>
</x-app-layout>
