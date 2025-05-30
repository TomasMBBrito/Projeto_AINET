<?php

namespace App\Http\Controllers;

use App\Models\ShippingCost;
use Illuminate\Http\Request;

class ShippingCostController extends Controller
{
    public function index()
    {
        $settings = ShippingCost::orderBy('min_value_threshold')->get();
        return view('admin.settings.shipping_cost.index', compact('settings'));
    }

    // public function create()
    // {
    //     return view('admin.settings.shipping_costs.create');
    // }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'min_value_threshold' => 'required|numeric|min:0',
    //         'max_value_threshold' => 'required|numeric|gt:min_value_threshold',
    //         'shipping_cost' => 'required|numeric|min:0',
    //     ]);

    //     ShippingCostSetting::create($validated);

    //     return redirect()->route('admin.settings.shipping_costs.index')->with('success', 'Intervalo de custo de envio criado.');
    // }

    // public function edit(ShippingCostSetting $shippingCostSetting)
    // {
    //     return view('admin.settings.shipping_costs.edit', compact('shippingCostSetting'));
    // }

    // public function update(Request $request, ShippingCostSetting $shippingCostSetting)
    // {
    //     $validated = $request->validate([
    //         'min_value_threshold' => 'required|numeric|min:0',
    //         'max_value_threshold' => 'required|numeric|gt:min_value_threshold',
    //         'shipping_cost' => 'required|numeric|min:0',
    //     ]);

    //     $shippingCostSetting->update($validated);

    //     return redirect()->route('admin.settings.shipping_costs.index')->with('success', 'Intervalo atualizado com sucesso.');
    // }

    // public function destroy(ShippingCostSetting $shippingCostSetting)
    // {
    //     $shippingCostSetting->delete();
    //     return back()->with('success', 'Intervalo removido com sucesso.');
    // }
}
