<?php
/**
 * Created by PhpStorm.
 * User: plampson
 * Date: 18/05/2016
 * Time: 14:05
 */

namespace Benhawker\Pipedrive\Library;


class Users
{
    /**
     * Hold the pipedrive cURL session
     * @var MPortal\Pipedrive\Library\Curl Curl Object
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
     * Returns a user
     *
     * @param  int   $id pipedrive users id
     * @return array returns detials of a user
     */
    public function getById($id)
    {
        return $this->curl->get('users/' . $id);
    }

    /**
     * Returns a user / people
     *
     * @param  string $name pipedrive users name
     * @return array  returns detials of a user
     */
    public function getByName($name)
    {
        return $this->curl->get('users/find', array('term' => $name,'search_by_email'=>0));
    }
    /**
     * Returns a user / people
     *
     * @param  string $name pipedrive users name
     * @return array  returns detials of a user
     */
    public function getIdByName($name)
    {
        $response=$this->curl->get('users/find', array('term' => $name));
        if($response['data'] === null)
        {
            return false;
        }
        return $response['data'][0]['id'];
    }

    /**
     * @param array $data
     * @return mixed
     * @throws PipedriveMissingFieldError
     */
    public function products(array $data)
    {
        //if there is no id set throw error as it is a required field
        if (!isset($data['id'])) {
            throw new PipedriveMissingFieldError('You must include the "id" of the user when getting products');
        }

        return $this->curl->get('users/' . $data['id'] . '/products');
    }

    /**
     * Updates a user
     *
     * @param  int   $userId pipedrives user Id
     * @param  array $data     new detials of user
     * @return array returns detials of a user
     */
    public function update($userId, array $data = array())
    {
        return $this->curl->put('users/' . $userId, $data);
    }

    /**
     * @param array $data
     * @return mixed
     * @throws PipedriveMissingFieldError
     */
    public function add(array $data)
    {
        //if there is no name set throw error as it is a required field
        if (!isset($data['name'])) {
            throw new PipedriveMissingFieldError('You must include a "name" field when inserting a user');
        }

        return $this->curl->post('users', $data);
    }

    /**
     * Deletes a user
     *
     * @param  int   $userId pipedrives user Id
     * @return array returns detials of a user
     */
    public function delete($userId)
    {
        return $this->curl->delete('users/' . $userId);
    }
}