<?php namespace Benhawker\Pipedrive;

    /**
 * Pipedrive API wrapper class v0.1
 *
 * Author: Ben Hawker (ben@tickettoridegroup.com) 2014
 */

/*
(MIT License)

Copyright (C) 2014 by TTRGroup

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

class Pipedrive
{
    /**
     * API Key
     * @var string
     */
    protected $apiKey;
    /**
     * Protocol (default 'https')
     * @var string
     */
    protected $protocol;
    /**
     * Host Url (default 'api.pipedrive,com')
     * @var string
     */
    protected $host;
    /**
     * API version (default 'v1')
     * @var string
     */
    protected $version;
    /**
     * Hold the Curl Object
     * @var \Benhawker\Pipedrive\Library\Curl Curl Object
     */
    protected $curl;
    /**
     * Placeholder attritube for the pipedrive persons class
     * @var \Benhawker\Pipedrive\Library\Persons Persons Object
     */
    protected $persons;
    /**
     * Placeholder attritube for the pipedrive deals class
     * @var \Benhawker\Pipedrive\Library\Deals Deals Object
     */
    protected $deals;
    /**
     * Placeholder attritube for the pipedrive activities class
     * @var \Benhawker\Pipedrive\Library\Activities Activities Object
     */
    protected $activities;
    /**
     * Placeholder attritube for the pipedrive notes class
     * @var \Benhawker\Pipedrive\Library\Notes Notes Object
     */
    protected $notes;
    /**
     * Placeholder attritube for the pipedrive dealFields class
     * @var \Benhawker\Pipedrive\Library\DealFields DealFields Object
     */
    protected $dealFields;
    /**
     * Placeholder attritube for the pipedrive organizations class
     * @var Organizations Object
     */
    protected $organizations;
    /**
     * Placeholder attritube for the pipedrive products class
     * @var Products Object
     */
    protected $products;
    /**
     * Placeholder attritube for the pipedrive filters class
     * @var Filters Object
     */
    protected $filters;
    /**
     * Placeholder attritube for the pipedrive pipelines class
     * @var Pipelines Object
     */
    protected $pipelines;
    /**
     * Placeholder attritube for the pipedrive stages class
     * @var Filters Object
     */
    protected $stages;

    /**
     * Set up API url and load library classes
     *
     * @param string $apiKey   API key
     * @param string $protocol protocol (default: https)
     * @param string $host     host url (default: api.pipedrive.com)
     * @param string $version  version  (default: v1)
     */
    public function __construct($apiKey = '', $protocol = 'https', $host = 'api.pipedrive.com', $version = 'v1')
    {
        //set var apiKey is essiantial!!
        $this->apiKey   = $apiKey;
        $this->protocol = $protocol;
        $this->host     = $host;
        $this->version  = $version;

        //make API url
        $url = $protocol . '://' . $host . '/' . $version;

        //add curl library and pass the API Url & key to the object
        $this->curl = new Library\Curl($url, $apiKey);

        //add pipedrive classes to the assoicated property
        $this->persons       = new Library\Persons($this);
        $this->deals         = new Library\Deals($this);
        $this->activities    = new Library\Activities($this);
        $this->notes         = new Library\Notes($this);
        $this->dealFields    = new Library\DealFields($this);
        $this->organizations = new Library\Organizations($this);
        $this->products      = new Library\Products($this);
        $this->filters       = new Library\Filters($this);
        $this->pipelines     = new Library\Pipelines($this);
        $this->stages        = new Library\Stages($this);
    }

     /**
     * Sets ApiKey using username and password
     * 
     * @param string $username  Username of pipedrive user
     * @param string $password  Password of pipedrive user
     * @return Api Key
     * @throws Exceptions\PipedriveException
     */
    public function login($username, $password) {
        $params = array("email" => $username, "password" => $password);
        $response = $this->curl()->post("authorizations", $params);
        $errorMessage = "Wrong email/password combination.";

        //Handle response
        if (isset($response["success"]) && $response["success"] == true) {
            if (isset($response["data"]) && isset($response["data"][0]) && isset($response["data"][0]["api_token"])) {
                $this->apiKey = $response["data"][0]["api_token"];
                //Reinitalize class constructor in case of some actions must be made with new ApiKey
                $this->__construct($this->apiKey, $this->protocol, $this->host, $this->version);
                return $this->apiKey;
            }
        } else if (isset($response["error"]) && $response["error"]) {
            $errorMessage = $response["error"];
        }
        throw new Exceptions\PipedriveException($errorMessage);
    }

    /**
     * Returns the Pipedrive cURL Session
     *
     * @return \Benhawker\Pipedrive\Library\Curl
     */
    public function curl()
    {
        return $this->curl;
    }

    /**
     * Returns the Pipedrive Persons Object
     *
     * @return \Benhawker\Pipedrive\Library\Persons
     */
    public function persons()
    {
        return $this->persons;
    }

    /**
     * Returns the Pipedrive Deals Object
     *
     * @return \Benhawker\Pipedrive\Library\Deals
     */
    public function deals()
    {
        return $this->deals;
    }

    /**
     * Returns the Pipedrive Activities Object
     *
     * @return \Benhawker\Pipedrive\Library\Activities
     */
    public function activities()
    {
        return $this->activities;
    }

    /**
     * Returns the Pipedrive Notes Object
     *
     * @return \Benhawker\Pipedrive\Library\Notes
     */
    public function notes()
    {
        return $this->notes;
    }

    /**
     * Returns the Pipedrive DealFields Object
     *
     * @return \Benhawker\Pipedrive\Library\DealFields
     */
    public function dealFields()
    {
        return $this->dealFields;
    }

    /**
     * Returns the Pipedrive Organizations Object
     *
     * @return Organizations Object
     */
    public function organizations()
    {
        return $this->organizations;
    }

    /**
     * Returns the Pipedrive Products Object
     *
     * @return Products Object
     */
    public function products()
    {
        return $this->products;
    }
    
    /**
     * Returns the Pipedrive Filters Object
     *
     * @return Filters Object
     */
    public function filters()
    {
        return $this->filters;
    }
    
    /**
     * Returns the Pipedrive Pipelines Object
     *
     * @return Pipelines Object
     */
    public function pipelines()
    {
        return $this->pipelines;
    }
    
    /**
     * Returns the Pipedrive Stages Object
     *
     * @return Stages Object
     */
    public function stages()
    {
        return $this->stages;
    }
}
