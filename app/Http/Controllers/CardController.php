<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Card;
use App\Models\Order;
use App\Models\Operation;
use App\Services\Payment;

class CardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $card = $user->cards; // Changed from card to cards to match User model relationship

        if (!$card) {
            return redirect()->route('card.create')->with('info', 'Crie um cartão virtual para continuar.');
        }

        return view('card.index', compact('card'));
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->cards) { // Changed from card to cards
            return redirect()->route('card.index')->with('info', 'Você já tem um cartão virtual.');
        }
        return view('card.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->cards) { // Changed from card to cards
            return redirect()->route('card.index')->with('info', 'Você já tem um cartão virtual.');
        }

        do {
            $cardNumber = rand(100000, 999999);
        } while (Card::where('card_number', $cardNumber)->exists());

        Card::create([
            'id' => $user->id, // Changed from user_id to id to match cards table schema
            'card_number' => $cardNumber,
            'balance' => 0,
        ]);

        return redirect()->route('card.index')->with('success', 'Cartão virtual criado com sucesso!');
    }

    public function addBalance()
    {
        $user = Auth::user();
        $card = $user->cards; // Changed from card to cards
        if (!$card) {
            return redirect()->route('card.create')->with('error', 'Crie um cartão virtual primeiro.');
        }
        return view('card.add-balance', compact('card'));
    }

    public function processPayment(Request $request)
    {
        $user = Auth::user();
        $card = $user->cards; // Changed from card to cards
        $amount = $request->input('amount');
        $paymentMethod = $request->input('payment_method');

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:visa,paypal,mbway',
        ]);

        if (!$card || $amount <= 0) {
            return redirect()->back()->with('error', 'Dados inválidos.');
        }

        $paymentSuccess = false;
        $paymentReference = null;

        switch ($paymentMethod) {
            case 'visa':
                $cardNumber = $request->input('card_number');
                $cvcCode = $request->input('cvc_code');
                $request->validate([
                    'card_number' => 'required|digits:16',
                    'cvc_code' => 'required|digits:3',
                ]);
                $paymentSuccess = Payment::payWithVisa($cardNumber, $cvcCode);
                $paymentReference = $cardNumber;
                break;

            case 'paypal':
                $email = $request->input('email');
                $request->validate([
                    'email' => 'required|email',
                ]);
                $paymentSuccess = Payment::payWithPaypal($email);
                $paymentReference = $email;
                break;

            case 'mbway':
                $phoneNumber = $request->input('phone_number');
                $request->validate([
                    'phone_number' => 'required|digits:9|starts_with:9',
                ]);
                $paymentSuccess = Payment::payWithMBway($phoneNumber);
                $paymentReference = $phoneNumber;
                break;
        }

        if ($paymentSuccess) {
            $card->increment('balance', $amount);
            Operation::create([
                'card_id' => $card->id,
                'type' => 'credit',
                'value' => $amount,
                'date' => now()->toDateString(),
                'debit_type' => null,
                'credit_type' => 'payment',
                'payment_type' => $paymentMethod,
                'payment_reference' => $paymentReference,
                'order_id' => null,
                'custom' => null,
            ]);
            return redirect()->route('card.index')->with('success', 'Saldo adicionado com sucesso!');
        } else {
            return redirect()-> back()->with('error', 'Pagamento falhou. Verifique os dados e tente novamente.');
        }
    }

    public function transactions()
    {
        $user = Auth::user();
        $card = $user->cards; // Changed from card to cards
        if (!$card) {
            return redirect()->route('card.create')->with('info', 'Crie um cartão virtual primeiro.');
        }

        $transactions = Operation::where('card_id', $card->id)->get();
        $orders = Order::where('member_id', $user->id)->with('orderItems')->get();

        return view('card.transactions', compact('card', 'transactions', 'orders'));
    }
}
