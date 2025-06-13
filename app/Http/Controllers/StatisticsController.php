<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Operation;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Carbon\Carbon;
use App\Exports\SalesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->type === 'board') {
            $month = $request->input('month'); // esperado no formato YYYY-MM ou null

            $year = null;
            $monthNumber = null;
            if ($month) {
                try {
                    $date = Carbon::createFromFormat('Y-m', $month);
                    $year = $date->year;
                    $monthNumber = $date->month;
                } catch (\Exception $e) {
                    // Formato inválido, pode ignorar ou tratar erro
                }
            }

            $salesByMonth = $this->getSalesByMonth($year, $monthNumber);
            $membersCountByMonth = $this->getMembersCountByMonth($year, $monthNumber);
            $topProducts = $this->getTopProducts($year, $monthNumber);

            return view('statistics.admin', [
                'salesByMonth' => $salesByMonth,
                'membersCountByMonth' => $membersCountByMonth,
                'topProducts' => $topProducts,
                'selectedMonth' => $month,
            ]);
        }

        $month = $request->input('month'); // formato "YYYY-MM"

        // Para mostrar o mês por extenso
        $selectedMonthLabel = $month ? Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') : null;

        return view('statistics.member', [
            'spendingByMonth' => $this->getSpendingByMonth($user->id, $month),
            'orderCount' => $this->getOrderCount($user->id, $month),
            'selectedMonth' => $month,
            'selectedMonthLabel' => $selectedMonthLabel,
        ]);
    }

    private function getSalesByMonth($year = null, $month = null)
    {
        $query = Operation::where('type', 'debit')
            ->where('debit_type', 'order');

        if ($year && $month) {
            $query->whereYear('date', $year)->whereMonth('date', $month);
            // Aqui você pode querer ajustar para agrupar por dia ou ignorar agrupamento,
            // pois só é um mês.
            // Mas para simplicidade, vou agrupar por dia:
            return $query->selectRaw('DAY(date) as day, SUM(value) as total')
                ->groupBy('day')
                ->orderBy('day')
                ->pluck('total', 'day');
        }

        // Sem filtro: agrupado por mês
        return $query->selectRaw('MONTH(date) as month, SUM(value) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');
    }

    private function getTopProducts($year = null, $month = null)
    {
        $query = DB::table('items_orders')
            ->join('products', 'items_orders.product_id', '=', 'products.id')
            ->join('orders', 'items_orders.order_id', '=', 'orders.id') // Para filtrar por data do pedido
            ->select('products.name', DB::raw('SUM(items_orders.quantity) as total'));

        if ($year && $month) {
            $query->whereYear('orders.created_at', $year)
                  ->whereMonth('orders.created_at', $month);
        }

        return $query->groupBy('products.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
    }

    private function getSpendingByMonth($userId, $month = null)
    {
        $query = Operation::where('card_id', $userId)
            ->where('type', 'debit')
            ->where('debit_type', 'order');

        if ($month) {
            $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $end = Carbon::createFromFormat('Y-m', $month)->endOfMonth();
            $query->whereBetween('date', [$start, $end]);
        }

        return $query
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as month, SUM(value) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');
    }

    private function getOrderCount($userId, $month = null)
    {
        $query = Order::where('member_id', $userId);

        if ($month) {
            $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $end = Carbon::createFromFormat('Y-m', $month)->endOfMonth();
            $query->whereBetween('created_at', [$start, $end]);
        }

        return $query->count();
    }

    private function getMembersCountByMonth($year = null, $month = null)
    {
        $query = User::whereIn('type', ['member', 'board']);

        if ($year && $month) {
            $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
            // Agrupar por dia para o mês filtrado
            return $query->selectRaw('DAY(created_at) as day, COUNT(*) as total')
                ->groupBy('day')
                ->orderBy('day')
                ->pluck('total', 'day');
        }

        // Sem filtro: agrupado por mês
        return $query->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');
    }

    public function exportSales()
    {
        return Excel::download(new SalesExport, 'sales.xlsx');
    }
}
