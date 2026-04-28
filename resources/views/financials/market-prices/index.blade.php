<x-app-layout>
    <x-slot name="title">Market Prices</x-slot>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <a href="{{ route('financials.dashboard') }}" class="text-muted small text-decoration-none">Financials</a>
            <span class="text-muted small"> / </span>
            <span class="small fw-semibold">Market Prices</span>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    {{-- Quick price entry --}}
    <div class="card card-etrm mb-3" style="max-width:480px;">
        <div class="card-header">Add / Update Price</div>
        <div class="card-body">
            <form method="POST" id="quick-price-form" action="">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Index <span class="text-danger">*</span></label>
                        <select name="_index_id" id="quick-index-select" class="form-select" required
                                onchange="document.getElementById('quick-price-form').action='/financials/market-prices/'+this.value">
                            <option value="">— Select Index —</option>
                            @foreach($indices as $idx)
                            <option value="{{ $idx->id }}">{{ $idx->index_name }}@if($idx->baseCurrency) ({{ $idx->baseCurrency->code }})@endif</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Date <span class="text-danger">*</span></label>
                        <input type="date" name="price_date" class="form-control @error('price_date') is-invalid @enderror"
                               value="{{ old('price_date', date('Y-m-d')) }}" required>
                        @error('price_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Price <span class="text-danger">*</span></label>
                        <input type="number" name="price" step="0.000001" min="0"
                               class="form-control @error('price') is-invalid @enderror"
                               value="{{ old('price') }}" placeholder="0.000000" required>
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary"
                            style="background:var(--etrm-secondary);border-color:var(--etrm-secondary);">Save Price</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-etrm">
        <div class="card-body p-0">
            <table class="table table-etrm table-hover mb-0" style="font-size:.875rem;">
                <thead>
                    <tr>
                        <th>Index</th>
                        <th>Market</th>
                        <th>Class</th>
                        <th>Currency</th>
                        <th class="text-end">Latest Price</th>
                        <th>As of Date</th>
                        <th class="text-center">Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($indices as $idx)
                    <tr>
                        <td class="fw-semibold">{{ $idx->index_name }}</td>
                        <td>{{ $idx->market }}</td>
                        <td>{{ $idx->class }}</td>
                        <td>{{ $idx->baseCurrency->code }}</td>
                        <td class="text-end fw-semibold">
                            {{ $idx->latestPrice ? number_format($idx->latestPrice->price, 4) : '—' }}
                        </td>
                        <td>{{ $idx->latestPrice?->price_date?->format('d-M-Y') ?? '—' }}</td>
                        <td class="text-center">
                            <span class="badge badge-authorized">{{ $idx->status }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('financials.market-prices.show', $idx) }}"
                               class="btn btn-outline-secondary btn-xs py-0 px-2" style="font-size:.75rem;">
                                Enter Price
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No indices found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
