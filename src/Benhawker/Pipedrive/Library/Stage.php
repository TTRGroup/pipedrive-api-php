<?php namespace Benhawker\Pipedrive\Library;

/**
 * Pipedrive Stage Methods
 *
 * Stage is a logical component of a Pipeline, and essentially a bucket that can hold a number 
 * of Deals. In the context of the Pipeline a stage belongs to, it has an order number which 
 * defines the order of stages in that Pipeline.
 */
class Stage 
{
    /**
     * Hold the pipedrive cURL session
     * @var \Benhawker\Pipedrive\Library\Curl Curl Object
     */
    protected $curl;

    /**
     * Initialise the object load master class
     */
    public function __construct(\Benhawker\Pipedrive\Pipedrive $master)
    {
        //associate curl class
        $this->curl = $master->curl();
    }

    public function getDeals($data) {
        //if there is no id set throw error as it is a required field
        if (!isset($data['id'])) {
            throw new PipedriveMissingFieldError('You must include the "id" of the stage when getting deals');
        }
        $stageId = intval($data['id']);
        unset($data['id']);

        $this->curl->get("stage/" . $stageId . "/deals", $data);
    }
}
