<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Client;

class VehicleController extends Controller
{
    //
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'client_id' => 'required|exists:clients,id',
                'plate' => 'required|string|max:10|unique:vehicles,plate',
                'brand' => 'required|string|max:100',
                'model' => 'required|string|max:100',
                'year' => 'required|integer|min:1900|max:' . date('Y'),
                'color' => 'nullable|string|max:50',
            ],
            [
                'client_id.required' => 'O cliente é obrigatório.',
                'client_id.exists' => 'Cliente não encontrado.',

                'plate.required' => 'A placa é obrigatória.',
                'plate.unique' => 'Já existe um veículo com essa placa.',

                'brand.required' => 'A marca é obrigatória.',
                'model.required' => 'O modelo é obrigatório.',

                'year.required' => 'O ano é obrigatório.',
                'year.integer' => 'O ano deve ser um número válido.',
            ]
        );

        $vehicle = Vehicle::create($data);

        return response()->json($vehicle, 201);
    }

    // listar
    public function index(Request $request)
    {
        $vehicles = Vehicle::with('client')->paginate(10);

        return response()->json([
            'data' => $vehicles->items(),
            'meta' => [
                'current_page' => $vehicles->currentPage(),
                'last_page' => $vehicles->lastPage(),
                'per_page' => $vehicles->perPage(),
                'total' => $vehicles->total(),
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::find($id);

        if (!$vehicle) {
            return response()->json([
                'message' => 'Veículo não encontrado'
            ], 404);
        }

        $data = $request->validate(
            [
                'client_id' => 'required|exists:clients,id',
                'plate' => 'required|string|max:10|unique:vehicles,plate,' . $vehicle->id,
                'brand' => 'required|string|max:100',
                'model' => 'required|string|max:100',
                'year' => 'required|integer|min:1900|max:' . date('Y'),
                'color' => 'nullable|string|max:50',
            ],
            [
                'plate.unique' => 'Já existe um veículo com essa placa.',
                'client_id.exists' => 'Cliente não encontrado.'
            ]
        );

        $vehicle->update($data);

        return response()->json($vehicle);
    }

    public function services($id)
    {
        $vehicle = Vehicle::find($id);

        if (!$vehicle) {
            return response()->json([
                'message' => 'Veículo não encontrado'
            ], 404);
        }

        $services = $vehicle->services()->paginate(10);

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
