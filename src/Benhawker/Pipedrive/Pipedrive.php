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
     * @var Curl Object
     */
    protected $curl;
    /**
     * Placeholder attritube for the pipedrive persons class
     * @var Persons Object
     */
    protected $persons;
    /**
     * Placeholder attritube for the pipedrive deals class
     * @var Deals Object
     */
    protected $deals;
    /**
     * Placeholder attritube for the pipedrive activities class
     * @var Activities Object
     */
    protected $activities;
    /**
     * Placeholder attritube for the pipedrive notes class
     * @var Notes Object
     */
    protected $notes;
    /**
     * Placeholder attritube for the pipedrive dealFields class
     * @var Notes Object
     */
    protected $dealFields;

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
        $this->persons    = new Library\Persons($this);
        $this->deals      = new Library\Deals($this);
        $this->activities = new Library\Activities($this);
        $this->notes      = new Library\Notes($this);
        $this->dealFields = new Library\DealFields($this);
    }

    /**
     * Returns the Pipedrive cURL Session
     *
     * @return Curl Object
     */
    public function curl()
    {
        return $this->curl;
    }

    /**
     * Returns the Pipedrive Persons Object
     *
     * @return Persons Object
     */
    public function persons()
    {
        return $this->persons;
    }

    /**
     * Returns the Pipedrive Deals Object
     *
     * @return Deals Object
     */
    public function deals()
    {
        return $this->deals;
    }

    /**
     * Returns the Pipedrive Activities Object
     *
     * @return Activities Object
     */
    public function activities()
    {
        return $this->activities;
    }

    /**
     * Returns the Pipedrive Notes Object
     *
     * @return Notes Object
     */
    public function notes()
    {
        return $this->notes;
    }

    /**
     * Returns the Pipedrive DealFields Object
     *
     * @return Activities Object
     */
    public function dealFields()
    {
        return $this->dealFields;
    }
}
