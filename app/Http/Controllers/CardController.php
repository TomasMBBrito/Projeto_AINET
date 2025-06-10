<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    public function showCreate()
    {
        $user = Auth::user();
        if ($user->card) {
            return redirect()->route('cart.confirm');
        }
        return view('card.create');
    }

    public function storeCreate(Request $request)
    {
        $user = Auth::user();
        if (!$user->card) {
            // Criar cartão virtual com saldo inicial zero
            $user->card->create([
                'balance' => 0,
                'blocked' => false,
            ]);
        }
        return redirect()->route('membership_fee.pay_membership')
            ->with('success', 'Cartão virtual criado com sucesso. Agora precisa de pagar a quota de membro.');
    }

    public function showTopup()
    {
        $user = Auth::user();
        if (!$user->card) {
            return redirect()->route('card.create')->with('error', 'Precisa de criar um cartão antes.');
        }
        return view('card.topup', ['balance' => $user->card->balance]);
    }

    public function processTopup(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);
        $user = Auth::user();
        $card = $user->card;
        if (!$card) {
            return redirect()->route('card.create')->with('error', 'Cartão virtual não encontrado.');
        }

        $card->increment('balance', $request->input('amount'));

        return redirect()->route('cart.confirm')->with('success', 'Saldo adicionado com sucesso.');
    }
}
