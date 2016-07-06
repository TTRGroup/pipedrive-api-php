<?php

namespace Benhawker\Pipedrive\Library;

/**
 * Pipedrive Stages Methods
 *
 * Stage is a logical component of a Pipeline, and essentially a bucket that
 * can hold a number of Deals. In the context of the Pipeline a stage belongs
 * to, it has an order number which defines the order of stages in that 
 * Pipeline.
 *
 */
class Stages {

    /**
     * Hold the pipedrive cURL session
     * @var \Benhawker\Pipedrive\Library\Curl Curl Object
     */
    protected $curl;

    /**
     * Initialise the object load master class
     */
    public function __construct(\Benhawker\Pipedrive\Pipedrive $master) {
        //associate curl class
        $this->curl = $master->curl();
    }

    /**
     * Returns data about a specific stage
     *
     * @param  int   $id pipedrive stage id
     * @return array returns detials of a stage
     */
    public function getById($id) {
        return $this->curl->get('stages/' . $id);
    }

    /**
     * Returns data about all stages
     *
     * @return array  returns detials of all stages
     */
    public function get() {
        return $this->curl->get('stages');
    }

}
