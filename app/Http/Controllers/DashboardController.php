<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query();

        //  filtro por período (opcional)
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        //  totais
        $total = $query->count();
        $open = (clone $query)->where('status', 'open')->count();
        $inProgress = (clone $query)->where('status', 'in_progress')->count();
        $done = (clone $query)->where('status', 'done')->count();

        //  faturamento (só concluídos)
        $revenue = (clone $query)
            ->where('status', 'done')
            ->sum('price');

        return response()->json([
            'total_services' => $total,
            'open' => $open,
            'in_progress' => $inProgress,
            'done' => $done,
            'total_revenue' => $revenue
        ]);
    }
}