<?php

namespace App\Services;

use App\Contracts\ISezrisService;

class UploadService implements ISezrisService
{
    public function fetchData(array $filters): array
    {
        return [
            [
                'id' => 1,
                'title' => 'SEZRIS Entry A',
                'status' => 'pending',
                'uploaded_file' => "http://localhost/storage/test.jpg",
            ],
            [
                'id' => 2,
                'title' => 'SEZRIS Entry B',
                'status' => 'approved',
                'uploaded_file' => "http://localhost/storage/test2.jpg",
            ],
            [
                'id' => 3,
                'title' => 'SEZRIS Entry C',
                'status' => 'rejected',
                'uploaded_file' => "http://localhost/storage/test3.jpg",
            ],
        ];
    }

    public function store(array $data): bool
    {
        // Simulate storing upload data
        return true;
    }

    public function getById(int $id): array|null
    {
        // Simulate finding one of the uploads
        $uploads = $this->fetchData([]);
        foreach ($uploads as $upload) {
            if ($upload['id'] === $id) {
                return $upload;
            }
        }

        return null;
    }
}
