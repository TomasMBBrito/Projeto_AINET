<?php

namespace App\Http\Controllers;

use App\Models\ShippingCost;
use Illuminate\Http\Request;

class ShippingCostController extends Controller
{
    public function index()
    {
        $shippingCosts = ShippingCost::orderBy('min_value_threshold')->paginate(10);
        return view('admin.settings.shipping_costs.index', compact('shippingCosts'));
    }


    public function create()
    {
        return view('admin.settings.shipping_costs.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'min_value_threshold' => 'required|numeric|min:0',
            'max_value_threshold' => 'required|numeric|gt:min_value_threshold',
            'shipping_cost' => 'required|numeric|min:0',
        ]);

        ShippingCost::create($validated);

        return redirect()->route('admin.settings.shipping_costs.index')->with('success', 'Shipping cost range created.');
    }

    public function edit(ShippingCost $shippingCost)
    {
        return view('admin.settings.shipping_costs.edit', compact('shippingCost'));
    }

    public function update(Request $request, ShippingCost $shippingCost)
    {
        $validated = $request->validate([
            'min_value_threshold' => 'required|numeric|min:0',
            'max_value_threshold' => 'required|numeric|gt:min_value_threshold',
            'shipping_cost' => 'required|numeric|min:0',
        ]);

        $shippingCost->update($validated);

        return redirect()->route('admin.settings.shipping_costs.index')->with('success', 'Range updated successfully.');
    }
    public function destroy(ShippingCost $shippingCost)
    {
        $shippingCost->delete();
        return back()->with('success', 'Range removed successfully.');
    }
}
