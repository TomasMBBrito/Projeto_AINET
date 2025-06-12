<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Operation;
use App\Models\Setting;

class MembershipFeeController extends Controller
{
    public function showPayMembership()
    {
        $user = Auth::user();

        if (in_array($user->type, ['member', 'board'])) {
            return redirect()->route('cart.confirm');
        }

        $membershipFee = Setting::getValue('membership_fee', 100); // 50 é default

        return view('membership_fee.pay',compact('membershipFee'));
    }

    public function processMembershipFee(Request $request)
    {
        $user = Auth::user();
        $card = $user->card;

        if (!$card) {
            return redirect()->route('card.create')->with('error', 'Crie o cartão virtual antes de pagar a quota.');
        }

        $membershipFee = 50; // Valor da quota (exemplo)

        if ($card->balance < $membershipFee) {
            return redirect()->route('card.topup')->with('error', 'Saldo insuficiente. Por favor, adicione saldo ao cartão.');
        }

        // Debitar valor da quota no cartão virtual
        $card->decrement('balance', $membershipFee);

        // Atualizar tipo do usuário para 'member' (melhor usar update para evitar problemas)
        $user->type = 'member';
        $user->save();

        // Criar operação para registro da débito da quota
        Operation::create([
            'card_id' => $card->id,
            'type' => 'debit',
            'value' => $membershipFee,
            'date' => now()->toDateString(),
            'debit_type' => 'membership_fee',
            'order_id' => null,
            'payment_type' => $user->default_payment_type ?? null,
            'payment_reference' => $user->default_payment_reference ?? null,
            'custom' => null,
        ]);

        return redirect()->route('cart.confirm')->with('success', 'Quota paga com sucesso. Pode agora concluir a compra.');
    }
}
