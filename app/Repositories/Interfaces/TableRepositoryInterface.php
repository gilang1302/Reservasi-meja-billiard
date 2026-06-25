<?php

namespace App\Repositories\Interfaces;

use App\Models\Table;
use Illuminate\Support\Collection;

interface TableRepositoryInterface
{
    public function all(): Collection;
    
    public function find(string $id): ?Table;
    
    public function create(array $data): Table;
    
    public function updateStatus(string $id, string $status): bool;
}
