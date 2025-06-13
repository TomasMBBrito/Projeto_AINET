<?php

namespace App\Http\Controllers;

use App\Services\Payment;
use App\Models\Card;
use App\Models\Operation;
use App\Models\User;
use App\Models\Setting;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


use Illuminate\Routing\Controller as BaseController;

class CardController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display the member's card details.
     */
    public function index()
    {
        $user = Auth::user();
        $card = Card::where('id', $user->id)->first();

        if (!$card) {
            return redirect()->route('home')->with('error', 'Nenhum cartão associado ao utilizador.');
        }

        return view('card.index', compact('card', 'user'));
    }

    /**
     * Show the form to credit the card.
     */
    public function showCreditForm()
    {
        $user = Auth::user();
        return view('card.credit', [
            'default_payment_type' => $user->default_payment_type,
            'default_payment_reference' => $user->default_payment_reference,
        ]);
    }

    /**
     * Process the card credit operation.
     */
    public function credit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_type' => 'required|in:Visa,PayPal,MB WAY',
            'payment_reference' => 'required',
        ]);

        $user = Auth::user();
        $card = Card::where('id', $user->id)->first();

        if (!$card) {
            return redirect()->route('card.index')->with('error', 'Nenhum cartão associado ao utilizador.');
        }

        $amount = $request->input('amount');
        $payment_type = $request->input('payment_type');
        $payment_reference = $request->input('payment_reference');

        // Validate payment based on payment type
        $payment_success = false;
        switch ($payment_type) {
            case 'Visa':
                $cvc_code = $request->input('cvc_code');
                $request->validate([
                    'payment_reference' => 'digits:16',
                    'cvc_code' => 'required|digits:3',
                ]);
                $payment_success = Payment::payWithVisa($payment_reference, $cvc_code);
                break;
            case 'PayPal':
                $request->validate([
                    'payment_reference' => 'email',
                ]);
                $payment_success = Payment::payWithPaypal($payment_reference);
                break;
            case 'MB WAY':
                $request->validate([
                    'payment_reference' => 'digits:9|starts_with:9',
                ]);
                $payment_success = Payment::payWithMBway($payment_reference);
                break;
        }

        if (!$payment_success) {
            return redirect()->route('card.credit')->with('error', 'Pagamento falhou. Verifique os dados fornecidos.');
        }

        // Start a transaction to ensure data consistency
        DB::beginTransaction();
        try {
            // Update card balance
            $card->balance += $amount;
            $card->save();

            // Record the operation
            Operation::create([
                'card_id' => $card->id,
                'type' => 'credit',
                'value' => $amount,
                'date' => now()->toDateString(),
                'credit_type' => 'payment',
                'payment_type' => $payment_type,
                'payment_reference' => $payment_reference,
            ]);

            DB::commit();
            return redirect()->route('card.index')->with('success', 'Cartão creditado com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('card.credit')->with('error', 'Erro ao processar o crédito. Tente novamente.');
        }
    }

    /**
     * Display the transaction history.
     */
    public function transactions()
    {
        $user = Auth::user();
        $card = Card::where('id', $user->id)->first();

        if (!$card) {
            return redirect()->route('home')->with('error', 'Nenhum cartão associado ao utilizador.');
        }

        $operations = Operation::where('card_id', $card->id)
            ->with('order')
            ->orderBy('date', 'desc')
            ->get();

        return view('card.transactions', compact('card', 'operations'));
    }
}
