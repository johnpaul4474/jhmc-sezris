<?php

namespace App\Http\Controllers\Locator;

use App\Http\Controllers\Controller;
use App\Services\UploadService;
use App\Services\ApplicationService;
use Illuminate\Http\Request;

class SezrisController extends Controller
{
    protected UploadService $uploadService;
    protected ApplicationService $appService;

    public function __construct(UploadService $uploadService, ApplicationService $appService)
    {
        $this->uploadService = $uploadService;
        $this->appService = $appService;
    }

    public function index(Request $request)
{
    $type = $request->get('type', 'application');

    $service = match ($type) {
        'upload' => app(\App\Services\UploadService::class),
        'application' => app(\App\Services\ApplicationService::class),
        default => throw new \InvalidArgumentException("Invalid service type"),
    };

    return response()->json($service->fetchData([]));
}
}
