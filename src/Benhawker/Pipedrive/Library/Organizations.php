<?php namespace Benhawker\Pipedrive\Library;

use Benhawker\Pipedrive\Exceptions\PipedriveMissingFieldError;

/**
 * Pipedrive Organizations Methods
 *
 * Organizations are companies and other kinds of organizations you are making 
 * Deals with. Persons can be associated with organizations so that each 
 * organization can contain one or more Persons.
 *
 */
class Organizations
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
     * Returns an organization
     *
     * @param  int   $organizationId ID of the organization
     * @return array details of the organization
     */
    public function getById($organizationId)
    {
        return $this->curl->get('organizations/' . $organizationId);
    }

	/**
     * Find organizations by name
     *
     * @param  string $name search term to look for
     * @param  array  $data (start, limit)
     * @return array  found organizations
     */
    public function getByName($name, array $data = array())
    {
        $data['term'] = $name;
        return $this->curl->get('organizations/find', $data);
    }
    
      
    /**
     * Returns all organizations
     *
     * @param  array $data (filter_id, start, limit, sort_by, sort_mode)
     * @return array all organizations
     */
    public function getAll(array $data = array())
    {
        return $this->curl->get('organizations/', $data);
    }
    
    /**
     * Lists deals associated with an organization.
     *
     * @param  array $data (id, start, limit)
     * @return array associated deals
     */
    public function deals(array $data)
    {
        //if there is no name set throw error as it is a required field
        if (!isset($data['id'])) {
            throw new PipedriveMissingFieldError('You must include the "id" of the organization when getting deals');
        }
        return $this->curl->get('organizations/' . $data['id'] . '/deals');
    }

    /**
     * Updates an organization
     *
     * @param  int   $organizationId ID of the organization
     * @param  array $data     new details for the organization
     * @return array details of the organization
     */
    public function update($organizationId, array $data = array())
    {
        return $this->curl->put('organizations/' . $organizationId, $data);
    }

    /**
     * Adds a new organization
     *
     * @param  array $data details of the organization
     * @return array details of the organization
     */
    public function add(array $data)
    {
        //if there is no name set throw error as it is a required field
        if (!isset($data['name'])) {
            throw new PipedriveMissingFieldError('You must include a "name" field when inserting an organization');
        }

        return $this->curl->post('organizations', $data);
    }

    /**
     * Deletes an organization
     *
     * @param  int   $organizationId ID of the organization
     * @return array details of the organization
     */
    public function delete($organizationId)
    {
        return $this->curl->delete('organizations/' . $organizationId);
    }
}
