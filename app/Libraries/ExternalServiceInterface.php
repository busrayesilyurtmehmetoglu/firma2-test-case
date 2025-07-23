<?php

namespace App\Libraries;

interface ExternalServiceInterface
{
    /**
     * Dış servisten veri çeker
     * @param string $endpoint
     * @param array $params
     * @return array
     */
    public function fetch(string $endpoint, array $params = []): array;

    /**
     * Çekilen veriyi normalize eder
     * @param array $data
     * @return array
     */
    public function normalize(array $data): array;
} 