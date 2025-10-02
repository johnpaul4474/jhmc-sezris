<?php
// File: app/Contracts/ISezrisService.php

namespace App\Contracts;

interface ISezrisService
{
    public function fetchData(array $filters): array;
}
