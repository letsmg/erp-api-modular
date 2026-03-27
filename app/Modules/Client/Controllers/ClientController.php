<?php

namespace App\Modules\Client\Controllers;

use App\Http\Controllers\ApiController;
use App\Modules\Client\Models\Client;
use App\Modules\Client\Requests\StoreClientRequest;
use App\Modules\Client\Requests\UpdateClientRequest;
use App\Modules\Client\Services\ClientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends ApiController
{
    public function __construct(private readonly ClientService $service) {}

    public function index(Request $request): JsonResponse
    {
        return $this->paginated($this->service->paginate($request->input('search')), 'Clientes carregados com sucesso.');
    }

    public function store(StoreClientRequest $request): JsonResponse
    {
        return $this->created($this->service->create($request->validated()), 'Cliente criado com sucesso.');
    }

    public function show(Client $client): JsonResponse
    {
        return $this->success($client->load(['user', 'address', 'sales.items']));
    }

    public function update(UpdateClientRequest $request, Client $client): JsonResponse
    {
        return $this->success($this->service->update($client, $request->validated()), 'Cliente atualizado com sucesso.');
    }

    public function destroy(Client $client): JsonResponse
    {
        $this->service->delete($client);

        return $this->deleted('Cliente removido com sucesso.');
    }
}
