<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    //
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'vehicle_id' => 'required|exists:vehicles,id',
                'description' => 'required|string|max:255',
                'price' => 'nullable|numeric|min:0'
            ],
            [
                'vehicle_id.required' => 'O veículo é obrigatório.',
                'vehicle_id.exists' => 'Veículo não encontrado.',
                'description.required' => 'A descrição é obrigatória.'
            ]
        );

        // regra de negócio
        $data['status'] = 'open';
        $data['started_at'] = now();

        $service = Service::create($data);

        return response()->json($service, 201);
    }

    public function start($id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json(['message' => 'Serviço não encontrado'], 404);
        }

        if ($service->status !== 'open') {
            return response()->json(['message' => 'Serviço não pode ser iniciado'], 400);
        }

        $service->update([
            'status' => 'in_progress',
            'started_at' => now()
        ]);

        return response()->json($service);
    }

    public function finish($id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json(['message' => 'Serviço não encontrado'], 404);
        }

        if ($service->status !== 'in_progress') {
            return response()->json(['message' => 'Serviço não pode ser finalizado'], 400);
        }

        $service->update([
            'status' => 'done',
            'finished_at' => now()
        ]);

        return response()->json($service);
    }

    public function index(Request $request)
    {
        $query = Service::query();

        // 🔍 filtro por status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        //  filtro por veículo
        if ($request->has('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        //  filtro por data início
        if ($request->has('date_from')) {
            $query->whereDate('started_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('started_at', '<=', $request->date_to);
        }

        //  ordenação
        $order = $request->get('order', 'desc');
        $query->orderBy('created_at', $order);

        //  paginação
        $services = $query->paginate(10);

        return response()->json([
            'data' => $services->items(),
            'meta' => [
                'current_page' => $services->currentPage(),
                'last_page' => $services->lastPage(),
                'per_page' => $services->perPage(),
                'total' => $services->total(),
            ]
        ]);
    }
}
