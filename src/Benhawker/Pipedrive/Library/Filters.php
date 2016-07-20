<?php namespace Benhawker\Pipedrive\Library;

use Benhawker\Pipedrive\Exceptions\PipedriveMissingFieldError;

/**
 * Pipedrive Filters Methods
 *
 */
class Filters
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
     * Returns a filter
     *
     * @param  int   $id filter id
     * @return array returns details of filter
     */
    public function getById($id)
    {
        return $this->curl->get('filters/' . $id);
    }

    /**
     * Add new filter
     *
     * @param  array $data filter details
     * @return array returns details of filter
     * @throws PipedriveMissingFieldError
     */
    public function add(array $data)
    {
        //if there is no title set throw error as it is a required field
        if (!isset($data['name']) || !isset($data['conditions'])) {
            throw new PipedriveMissingFieldError('Missing required field name/conditions');
        }

        return $this->curl->post('filters', $data);
    }

    /**
     * Updates a deal
     *
     * @param $filterId
     * @param  array $data new details of filter
     * @return array returns details of filter
     * @internal param int $filterId
     */
    public function update($filterId, array $data = array())
    {
        return $this->curl->put('filters/' . $filterId, $data);
    }


    /**
     * Delete single filter by filter id
     *
     * @param $filterId
     * @return array
     */

    public function delete($filterId)
    {
        return $this->curl->delete('filters/'.$filterId);
    }


    /**
     * Bulk delete filters
     *
     * @param $ids comma separated ids
     * @return mixed
     */
    public function bulkDelete($ids)
    {
        return $this->curl->bulkDelete('filters', array('ids' => $ids));
    }

    /**
     * Get all filters by filter type
     * @param $type
     * @return array
     * @throws PipedriveMissingFieldError
     * @internal param $params
     */
    public function getAll($type)
    {
        $supportedTypes = ['deals', 'org', 'people', 'products'];
        if(!in_array($type, $supportedTypes)){
            throw new PipedriveMissingFieldError("Unrecognized filter type. Supported types are:" . implode(', ', $supportedTypes));
        }
        return $this->curl->get('filters', $type);
    }

}
