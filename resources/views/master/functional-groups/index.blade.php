<x-app-layout><x-slot name="title">Functional Groups</x-slot>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <a href="{{ route('master.dashboard') }}" class="text-muted small text-decoration-none">Master Data</a>
        <span class="text-muted small"> / </span>
        <span class="small fw-semibold">Functional Groups</span>
    </div>
    <a href="{{ route('master.functional-groups.create') }}" class="btn btn-primary btn-sm"
       style="background:var(--etrm-secondary);border-color:var(--etrm-secondary);">+ New Group</a>
</div>

@if(session('success'))
<div class="alert alert-success py-2">{{ session('success') }}</div>
@endif

<div class="card card-etrm">
    <div class="card-body p-0">
        <table class="table table-etrm table-hover mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th class="text-center">Users</th>
                    <th class="text-center">Active</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($groups as $group)
                <tr>
                    <td class="fw-semibold">{{ $group->name }}</td>
                    <td class="text-muted">{{ $group->description ?? '—' }}</td>
                    <td class="text-center">{{ $group->users_count }}</td>
                    <td class="text-center">
                        @if($group->is_active)
                            <span class="badge bg-success">Yes</span>
                        @else
                            <span class="badge bg-secondary">No</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('master.functional-groups.edit', $group) }}"
                           class="btn btn-outline-secondary btn-xs py-0 px-2" style="font-size:.75rem;">Edit</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">No functional groups defined yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($groups->hasPages())
    <div class="card-footer py-2">{{ $groups->links() }}</div>
    @endif
</div>
</x-app-layout>
