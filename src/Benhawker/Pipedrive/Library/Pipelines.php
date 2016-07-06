<?php

namespace Benhawker\Pipedrive\Library;

/**
 * Pipedrive Pipelines Methods
 *
 * Pipelines are essentially ordered collections of Stages.
 *
 */
class Pipelines {

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
     * Returns data about a specific pipeline. Also returns the summary of the
     * deals in this pipeline across its stages.
     * 
     * @param  int   $id pipedrive persons id
     * @return array returns detials of a pipeline
     */
    public function getById($id) {
        return $this->curl->get('pipelines/' . $id);
    }

    /**
     * Returns data about all pipelines
     * 
     * @return array returns detials of a pipelines
     */
    public function get() {
        return $this->curl->get('pipelines');
    }

}
