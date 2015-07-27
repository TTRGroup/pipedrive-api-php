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
     * Returns a person
     *
     * @param  int   $id ID of the person
     * @return array details of the person
     */
    public function getById($personId)
    {
        return $this->curl->get('persons/' . $personId);
    }

    /**
     * Find persons by their name
     *
     * @param  string $name Search term to look for
     * @return array  found persons
     */
    public function getByName($name)
    {
        return $this->curl->get('persons/find', array('term' => $name));
    }

    /**
     * Lists deals associated with a person.
     *
     * @param  array $data (id, start, limit)
     * @return array associated deals
     */
    public function deals(array $data)
    {
        //if there is no id set throw error as it is a required field
        if (!isset($data['id'])) {
            throw new PipedriveMissingFieldError('You must include the "id" of the person when getting deals');
        }

        return $this->curl->get('persons/' . $data['id'] . '/deals');
    }

    /**
     * Lists products associated with a person.
     *
     * @param  array $data (id, start, limit)
     * @return array associated products
     */
    public function products(array $data)
    {
        //if there is no id set throw error as it is a required field
        if (!isset($data['id'])) {
            throw new PipedriveMissingFieldError('You must include the "id" of the person when getting products');
        }

        return $this->curl->get('persons/' . $data['id'] . '/products');
    }

    /**
     * Updates a person
     *
     * @param  int   $personId ID of the person
     * @param  array $data     new details for the person
     * @return array details of the person
     */
    public function update($personId, array $data = array())
    {
        return $this->curl->put('persons/' . $personId, $data);
    }

    /**
     * Adds a person
     *
     * @param  array $data details for the person
     * @return array details of the person
     */
    public function add(array $data)
    {
        //if there is no name set throw error as it is a required field
        if (!isset($data['name'])) {
            throw new PipedriveMissingFieldError('You must include a "name" field when inserting a person');
        }

        return $this->curl->post('persons', $data);
    }

    /**
     * Deletes a person
     *
     * @param  int   $personId ID of the person
     * @return array details of the person
     */
    public function delete($personId)
    {
        return $this->curl->delete('persons/' . $personId);
    }
}
