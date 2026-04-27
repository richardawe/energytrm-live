<?php
namespace App\Http\Controllers\Master;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\IndexDefinition;
use App\Models\Uom;
use Illuminate\Http\Request;

class IndexDefinitionController extends Controller
{
    public function index()
    {
        return view('master.indices.index', [
            'indices' => IndexDefinition::with(['baseCurrency','uom','latestPrice'])->orderBy('index_name')->paginate(25),
        ]);
    }

    public function create()
    {
        return view('master.indices.create', [
            'currencies' => Currency::orderBy('code')->get(),
            'uoms'       => Uom::where('is_active', true)->orderBy('code')->get(),
            'allIndices' => IndexDefinition::orderBy('index_name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'index_name'         => 'required|string|max:100',
            'market'             => 'nullable|string|max:100',
            'index_group'        => 'nullable|string|max:100',
            'index_subgroup'     => 'nullable|string|max:100',
            'label'              => 'nullable|string|max:100',
            'format'             => 'required|in:Daily,Monthly,Quarterly,Annual',
            'class'              => 'nullable|string|max:50',
            'delivery_unit'      => 'nullable|string|max:50',
            'date_sequence'      => 'nullable|string|max:50',
            'payment_convention' => 'nullable|string|max:50',
            'coverage_end_date'  => 'nullable|date',
            'interpolation'      => 'nullable|in:Back-Step,Front-Step,Linear',
            'inheritance'        => 'boolean',
            'discount_index_id'  => 'nullable|exists:index_definitions,id',
            'reference_source'   => 'nullable|string|max:100',
            'projection_method'  => 'nullable|string|max:100',
            'day_start_time'     => 'nullable|date_format:H:i',
            'holiday_schedule'   => 'nullable|string|max:100',
            'index_type'         => 'nullable|in:Standard,Composite',
            'version_status'     => 'nullable|in:Pending,Authorized,Superseded',
            'base_currency_id'   => 'nullable|exists:currencies,id',
            'uom_id'             => 'nullable|exists:uoms,id',
            'status'             => 'required|in:Custom,Official,Template',
            'rec_status'         => 'required|in:Auth Pending,Authorized,Do Not Use',
        ]);
        $data['inheritance'] = $request->boolean('inheritance');
        IndexDefinition::create($data);
        return redirect()->route('master.indices.index')->with('success', 'Index created.');
    }

    public function show(IndexDefinition $index)
    {
        return view('master.indices.show', [
            'index' => $index->load(['baseCurrency','uom','gridPoints','discountIndex']),
        ]);
    }

    public function edit(IndexDefinition $index)
    {
        return view('master.indices.edit', [
            'index'      => $index,
            'currencies' => Currency::orderBy('code')->get(),
            'uoms'       => Uom::where('is_active', true)->orderBy('code')->get(),
            'allIndices' => IndexDefinition::where('id', '!=', $index->id)->orderBy('index_name')->get(),
        ]);
    }

    public function update(Request $request, IndexDefinition $index)
    {
        $data = $request->validate([
            'index_name'         => 'required|string|max:100',
            'market'             => 'nullable|string|max:100',
            'index_group'        => 'nullable|string|max:100',
            'index_subgroup'     => 'nullable|string|max:100',
            'label'              => 'nullable|string|max:100',
            'format'             => 'required|in:Daily,Monthly,Quarterly,Annual',
            'class'              => 'nullable|string|max:50',
            'delivery_unit'      => 'nullable|string|max:50',
            'date_sequence'      => 'nullable|string|max:50',
            'payment_convention' => 'nullable|string|max:50',
            'coverage_end_date'  => 'nullable|date',
            'interpolation'      => 'nullable|in:Back-Step,Front-Step,Linear',
            'inheritance'        => 'boolean',
            'discount_index_id'  => 'nullable|exists:index_definitions,id',
            'reference_source'   => 'nullable|string|max:100',
            'projection_method'  => 'nullable|string|max:100',
            'day_start_time'     => 'nullable|date_format:H:i',
            'holiday_schedule'   => 'nullable|string|max:100',
            'index_type'         => 'nullable|in:Standard,Composite',
            'version_status'     => 'nullable|in:Pending,Authorized,Superseded',
            'base_currency_id'   => 'nullable|exists:currencies,id',
            'uom_id'             => 'nullable|exists:uoms,id',
            'status'             => 'required|in:Custom,Official,Template',
            'rec_status'         => 'required|in:Auth Pending,Authorized,Do Not Use',
        ]);

        // Handle grid point addition via the show-page modal
        if ($request->boolean('add_grid_point')) {
            $request->validate([
                'grid_date'  => 'required|date',
                'grid_price' => 'required|numeric|min:0',
            ]);
            \App\Models\IndexGridPoint::updateOrCreate(
                ['index_id' => $index->id, 'price_date' => $request->grid_date],
                ['price' => $request->grid_price, 'entered_by' => auth()->id()]
            );
            return redirect()->route('master.indices.show', $index)->with('success', 'Price added.');
        }

        $data['inheritance'] = $request->boolean('inheritance');
        $data['version']     = $index->version + 1;
        $index->update($data);
        return redirect()->route('master.indices.show', $index)->with('success', 'Index updated.');
    }

    public function destroy(IndexDefinition $index)
    {
        $index->delete();
        return redirect()->route('master.indices.index')->with('success', 'Deleted.');
    }
}
