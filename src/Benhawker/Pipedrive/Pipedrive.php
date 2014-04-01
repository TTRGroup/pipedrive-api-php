<?php namespace Benhawker\Pipedrive;

class Pipedrive
{
    protected $apiKey;
    protected $protocol;
    protected $host;
    protected $version;

    public function __contstruct($apiKey = '', $protocol = 'https', $host = 'api.pipedrive.com', $version = 'v1')
    {
        $this->apiKey   = $apiKey;
        $this->protocol = $protocol;
        $this->host     = $host;
        $this->version  = $version;
    }

    public function setApi($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;
    }

    public function setHost($host)
    {
        $this->host = $host;
    }

    public function setVersion($version)
    {
        $this->version = $version;
    }
}
