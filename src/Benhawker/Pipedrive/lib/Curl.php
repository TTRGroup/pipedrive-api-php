<?php namespace Benhawker\Pipdrive\lib;

use Benhawker\Pipedrive\Exceptions\PipedriveHttpError;
use Benhawker\Pipedrive\Exceptions\PipedriveApiError;

class Curl
{
    const USER_AGENT = 'Pipedrive-PHP/0.1';

    public $curl;

    public function __construct()
    {
        $this->curl = curl_init();
        $this->setOpt(CURLOPT_USERAGENT, self::USER_AGENT);
        $this->setOpt(CURLOPT_HEADER, false);
        $this->setOpt(CURLOPT_RETURNTRANSFER, true);
    }

    public function __destruct()
    {
        if (is_resource($this->curl)) {
            curl_close($this->curl);
        }
    }

    public function setOpt($option, $value)
    {
        return curl_setopt($this->curl, $option, $value);
    }

    public function get($url, $data = array())
    {

        $this->setopt(CURLOPT_URL, $this->buildURL($url, $data));
        $this->setOpt(CURLOPT_CUSTOMREQUEST, 'GET');
        $this->setopt(CURLOPT_HTTPGET, true);

        return $this->exec();
    }

    public function post($url, $data = array())
    {
        $this->setOpt(CURLOPT_URL, $this->buildURL($url));
        $this->setOpt(CURLOPT_CUSTOMREQUEST, 'POST');
        $this->setOpt(CURLOPT_POST, true);
        $this->setOpt(CURLOPT_POSTFIELDS, $this->postfields($data));

        return $this->exec();
    }

    public function put($url, $data = array())
    {
        $this->setOpt(CURLOPT_URL, $url);
        $this->setOpt(CURLOPT_CUSTOMREQUEST, 'PUT');
        $this->setOpt(CURLOPT_POSTFIELDS, http_build_query($data));

        return $this->exec();
    }

    public function delete($url, $data = array())
    {
        $this->setOpt(CURLOPT_URL, $this->buildURL($url, $data));
        $this->setOpt(CURLOPT_CUSTOMREQUEST, 'DELETE');

        return $this->exec();
    }

    private function buildURL($url, $data = array())
    {
        return $url . (empty($data) ? '' : '?' . http_build_query($data));
    }

    private function postfields($data)
    {

        foreach ($data as $key => $value) {
            // Fix "Notice: Array to string conversion" when $value in
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $value) is an array
            // that contains an empty array.
            if (is_array($value) && empty($value)) {
                $data[$key] = '';
            // Fix "curl_setopt(): The usage of the @filename API for
            // file uploading is deprecated. Please use the CURLFile
            // class instead".
            } elseif (is_string($value) && strpos($value, '@') === 0) {
                if (class_exists('CURLFile')) {
                    $data[$key] = new CURLFile(substr($value, 1));
                }
            }
        }

        return $data;
    }

    protected function exec()
    {
        $response = curl_exec($this->curl);
        $info     = curl_getinfo($this->curl);

        if (curl_error($this->curl)) {
            //throw error
            throw new PipedriveHttpError('API call failed' . curl_error($this->curl));
        }

        if (floor($info['http_code'] / 100) >= 4) {
            throw new PipedriveApiError('API call failed' . curl_error($this->curl));
        }

        $result = json_decode($response, true);

    }
}
