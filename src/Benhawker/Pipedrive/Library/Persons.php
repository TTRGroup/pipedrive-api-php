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
    +     * Returns a person / people
    +     *
    +     * @param  string $email pipedrive persons email
    +     * @return array  returns details of a person
    +     */
    public function getByEmail($email)
    {
        return $this->curl->get('persons/find', array('term' => $email, 'search_by_email' => 1));
    }


    /**
     * Lists deals associated with a person.
     *
     * @param  array $data (id, start, limit)
     * @return array deals
     * @throws PipedriveMissingFieldError
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
     * @return array products
     * @throws PipedriveMissingFieldError
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
     * @throws PipedriveMissingFieldError
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
     * Get all persons by filter id
     * @param $params
     * @return array
     */
    public function getAll($params)
    {
        return $this->curl->get('persons', $params);
    }

    /**
     * Deletes a person
     *
     * @param  int   $personId pipedrives person Id
     * @return array returns detials of a person
     */
    public function delete($personId)
    {
        return $this->curl->delete('persons/' . $personId);
    }

    public function listFollowers($personId)
    {
        return $this->curl->get('persons/' . $personId . '/followers');
    }

    /**
     * @param $personId
     * @param $followerId
     * @return array
     */
    public function addFollower($personId, $followerId)
    {
        return $this->curl->post('persons/' . $personId . '/followers', [
            'id' => $personId,
            'user_id' => $followerId
        ]);
    }

    /**
     * @param $personId
     * @param $followerId
     * @return array
     */
    public function deleteFollower($personId, $followerId)
    {
        return $this->curl->delete('persons/' . $personId . '/followers/' . $followerId);
    }
}
