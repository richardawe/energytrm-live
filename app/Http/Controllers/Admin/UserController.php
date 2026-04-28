<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FunctionalGroup;
use App\Models\IndexDefinition;
use App\Models\Party;
use App\Models\Portfolio;
use App\Models\SecurityGroup;
use App\Models\TradingLocation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::withCount(['businessUnits', 'portfolios', 'securityGroups'])
            ->orderBy('name')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $parties          = Party::businessUnits()->authorized()->orderBy('long_name')->get();
        $portfolios       = Portfolio::orderBy('name')->get();
        $securityGroups   = SecurityGroup::orderBy('name')->get();
        $tradingLocations = TradingLocation::where('is_active', true)->orderBy('name')->get();
        $legalEntities    = Party::where('party_type', 'LE')->orderBy('long_name')->get();
        $securedIndices   = IndexDefinition::where('status', 'Authorized')->orderBy('index_name')->get();
        $functionalGroups = FunctionalGroup::where('is_active', true)->orderBy('name')->get();

        return view('admin.users.create', compact(
            'parties', 'portfolios', 'securityGroups', 'tradingLocations',
            'legalEntities', 'securedIndices', 'functionalGroups'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'                   => ['required', 'string', 'max:255'],
            'first_name'             => ['sometimes', 'nullable', 'string', 'max:100'],
            'last_name'              => ['sometimes', 'nullable', 'string', 'max:100'],
            'email'                  => ['required', 'email', 'max:255', 'unique:users,email'],
            'role'                   => ['required', Rule::in(['admin', 'trader', 'back_office'])],
            'password'               => ['required', 'string', 'min:8', 'confirmed'],
            'user_type'              => ['sometimes', Rule::in(['Internal', 'External', 'Licensed'])],
            'license_type'           => ['sometimes', 'nullable', Rule::in(['Full Access', 'Server', 'Read Only'])],
            'short_ref_name'         => ['sometimes', 'nullable', 'string', 'max:32'],
            'short_alias_name'       => ['sometimes', 'nullable', 'string', 'max:50'],
            'employee_id'            => ['sometimes', 'nullable', 'string', 'max:50'],
            'title'                  => ['sometimes', 'nullable', 'string', 'max:100'],
            'phone'                  => ['sometimes', 'nullable', 'string', 'max:50'],
            'address'                => ['sometimes', 'nullable', 'string', 'max:255'],
            'city'                   => ['sometimes', 'nullable', 'string', 'max:100'],
            'state'                  => ['sometimes', 'nullable', 'string', 'max:100'],
            'country'                => ['sometimes', 'nullable', 'string', 'max:100'],
            'password_never_expires' => ['boolean'],
            'status'                 => ['sometimes', Rule::in(['Authorized', 'Auth Pending', 'Do Not Use'])],
            'business_units'         => ['sometimes', 'array'],
            'business_units.*'       => ['exists:parties,id'],
            'portfolios'             => ['sometimes', 'array'],
            'portfolios.*'           => ['exists:portfolios,id'],
            'security_groups'        => ['sometimes', 'array'],
            'security_groups.*'      => ['exists:security_groups,id'],
            'trading_locations'      => ['sometimes', 'array'],
            'trading_locations.*'    => ['exists:trading_locations,id'],
            'legal_entities'         => ['sometimes', 'array'],
            'legal_entities.*'       => ['exists:parties,id'],
            'secured_indices'        => ['sometimes', 'array'],
            'secured_indices.*'      => ['exists:index_definitions,id'],
            'functional_groups'      => ['sometimes', 'array'],
            'functional_groups.*'    => ['exists:functional_groups,id'],
        ]);

        $data['password']               = Hash::make($data['password']);
        $data['password_never_expires'] = $request->boolean('password_never_expires');

        $user = User::create($data);

        $user->businessUnits()->sync($request->input('business_units', []));
        $user->portfolios()->sync($request->input('portfolios', []));
        $user->securityGroups()->sync($request->input('security_groups', []));
        $user->tradingLocations()->sync($request->input('trading_locations', []));
        $user->legalEntities()->sync($request->input('legal_entities', []));
        $user->securedIndices()->sync($request->input('secured_indices', []));
        $user->functionalGroups()->sync($request->input('functional_groups', []));

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user): View
    {
        $user->load(['businessUnits', 'portfolios', 'securityGroups', 'tradingLocations', 'legalEntities', 'securedIndices', 'functionalGroups']);

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $user->load(['businessUnits', 'portfolios', 'securityGroups', 'tradingLocations', 'legalEntities', 'securedIndices', 'functionalGroups']);

        $parties          = Party::businessUnits()->authorized()->orderBy('long_name')->get();
        $portfolios       = Portfolio::orderBy('name')->get();
        $securityGroups   = SecurityGroup::orderBy('name')->get();
        $tradingLocations = TradingLocation::where('is_active', true)->orderBy('name')->get();
        $legalEntities    = Party::where('party_type', 'LE')->orderBy('long_name')->get();
        $securedIndices   = IndexDefinition::where('status', 'Authorized')->orderBy('index_name')->get();
        $functionalGroups = FunctionalGroup::where('is_active', true)->orderBy('name')->get();

        $assignedBUs        = $user->businessUnits->pluck('id')->toArray();
        $assignedPortfolios = $user->portfolios->pluck('id')->toArray();
        $assignedSGs        = $user->securityGroups->pluck('id')->toArray();
        $assignedLocations  = $user->tradingLocations->pluck('id')->toArray();
        $assignedLEs        = $user->legalEntities->pluck('id')->toArray();
        $assignedIndices    = $user->securedIndices->pluck('id')->toArray();
        $assignedFGs        = $user->functionalGroups->pluck('id')->toArray();

        return view('admin.users.edit', compact(
            'user',
            'parties', 'portfolios', 'securityGroups', 'tradingLocations',
            'legalEntities', 'securedIndices', 'functionalGroups',
            'assignedBUs', 'assignedPortfolios', 'assignedSGs', 'assignedLocations',
            'assignedLEs', 'assignedIndices', 'assignedFGs'
        ));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name'                   => ['required', 'string', 'max:255'],
            'first_name'             => ['sometimes', 'nullable', 'string', 'max:100'],
            'last_name'              => ['sometimes', 'nullable', 'string', 'max:100'],
            'email'                  => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role'                   => ['required', Rule::in(['admin', 'trader', 'back_office'])],
            'user_type'              => ['sometimes', Rule::in(['Internal', 'External', 'Licensed'])],
            'license_type'           => ['sometimes', 'nullable', Rule::in(['Full Access', 'Server', 'Read Only'])],
            'short_ref_name'         => ['sometimes', 'nullable', 'string', 'max:32'],
            'short_alias_name'       => ['sometimes', 'nullable', 'string', 'max:50'],
            'employee_id'            => ['sometimes', 'nullable', 'string', 'max:50'],
            'title'                  => ['sometimes', 'nullable', 'string', 'max:100'],
            'phone'                  => ['sometimes', 'nullable', 'string', 'max:50'],
            'address'                => ['sometimes', 'nullable', 'string', 'max:255'],
            'city'                   => ['sometimes', 'nullable', 'string', 'max:100'],
            'state'                  => ['sometimes', 'nullable', 'string', 'max:100'],
            'country'                => ['sometimes', 'nullable', 'string', 'max:100'],
            'password_never_expires' => ['boolean'],
            'status'                 => ['sometimes', Rule::in(['Authorized', 'Auth Pending', 'Do Not Use'])],
            'business_units'         => ['sometimes', 'array'],
            'business_units.*'       => ['exists:parties,id'],
            'portfolios'             => ['sometimes', 'array'],
            'portfolios.*'           => ['exists:portfolios,id'],
            'security_groups'        => ['sometimes', 'array'],
            'security_groups.*'      => ['exists:security_groups,id'],
            'trading_locations'      => ['sometimes', 'array'],
            'trading_locations.*'    => ['exists:trading_locations,id'],
            'legal_entities'         => ['sometimes', 'array'],
            'legal_entities.*'       => ['exists:parties,id'],
            'secured_indices'        => ['sometimes', 'array'],
            'secured_indices.*'      => ['exists:index_definitions,id'],
            'functional_groups'      => ['sometimes', 'array'],
            'functional_groups.*'    => ['exists:functional_groups,id'],
        ]);

        $data['password_never_expires'] = $request->boolean('password_never_expires');

        $user->update($data);

        if ($request->filled('password')) {
            $request->validate(['password' => ['string', 'min:8', 'confirmed']]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->businessUnits()->sync($request->input('business_units', []));
        $user->portfolios()->sync($request->input('portfolios', []));
        $user->securityGroups()->sync($request->input('security_groups', []));
        $user->tradingLocations()->sync($request->input('trading_locations', []));
        $user->legalEntities()->sync($request->input('legal_entities', []));
        $user->securedIndices()->sync($request->input('secured_indices', []));
        $user->functionalGroups()->sync($request->input('functional_groups', []));

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted.');
    }
}
