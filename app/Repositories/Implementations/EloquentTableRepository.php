<?php

namespace App\Repositories\Implementations;

use App\Models\Table;
use App\Repositories\Interfaces\TableRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentTableRepository implements TableRepositoryInterface
{
    public function all(): Collection
    {
        return Table::orderBy('table_number', 'asc')->get();
    }

    public function find(string $id): ?Table
    {
        return Table::find($id);
    }

    public function create(array $data): Table
    {
        return Table::create($data);
    }

    public function updateStatus(string $id, string $status): bool
    {
        $table = Table::find($id);
        if ($table) {
            return $table->update(['status' => $status]);
        }
        return false;
    }
}
