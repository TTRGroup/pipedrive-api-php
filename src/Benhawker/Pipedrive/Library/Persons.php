<?php namespace Benhawker\Pipedrive\Library;

use Benhawker\Pipedrive\Exceptions\PipedriveMissingFieldError;

/**
 * Pipedrive Persons Methods
 *
 * Persons are your contacts, the customers you are doing Deals with.
 * Each Person can belong to an Organization.
 * Persons should not be confused with Users.
 *
 */
class Persons
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
     * Returns a person
     *
     * @param  int   $id pipedrive persons id
     * @return array returns detials of a person
     */
    public function getById($id)
    {
        return $this->curl->get('persons/' . $id);
    }

    /**
     * Returns a person / people
     *
     * @param  string $name pipedrive persons name
     * @return array  returns detials of a person
     */
    public function getByName($name)
    {
        return $this->curl->get('persons/find', array('term' => $name));
    }

    /**
     * Lists deals associated with a person.
     *
     * @param  array $data (id, start, limit)
     * @return array deals
     */
    public function deals(array $data)
    {
        //if there is no name set throw error as it is a required field
        if (!isset($data['id'])) {
            throw new PipedriveMissingFieldError('You must include the "id" of the person when getting deals');
        }

        return $this->curl->get('persons/' . $data['id'] . '/deals');
    }

    /**
     * Updates a person
     *
     * @param  int   $personId pipedrives person Id
     * @param  array $data     new detials of person
     * @return array returns detials of a person
     */
    public function update($personId, array $data = array())
    {
        return $this->curl->put('persons/' . $personId, $data);
    }

    /**
     * Adds a person
     *
     * @param  array $data persons detials
     * @return array returns detials of a person
     */
    public function add(array $data)
    {
        //if there is no name set throw error as it is a required field
        if (!isset($data['name'])) {
            throw new PipedriveMissingFieldError('You must include a "name" feild when inserting a person');
        }

        return $this->curl->post('persons', $data);
    }
}
