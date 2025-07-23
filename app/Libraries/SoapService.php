<?php

namespace App\Libraries;

class SoapService implements ExternalServiceInterface
{
    protected string $wsdl;
    protected \SoapClient $client;

    public function __construct(string $wsdl)
    {
        $this->wsdl = $wsdl;
        $this->client = new \SoapClient($wsdl, ['trace' => 1, 'exceptions' => true]);
    }

    public function fetch(string $function, array $params = []): array
    {
        $result = $this->client->__soapCall($function, [$params]);
        return json_decode(json_encode($result), true);
    }

    public function normalize(array $data): array
    {
        // Basit normalize örneği: olduğu gibi döndür
        return $data;
    }
} 