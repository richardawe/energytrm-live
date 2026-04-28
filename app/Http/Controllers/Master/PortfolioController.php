<?php
namespace App\Http\Controllers\Master;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Party;
use App\Models\Portfolio;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index()
    {
        return view('master.portfolios.index', [
            'portfolios' => Portfolio::with('businessUnit')->orderBy('name')->paginate(25),
        ]);
    }

    public function create()
    {
        return view('master.portfolios.create', [
            'businessUnits'   => Party::businessUnits()->internal()->authorized()->orderBy('short_name')->get(),
            'currencies'      => Currency::where('is_active', true)->orderBy('code')->get(),
            'linkedPortfolios'=> Portfolio::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'               => 'required|string|max:100',
            'long_name'          => 'nullable|string|max:255',
            'business_unit_id'   => 'nullable|exists:parties,id',
            'type'               => 'nullable|string|max:50',
            'currency_id'        => 'nullable|exists:currencies,id',
            'is_restricted'      => 'boolean',
            'requires_strategy'  => 'boolean',
            'linked_portfolio_id'=> 'nullable|exists:portfolios,id',
            'status'             => 'required|in:Auth Pending,Authorized,Do Not Use',
        ]);
        $data['is_restricted']    = $request->boolean('is_restricted');
        $data['requires_strategy']= $request->boolean('requires_strategy');
        Portfolio::create($data);
        return redirect()->route('master.portfolios.index')->with('success', 'Portfolio created.');
    }

    public function show(Portfolio $portfolio)
    {
        return view('master.portfolios.show', [
            'portfolio' => $portfolio->load('businessUnit', 'currency', 'linkedPortfolio'),
        ]);
    }

    public function edit(Portfolio $portfolio)
    {
        return view('master.portfolios.edit', [
            'portfolio'       => $portfolio,
            'businessUnits'   => Party::businessUnits()->internal()->authorized()->orderBy('short_name')->get(),
            'currencies'      => Currency::where('is_active', true)->orderBy('code')->get(),
            'linkedPortfolios'=> Portfolio::where('id', '!=', $portfolio->id)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $data = $request->validate([
            'name'               => 'required|string|max:100',
            'long_name'          => 'nullable|string|max:255',
            'business_unit_id'   => 'nullable|exists:parties,id',
            'type'               => 'nullable|string|max:50',
            'currency_id'        => 'nullable|exists:currencies,id',
            'is_restricted'      => 'boolean',
            'requires_strategy'  => 'boolean',
            'linked_portfolio_id'=> 'nullable|exists:portfolios,id',
            'status'             => 'required|in:Auth Pending,Authorized,Do Not Use',
        ]);
        $data['is_restricted']    = $request->boolean('is_restricted');
        $data['requires_strategy']= $request->boolean('requires_strategy');
        $portfolio->update($data);
        return redirect()->route('master.portfolios.index')->with('success', 'Portfolio updated.');
    }

    public function destroy(Portfolio $portfolio)
    {
        $portfolio->delete();
        return redirect()->route('master.portfolios.index')->with('success', 'Deleted.');
    }
}
