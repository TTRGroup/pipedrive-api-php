<?php namespace Benhawker\Pipedrive\Library;

use Benhawker\Pipedrive\Exceptions\PipedriveMissingFieldError;

/**
 * Pipedrive Activities Methods
 *
 * Activities are appointments/tasks/events on a calendar that can be
 * associated with a Deal, a Person and an Organization. Activities can
 * be of different type (such as call, meeting, lunch or a custom type
 * - see ActivityTypes object) and can be assigned to a particular User.
 * Note that activities can also be created without a specific date/time.
 *
 */
class Activities
{
    /**
     * Hold the pipedrive cURL session
     * @var Curl Object
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
     * Adds a activity
     *
     * @param  array $data activity details
     * @return array returns details of the activity
     * @throws PipedriveMissingFieldError
     */
    public function add(array $data)
    {

        //if there is no subject or type set chuck error as both of the fields are required
        if (!isset($data['subject']) or !isset($data['type'])) {
            throw new PipedriveMissingFieldError('You must include both a "subject" and "type" field when inserting a note');
        }

        return $this->curl->post('activities', $data);
    }

    /**
     * @param $id
     * @return array returns deleted id
     */
    public function delete($id){
        return $this->curl->delete('activities/'.$id);
    }

    /**
     * Update activity
     * @param $id
     * @param $data
     * @return array
     */
    public function update($id, $data)
    {
        return $this->curl->put('activities/' . $id, $data);
    }

    /**
     * Return activity
     *
     * @param  int   $id pipedrive activity id
     * @return array returns details of activity
     */
    public function getById($id)
    {
        return $this->curl->get('activities/' . $id);
    }

    /**
     * @param array $data
     * @return array returns all activities by data fields
     */
    public function getByUser(array $data)
    {
        return $this->curl->get('activities', $data);
    }

    /**
     * List activities by condition
     *
     * @param array $data
     * @return array return all activities by data fields
     */
    public function getList(array $data)
    {
        return $this->curl->get('activities/list', $data);
    }
}
