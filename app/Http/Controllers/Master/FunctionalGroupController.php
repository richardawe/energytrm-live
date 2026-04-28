<?php
namespace App\Http\Controllers\Master;
use App\Http\Controllers\Controller;
use App\Models\FunctionalGroup;
use Illuminate\Http\Request;

class FunctionalGroupController extends Controller
{
    public function index()
    {
        $groups = FunctionalGroup::withCount('users')->orderBy('name')->paginate(20);
        return view('master.functional-groups.index', compact('groups'));
    }

    public function create()
    {
        return view('master.functional-groups.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100|unique:functional_groups,name',
            'description' => 'nullable|string|max:255',
            'is_active'   => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        FunctionalGroup::create($data);
        return redirect()->route('master.functional-groups.index')->with('success', 'Functional group created.');
    }

    public function edit(FunctionalGroup $functionalGroup)
    {
        return view('master.functional-groups.edit', compact('functionalGroup'));
    }

    public function update(Request $request, FunctionalGroup $functionalGroup)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100', \Illuminate\Validation\Rule::unique('functional_groups', 'name')->ignore($functionalGroup->id)],
            'description' => 'nullable|string|max:255',
            'is_active'   => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $functionalGroup->update($data);
        return redirect()->route('master.functional-groups.index')->with('success', 'Functional group updated.');
    }

    public function destroy(FunctionalGroup $functionalGroup)
    {
        $functionalGroup->delete();
        return redirect()->route('master.functional-groups.index')->with('success', 'Functional group deleted.');
    }
}
