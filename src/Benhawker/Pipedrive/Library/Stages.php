<?php
/**
 * Stages.php
 * Date: 09/01/2018
 * Time: 15:21
 */

namespace Benhawker\Pipedrive\Library;


class Stages
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

    /**
     * If pipeline_id is ommited,
     * all stages from all pipelines will be returned.
     *
     * @param null $pipeline_id
     * @return array
     */
    public function getAll($pipeline_id = null)
    {
        return $this->curl->get('stages', ['pipeline_id' => null]);
    }
    
    /**
     * Returns a stage
     *
     * @param  int $id pipedrive stage id
     * @return array returns details of a stage
     */
    public function getById($id)
    {
        return $this->curl->get('stages/' . $id);
    }

    /**
     * Get all deals in stage
     *
     * @param $stage_id
     * @param array $params
     * @return array
     */
    public function getDealsInStage($stage_id, $params = [])
    {
        return $this->curl->get('stages/' . $stage_id . '/deals', $params);
    }

}