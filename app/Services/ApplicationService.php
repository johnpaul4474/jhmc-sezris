<?php

namespace App\Services;

use App\Services\Contracts\ISezrisService;

class ApplicationService implements ISezrisService
{
    public function fetchData(array $filters): array
    {
    return [
        [
            'id' => 1,
            'title' => 'SEZRIS Entry A',
            'status' => 'pending',
            'created_at' => now()->subDays(3)->toDateTimeString(),
        ],
        [
            'id' => 2,
            'title' => 'SEZRIS Entry B',
            'status' => 'approved',
            'created_at' => now()->subDays(1)->toDateTimeString(),
        ],
        [
            'id' => 3,
            'title' => 'SEZRIS Entry C',
            'status' => 'rejected',
            'created_at' => now()->subDays(7)->toDateTimeString(),
        ],
    ];
}


    public function store(array $data): bool
    {
        // Implementation here
        return true;
    }

    public function getById(int $id): array|null
    {
        // Implementation here
        return null;
    }
}
