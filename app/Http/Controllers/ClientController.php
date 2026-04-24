<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    //
    public function index(Request $request){
        $status = $request->query('status');
        $search = $request->query('search');
        $sort = $request->query('sort', 'name'); // padrão
        $direction = $request->query('direction', 'asc'); // padrão

        // 🔒 Segurança: evitar SQL injection em orderBy
        $allowedSorts = ['name', 'created_at'];
        $allowedDirections = ['asc', 'desc'];

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'name';
        }

        if (!in_array($direction, $allowedDirections)) {
            $direction = 'asc';
        }

        // Base da query
        if ($status === 'inactive') {
            $query = Client::onlyTrashed();
        } elseif ($status === 'all') {
            $query = Client::withTrashed();
        } else {
            $query = Client::query();
        }

        // 🔍 Busca
        if ($search && trim($search) !== '') {
            $query->where('name', 'ILIKE', "%{$search}%");
        }

        // 🔃 Ordenação
        $query->orderBy($sort, $direction);

        // Paginação
        $clients = $query->paginate(10);

        return response()->json([
            'data' => $clients->items(),
            'meta' => [
                'current_page' => $clients->currentPage(),
                'last_page' => $clients->lastPage(),
                'per_page' => $clients->perPage(),
                'total' => $clients->total(),
            ]
        ]);
    }

    public function store(Request $request){
        $data = $request->validate(
            [
                'name' => 'required|string|max:150',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:150',
                'notes' => 'nullable|string'
            ],
            [
                'name.required' => 'O nome é obrigatório.',
                'name.max' => 'O nome deve ter no máximo 150 caracteres.',

                'phone.max' => 'O telefone deve ter no máximo 20 caracteres.',

                'email.email' => 'O e-mail deve ser válido.',
                'email.max' => 'O e-mail deve ter no máximo 150 caracteres.',

                'notes.string' => 'A observação deve ser um texto válido.'
            ]
        );

        $client = Client::create($data);

        return response()->json($client, 201);
    }

    public function update(Request $request, $id){
        $client = Client::find($id);

        if (!$client) {
            return response()->json([
                'message' => 'Cliente não encontrado'
            ], 404);
        }

        $data = $request->validate(
            [
                'name' => 'required|string|max:150',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:150',
                'notes' => 'nullable|string'
            ],
            [
                'name.required' => 'O nome é obrigatório.',
                'email.email' => 'O e-mail deve ser válido.'
            ]
        );

        $client->update($data);

        return response()->json($client);
    }

    public function destroy($id){
        $client = Client::find($id);

        if (!$client) {
            return response()->json([
                'message' => 'Cliente não encontrado'
            ], 404);
        }

        $client->delete();

        return response()->json([
            'message' => 'Cliente removido com sucesso'
        ]);
    }

    public function restore($id){
        $client = Client::withTrashed()->find($id);

        if (!$client) {
            return response()->json([
                'message' => 'Cliente não encontrado'
            ], 404);
        }

        if (!$client->trashed()) {
            return response()->json([
                'message' => 'Cliente já está ativo'
            ], 400);
        }

        $client->restore();

        return response()->json([
            'message' => 'Cliente restaurado com sucesso'
        ]);
    }

    public function vehicles($id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json([
                'message' => 'Cliente não encontrado'
            ], 404);
        }

        $vehicles = $client->vehicles()->paginate(10);
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
}


