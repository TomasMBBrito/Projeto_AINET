<?php

namespace App\Http\Controllers;

use App\Services\Payment;
use App\Models\Card;
use App\Models\Operation;
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


    public function index()
    {
        $user = Auth::user();
        $card = Card::where('id', $user->id)->first();

        if (!$card) {
            return redirect()->route('home')->with('error', 'No card associated with user.');
        }

        return view('card.index', compact('card', 'user'));
    }

    public function showCreditForm()
    {
        $user = Auth::user();
        return view('card.credit', [
            'default_payment_type' => $user->default_payment_type,
            'default_payment_reference' => $user->default_payment_reference,
        ]);
    }

    public function credit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_type' => 'required|in:Visa,PayPal,MB WAY',
            'payment_reference' => 'required',
        ], [
            'amount.required' => 'The amount is mandatory.',
            'amount.numeric' => 'The amount must be a numeric value.',
            'amount.min' => 'The amount must be at least €0.01.',
            'payment_type.required' => 'Payment method is required.',
            'payment_type.in' => 'The selected payment method is invalid.',
            'payment_reference.required' => 'Payment reference is mandatory.',
        ]);

        $user = Auth::user();
        $card = Card::where('id', $user->id)->first();

        if (!$card) {
            return redirect()->route('card.index')->with('error', 'No card associated with user.');
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
                    'payment_reference' => 'digits:16|numeric',
                    'cvc_code' => 'required|digits:3|numeric',
                ], [
                    'payment_reference.digits' => 'The card number must be exactly 16 digits long.',
                    'payment_reference.numeric' => 'The card number must be digits only.',
                    'cvc_code.required' => 'The CVC code is required.',
                    'cvc_code.digits' => 'The CVC code must be exactly 3 digits long.',
                    'cvc_code.numeric' => 'The CVC code must be digits only.',
                ]);
                $payment_success = Payment::payWithVisa($payment_reference, $cvc_code);
                break;
            case 'PayPal':
                $request->validate([
                    'payment_reference' => 'email|max:255',
                ], [
                    'payment_reference.email' => 'PayPal email must be valid.',
                    'payment_reference.max' => 'PayPal email cannot exceed 255 characters.',
                ]);
                $payment_success = Payment::payWithPaypal($payment_reference);
                break;
            case 'MB WAY':
                $request->validate([
                    'payment_reference' => 'digits:9|numeric|starts_with:9',
                ], [
                    'payment_reference.digits' => 'Mobile number must have exactly 9 digits.',
                    'payment_reference.numeric' => 'Mobile number must contain only digits.',
                    'payment_reference.starts_with' => 'Mobile number must start with 9.',
                ]);
                $payment_success = Payment::payWithMBway($payment_reference);
                break;
        }

        if (!$payment_success) {
            return redirect()->route('card.credit')->with('error', 'Payment failed. Please check the data provided.');
        }

        DB::beginTransaction();
        try {
            $card->balance += $amount;
            $card->save();

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
            return redirect()->route('card.index')->with('success', 'Card credited successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('card.credit')->with('error', 'Error processing credit. Please try again..');
        }
    }

    public function transactions()
    {
        $user = Auth::user();
        $card = Card::where('id', $user->id)->first();

        if (!$card) {
            return redirect()->route('home')->with('error', 'No card associated with user.');
        }

        $operations = Operation::where('card_id', $card->id)
            ->with('order')
            ->orderBy('date', 'desc')
            ->get();

        return view('card.transactions', compact('card', 'operations'));
    }
}
