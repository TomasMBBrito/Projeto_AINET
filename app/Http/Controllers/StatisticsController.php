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
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
         $user = \App\Models\User::find(Auth::id());

        if ($user->type === 'board' || $user->type === 'employee') {
            $month = $request->input('month');
            $username = $request->input('user');
            $user_id = null;

            if ($username) {
                $matchedUser = User::where('name', $username)->first();

                if ($matchedUser) {
                    $user_id = $matchedUser->id;
                } else {
                    // Pode lançar um erro ou apenas seguir sem filtrar por user
                    return redirect()->back()->with('error', 'User not found.');
                }
            }
            $categoryId = $request->input('category_id');
            $productId = $request->input('product_id');
            $year = null;
            $monthNumber = null;
            if ($month) {
                try {
                    $date = Carbon::createFromFormat('Y-m', $month);
                    $year = $date->year;
                    $monthNumber = $date->month;
                } catch (\Exception $e) {
                }
            }

            $salesByMonth = $this->getSalesByMonth($year, $monthNumber, $user_id, $categoryId, $productId);
            $membersCountByMonth = $this->getMembersCountByMonth($year, $monthNumber);
            $topProducts = $this->getTopProducts($year, $monthNumber, $categoryId, $user_id);
            $salesByUser = $this->getSalesByUser($year, $monthNumber, $categoryId, $productId);
            $salesByCategory = $this->getSalesByCategory($year, $monthNumber, $user_id, $productId);
            $summary = $this->getSalesSummary($year,$month);

            return view('statistics.admin', [
                'salesByMonth' => $salesByMonth,
                'membersCountByMonth' => $membersCountByMonth,
                'topProducts' => $topProducts,
                'salesByUser' => $salesByUser,
                'salesByCategory' => $salesByCategory,
                'selectedMonth' => $month,
                'users' => User::where('type', 'member')->get(),
                'categories' => Category::all(),
                'products' => Product::all(),
                'summary' => $summary
            ]);
        }

        $month = $request->input('month'); // formato "YYYY-MM"

        // Para mostrar o mês por extenso
        $selectedMonthLabel = $month ? Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') : null;

        return view('statistics.member', [
            'spendingByMonth' => $this->getSpendingByMonth($user->id, $month),
            'orderCount' => $this->getOrderCount($user->id, $month),
            'orderSpending' => $this->getOrderSpending($user->id, $month),
            'creditsAdded' => $this->getCreditsAdded($user->id, $month),
            'selectedMonth' => $month,
            'selectedMonthLabel' => $selectedMonthLabel,
        ]);
    }

    private function getSalesByMonth($year = null, $month = null, $user = null, $categoryId = null, $productId = null)
    {
        $query = DB::table('operations')
            ->join('orders', 'operations.order_id', '=', 'orders.id')
            ->join('items_orders', 'orders.id', '=', 'items_orders.order_id')
            ->join('products', 'items_orders.product_id', '=', 'products.id')
            ->where('operations.type', 'debit')
            ->where('operations.debit_type', 'order');

        if ($year && $month) {
            $query->whereYear('operations.date', $year)
                ->whereMonth('operations.date', $month);
        }

        if ($user) {
            $query->where('operations.card_id', $user);
        }

        if ($categoryId) {
            $query->where('products.category_id', $categoryId);
        }

        if ($productId) {
            $query->where('products.id', $productId);
        }

        return $query->selectRaw('DAY(operations.date) as day, SUM(operations.value) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');
    }


    private function getSalesSummary($year = null, $month = null)
    {
        $query = Operation::where('type', 'debit')->where('debit_type', 'order');

        if ($year && $month) {
            $query->whereYear('date', $year)->whereMonth('date', $month);
        }

        return $query->selectRaw('
            SUM(value) as total_sales,
            AVG(value) as average_sales,
            MAX(value) as max_sale,
            MIN(value) as min_sale
        ')->first();
    }

    private function getSalesByCategory($year = null, $month = null, $user = null, $productId = null)
    {
        $query = DB::table('items_orders')
            ->join('products', 'items_orders.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'items_orders.order_id', '=', 'orders.id')
            ->join('operations', 'orders.id', '=', 'operations.order_id')
            ->where('operations.type', 'debit')
            ->where('operations.debit_type', 'order')
            ->select('categories.name', DB::raw('SUM(items_orders.quantity * items_orders.unit_price) as total'));

        if ($year && $month) {
            $query->whereYear('operations.date', $year)
                ->whereMonth('operations.date', $month);
        }

        if ($user) {
            $query->where('operations.card_id', $user);
        }

        if ($productId) {
            $query->where('products.id', $productId);
        }

        return $query->groupBy('categories.name')
            ->orderByDesc('total')
            ->get();
    }


    private function getSalesByUser($year = null, $month = null, $categoryId = null, $productId = null)
    {
        $query = DB::table('operations')
            ->join('users', 'operations.card_id', '=', 'users.id')
            ->join('orders', 'operations.order_id', '=', 'orders.id')
            ->join('items_orders', 'orders.id', '=', 'items_orders.order_id')
            ->join('products', 'items_orders.product_id', '=', 'products.id')
            ->where('operations.type', 'debit')
            ->where('operations.debit_type', 'order')
            ->select('users.name', DB::raw('SUM(operations.value) as total'));

        if ($year && $month) {
            $query->whereYear('operations.date', $year)->whereMonth('operations.date', $month);
        }

        if ($categoryId) {
            $query->where('products.category_id', $categoryId);
        }

        if ($productId) {
            $query->where('products.id', $productId);
        }

        return $query->groupBy('users.name')
            ->orderByDesc('total')
            ->limit(20)
            ->get();
    }




    private function getTopProducts($year = null, $month = null, $categoryId = null, $user = null)
    {
        $query = DB::table('items_orders')
            ->join('products', 'items_orders.product_id', '=', 'products.id')
            ->join('orders', 'items_orders.order_id', '=', 'orders.id')
            ->join('operations', 'orders.id', '=', 'operations.order_id') // associar com operação de débito
            ->where('operations.type', 'debit')
            ->where('operations.debit_type', 'order')
            ->select('products.name', DB::raw('SUM(items_orders.quantity) as total'));

        if ($year && $month) {
            $query->whereYear('operations.date', $year)
                ->whereMonth('operations.date', $month);
        }

        if ($categoryId) {
            $query->where('products.category_id', $categoryId);
        }

        if ($user) {
            $query->where('operations.card_id', $user);
        }

        return $query->groupBy('products.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
    }


    private function getSpendingByMonth($user, $month = null)
    {
        $query = Operation::where('card_id', $user)
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

    private function getOrderCount($user, $month = null)
    {
        $query = Order::where('member_id', $user);

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

    private function getOrderSpending($user, $month = null)
    {
        $query = Operation::where('card_id', $user)
            ->where('type', 'debit')
            ->where('debit_type', 'order');

        if ($month) {
            $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $end = Carbon::createFromFormat('Y-m', $month)->endOfMonth();
            $query->whereBetween('date', [$start, $end]);
        }

        return $query->selectRaw('DATE(date) as day, SUM(value) as total')
    ->groupBy('day')
    ->orderBy('day')
    ->pluck('total', 'day');
    }

    private function getCreditsAdded($user, $month = null)
    {
        $query = Operation::where('card_id', $user)
            ->where('type', 'credit');

        if ($month) {
            $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $end = Carbon::createFromFormat('Y-m', $month)->endOfMonth();
            $query->whereBetween('date', [$start, $end]);
        }

        return $query->selectRaw('DATE(date) as day, SUM(value) as total')
    ->groupBy('day')
    ->orderBy('day')
    ->pluck('total', 'day');
    }


    public function exportSalesXLSX()
    {
        return Excel::download(new SalesExport, 'sales.xlsx');
    }

    public function exportSalesCSV()
    {
        return Excel::download(new SalesExport, 'sales.csv');
    }
}
