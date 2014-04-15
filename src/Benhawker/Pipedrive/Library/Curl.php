<?php namespace Benhawker\Pipedrive\Library;

use Benhawker\Pipedrive\Exceptions\PipedriveHttpError;
use Benhawker\Pipedrive\Exceptions\PipedriveApiError;

/**
 * This class does the cURL requests for Pipedrive
 */
class Curl
{
    /**
     * User Agent used to send to API
     */
    const USER_AGENT = 'Pipedrive-PHP/0.1';

    /**
     * API Key
     * @var string
     */
    protected $apiKey;
    /**
     * API Url
     * @var string
     */
    protected $url;
    /**
     * Client URL Library
     * @var curl session
     */
    public $curl;

    /**
     * Initialise the cURL session and set headers
     */
    public function __construct($url, $apiKey)
    {
        //set URL and API Key
        $this->url    = $url;
        $this->apiKey = $apiKey;

        //Intialise cURL session
        $this->curl = curl_init();
        //Set up options for cURL session
        $this->setOpt(CURLOPT_USERAGENT, self::USER_AGENT)
             ->setOpt(CURLOPT_HEADER, false)
             ->setOpt(CURLOPT_RETURNTRANSFER, true)
             ->setOpt(CURLOPT_HTTPHEADER, array("Accept: application/json"));
    }

    /**
     * Close cURL session
     */
    public function __destruct()
    {
        //if session is open close it
        if (is_resource($this->curl)) {
            curl_close($this->curl);
        }
    }

    /**
     * Makes cURL get Request
     *
     * @param  string $method Pipedrive method
     * @return array  decoded Json Output
     */
    public function get($method, $data = array())
    {
        //set cURL transfer option for get request
        // and get ouput
        return $this->createEndPoint($method, $data)
                    ->setOpt(CURLOPT_CUSTOMREQUEST, 'GET')
                    ->setopt(CURLOPT_HTTPGET, true)
                    ->exec();
    }

    /**
     * Makes cURL get Request
     *
     * @param  string $method Pipedrive method
     * @return array  decoded Json Output
     */
    public function post($method, array $data)
    {
        //set cURL transfer option for post request
        // and get ouput
        return $this->createEndPoint($method)
                    ->setOpt(CURLOPT_CUSTOMREQUEST, 'POST')
                    ->setOpt(CURLOPT_POST, true)
                    ->setOpt(CURLOPT_POSTFIELDS, $this->postfields($data))
                    ->exec();
    }

    /**
     * Makes cURL get Request
     *
     * @param  string $method Pipedrive method
     * @return array  decoded Json Output
     */
    public function put($method, array $data)
    {
        //set cURL transfer option for post request
        // and get ouput
        return $this->createEndPoint($method)
                    ->setOpt(CURLOPT_CUSTOMREQUEST, 'PUT')
                    ->setOpt(CURLOPT_POSTFIELDS, http_build_query($data))
                    ->exec();
    }

    /**
     * Makes cURL get Request
     *
     * @param  string $method Pipedrive method
     * @return array  decoded Json Output
     */
    public function delete($method)
    {
        //set cURL transfer option for delete request
        // and get ouput
        return $this->createEndPoint($method)
                    ->setOpt(CURLOPT_CUSTOMREQUEST, 'DELETE')
                    ->exec();
    }

    /**
     * Execute current cURL session
     *
     * @return array decoded json ouput
     */
    protected function exec()
    {
        //get response output and info
        $response = curl_exec($this->curl);
        $info     = curl_getinfo($this->curl);

        //if there is a curl error throw Exception
        if (curl_error($this->curl)) {
            //throw error
            throw new PipedriveHttpError('API call failed: ' . curl_error($this->curl));
        }
        //decode output
        $result = json_decode($response, true);

        //if http error throw exception
        if (floor($info['http_code'] / 100) >= 4) {
            //throw error
            throw new PipedriveApiError('API HTTP Error ' . $info['http_code'] . '. Message ' . $result['error']);
        }
        // return output
        return $result;
    }

    /**
     * Set an option for a cURL transfer
     *
     * @param string $option option
     * @param string $value  value
     *
     * @return object $this this object
     */
    protected function setOpt($option, $value)
    {
        //set cURL transfer option
        curl_setopt($this->curl, $option, $value);
        // return the current object
        return $this;
    }

    /**
     * takes the pipedrive method and turns it into the correct URL endpoint
     * by adding the method and API key
     *
     * @param  string $method Pipedrive method
     * @return object $this   Current Object
     */
    protected function createEndPoint($method, $data = array())
    {
        //create array for api key
        $data['api_token'] = $this->apiKey;
        //make API end point
        $endPoint = $this->url  . '/' . $method . '?' . http_build_query($data);
        //set API endpoint
        $this->setOpt(CURLOPT_URL, $endPoint);
        //return this object
        return $this;
    }

    /**************************************************\

     ALL FOLLOWING METHODS ARE BASED ON :
     https://github.com/php-curl-class/php-curl-class

    \**************************************************/

    /**
     * Loops through post field array and removes any empty arrays
     * if there is an @ symbol at the front of the string we assume it
     * is a file and set up a CURLFile object
     *
     * @param  array $data post fields
     * @return array updated postfields
     */
    protected function postfields($data)
    {
        if (is_array($data)) {
            //if mulitdimensional array
            if ($this->isArrayMultiDim($data)) {
                // build bultidimensial query
                $data = $this->httpBuildMultiQuery($data);
            } else {
                //loop through array
                foreach ($data as $key => $value) {

                    // Fix "Notice: Array to string conversion" when $value in
                    // curl_setopt($ch, CURLOPT_POSTFIELDS, $value) is an array
                    // that contains an empty array.
                    if (is_array($value) && empty($value)) {
                        $data[$key] = '';
                    }
                    // Fix "curl_setopt(): The usage of the @filename API for
                    // file uploading is deprecated. Please use the CURLFile
                    // class instead".
                    elseif (is_string($value) && strpos($value, '@') === 0) {
                        // add file
                        $data[$key] = new CURLFile(substr($value, 1));
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Build multidimenianl query
     * from: https://github.com/php-curl-class/php-curl-class
     *
     * @param  array  $data post data
     * @param  string $key  nested key
     * @return string
     */
    private function httpBuildMultiQuery(array $data, $key = null)
    {
            $query = array();

            if (empty($data)) {
                return $key . '=';
            }
            $isArrayAssoc = $this->isArrayAssoc($data);
            // build
            foreach ($data as $k => $value) {
                if (is_string($value) || is_numeric($value)) {
                    $brackets = $isArrayAssoc ? '[' . $k . ']' : '[]';
                    $query[] = urlencode(is_null($key) ? $k : $key . $brackets) . '=' . rawurlencode($value);
                } elseif (is_array($value)) {
                    $nested = is_null($key) ? $k : $key . '[' . $k . ']';
                    $query[] = $this->httpBuildMultiQuery($value, $nested);
                }
            }

            return implode('&', $query);
        }

    /**
     * From https://github.com/php-curl-class/php-curl-class
     * @param  array   $array
     * @return boolean
     */
    private function isArrayAssoc($array)
    {
        return (bool) count(array_filter(array_keys($array), 'is_string'));
    }

    /**
     * From https://github.com/php-curl-class/php-curl-class
     * @param  array   $array
     * @return boolean
     */
    private function isArrayMultiDim($array)
    {
        if (!is_array($array)) return false;
        return !(count($array) === count($array, COUNT_RECURSIVE));
    }
}
