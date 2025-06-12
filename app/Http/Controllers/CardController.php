<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Card;
use App\Models\Order;
use App\Models\Operation;

class CardController extends Controller
{
    public function index()
    {
        // Aqui você pode obter os cartões do utilizador logado, se já estiver implementado
        $userId = Auth::id();

        // Obtem os IDs dos pedidos (orders) do user autenticado
        $orderIds = Order::where('member_id', $userId)->pluck('id');

        // Obtem os IDs dos cartões usados nas operações relacionadas com esses pedidos
        $cardIds = Operation::whereIn('order_id', $orderIds)
                            ->whereNotNull('card_id')
                            ->pluck('card_id')
                            ->unique();

        // Finalmente, obtem os cartões com esses IDs
        $cards = Card::whereIn('id', $cardIds)->get();

        return view('card.index', compact('cards')); // pode passar os cartões para a view com ['cards' => $cards]
    }
    public function showCreate()
    {
        $user = Auth::user();
        // if ($user->card) {
        //     return redirect()->route('card.topup');
        // }
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
        return redirect()->route('membership_fee.pay')
            ->with('success', 'Cartão virtual criado com sucesso. Agora precisa de pagar a quota de membro.');
    }

        public function topup(Card $card)
    {
        // Verifica se o cartão pertence ao user autenticado, opcional mas recomendado
        // if ($card->user_id !== auth()->id()) abort(403);

        return view('card.topup', compact('card'));
    }

    public function storeTopup(Request $request, Card $card)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $card->balance += $request->input('amount');
        $card->save();

        // Aqui podes associar a operação a uma ordem ou só adicionar saldo (depende do teu modelo)
        // Exemplo de lógica simples (ajusta conforme a tua lógica real):
        Operation::create([
            'card_id' => $card->id,
            'order_id' => null, // ou algum valor válido
            'value' => $request->amount,
            'date' => date('Y-m-d'),
            'type' => 'debit',
        ]);

        return redirect()->route('cards.index')->with('success', 'Card topped up successfully.');
    }
}
