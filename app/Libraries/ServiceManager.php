<?php

namespace App\Libraries;

use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Cache\CacheInterface;
use CodeIgniter\Log\Logger;

class ServiceManager
{
    protected ExternalServiceInterface $service;
    protected CacheInterface $cache;
    protected BaseConnection $db;
    protected Logger $logger;
    protected int $cacheTtl = 1800; // 30 dakika

    public function __construct(ExternalServiceInterface $service)
    {
        $this->service = $service;
        $this->cache = \Config\Services::cache();
        $this->db = \Config\Database::connect();
        $this->logger = \Config\Services::logger();
    }

    /**
     * Dış servisten veri alır, cache ve db ile yönetir
     */
    public function getData(string $key, string $endpoint, array $params = []): array
    {
        // 1. Önce cache kontrolü
        $cacheKey = 'ext_' . md5($key . json_encode($params));
        $cached = $this->cache->get($cacheKey);
        if ($cached) {
            $this->log('cache', $endpoint, $params, 'success', 'Cache hit');
            return $cached;
        }

        // 2. DB kontrolü (örnek: external_data tablosu)
        $row = $this->db->table('external_data')->where('cache_key', $cacheKey)->get()->getRowArray();
        if ($row) {
            $data = json_decode($row['data'], true);
            $this->cache->save($cacheKey, $data, $this->cacheTtl);
            $this->log('db', $endpoint, $params, 'success', 'DB hit');
            return $data;
        }

        // 3. Dış servisten çek
        try {
            $raw = $this->service->fetch($endpoint, $params);
            $data = $this->service->normalize($raw);
            // DB'ye kaydet
            $this->db->table('external_data')->insert([
                'cache_key' => $cacheKey,
                'data' => json_encode($data),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            // Cache'e kaydet
            $this->cache->save($cacheKey, $data, $this->cacheTtl);
            $this->log('external', $endpoint, $params, 'success', 'External fetch');
            return $data;
        } catch (\Throwable $e) {
            $this->log('external', $endpoint, $params, 'error', $e->getMessage());
            throw $e;
        }
    }

    /**
     * Entegrasyon logunu kaydeder
     */
    protected function log(string $source, string $endpoint, array $params, string $status, string $message)
    {
        $this->db->table('integration_logs')->insert([
            'source' => $source,
            'endpoint' => $endpoint,
            'params' => json_encode($params),
            'status' => $status,
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $this->logger->log($status === 'success' ? 'info' : 'error', $message, $params);
    }
} 