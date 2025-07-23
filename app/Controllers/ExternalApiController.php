<?php

namespace App\Controllers;

use App\Libraries\RestService;
use App\Libraries\ServiceManager;
use CodeIgniter\RESTful\ResourceController;

class ExternalApiController extends ResourceController
{
    protected $format = 'json';

    /**
     * POST /api/external/rest
     * Body: { "endpoint": "posts/1", "params": { ... } }
     */
    public function rest()
    {
        $data = $this->request->getJSON(true);
        $endpoint = $data['endpoint'] ?? '';
        $params = $data['params'] ?? [];
        if (!$endpoint) {
            return $this->failValidationError('endpoint is required');
        }
        $service = new RestService('https://jsonplaceholder.typicode.com');
        $manager = new ServiceManager($service);
        try {
            $result = $manager->getData($endpoint, $endpoint, $params);
            return $this->respond($result);
        } catch (\Throwable $e) {
            return $this->failServerError($e->getMessage());
        }
    }
} 