<?php

namespace App\Modules\Client\Services;

use App\Modules\Client\Models\Client;
use App\Modules\Client\Repositories\ClientRepository;
use Illuminate\Support\Facades\DB;

class ClientService
{
    public function __construct(private readonly ClientRepository $repository) {}

    public function paginate($search = null)
    {
        return $this->repository->paginate($search);
    }

    public function create(array $data): Client
    {
        return DB::transaction(function () use ($data) {
            $addressData = $data['address'];
            unset($data['address']);

            $client = $this->repository->create($data);
            $client->address()->create($addressData);

            return $client->load(['user', 'address']);
        });
    }

    public function update(Client $client, array $data): Client
    {
        return DB::transaction(function () use ($client, $data) {
            $addressData = $data['address'];
            unset($data['address']);

            $client->update($data);
            $client->address()->updateOrCreate([], $addressData);

            return $client->refresh()->load(['user', 'address']);
        });
    }

    public function delete(Client $client): void
    {
        $client->delete();
    }
}
