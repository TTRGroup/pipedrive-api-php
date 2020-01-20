<?php namespace Benhawker\Pipedrive\Library;

use Benhawker\Pipedrive\Exceptions\PipedriveMissingFieldError;

/**
 * Pipedrive Users Methods
 * 
 * Users are people with access to your Pipedrive account. 
 * A user may belong to one or many Pipedrive accounts, so deleting
 * a user from one Pipedrive account will not remove the user from
 * the data store if he/she is connected to multiple accounts.
 * Users should not be confused with Persons.
 *
 */
class Users
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
     * Returns all users
     *
     * @return array returns detials of all organizations
     */
    public function getAll()
    {
        return $this->curl->get('users/');
    }

}
