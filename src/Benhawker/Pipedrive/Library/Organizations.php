<?php namespace Benhawker\Pipedrive\Library;

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
     * Hold the data of the response
     */
    private $response = array();

    /**
     * Initialise the object load master class
     */
    public function __construct(\Benhawker\Pipedrive\Pipedrive $master)
    {
        // associate curl class
        $this->curl = $master->curl();
    }

    /**
     * Returns an organization
     *
     * @param  int   $id pipedrive organizations id
     * @return array returns detials of a organization
     */
    public function getById($id)
    {
        return $this->curl->get('organizations/' . $id);
    }

    /**
     * Returns an organization
     *
     * @param  string $name pipedrive organizations name
     * @param  array  $data (start, limit)
     * @return array  returns detials of a organization
     */
    public function getByName($name, array $data = array())
    {
        if (isset($data['pagination']) && $data['pagination'] == false) {
          return $this->getByNameNoPagination($name, $data);
        }

        $data['term'] = $name;

        return $this->curl->get('organizations/find', $data);
    }

    /**
     * Returns an organization without pagination
     *
     * @param  string $name pipedrive organizations name
     * @param  array  $data (start, limit)
     * @return array  returns detials of a organization
     */
    private function getByNameNoPagination($name, array $data = array())
    {
        $data['term'] = $name;

        $response = $this->curl->get('organizations/find', $data);

        if ($response['success'] && $response['data']) {
            array_push($this->response, $response['data']);

            $pagination = $response['additional_data']['pagination'];

            if (!isset($data['limit']) && $pagination['more_items_in_collection']) {
                $data['start'] = $pagination['start'] + $pagination['limit'];
                return $this->getByNameNoPagination($name, $data);
            }
        }

        $output['data'] = count($this->response) ? $this->response[0] : $this->response;

        return $output;
    }

    /**
     * Returns all organizations
     *
     * @param  array $data (filter_id, start, limit, sort_by, sort_mode)
     * @return array returns detials of all organizations
     */
    public function getAll(array $data = array())
    {
        if (isset($data['pagination']) && $data['pagination'] == false) {
          return $this->getAllNoPagination($data);
        }

        return $this->curl->get('organizations/', $data);
    }

    /**
     * Returns all organizations without pagination
     *
     * @param  array $data (filter_id, start, limit, sort_by, sort_mode)
     * @return array returns detials of all organizations
     */
    private function getAllNoPagination(array $data = array())
    {
        $response = $this->curl->get('organizations/', $data);

        if ($response['success'] && $response['data']) {
            array_push($this->response, $response['data']);

            $pagination = $response['additional_data']['pagination'];

            if (!isset($data['limit']) && $pagination['more_items_in_collection']) {
                $data['start'] = $pagination['start'] + $pagination['limit'];
                return $this->getAllNoPagination($data);
            }
        }

        $output['data'] = count($this->response) ? $this->response[0] : $this->response;

        return $output;
    }

    /**
     * Updates an organization
     *
     * @param  int   $organizationId  pipedrives organization Id
     * @param  array $data  new detials of organization
     * @return array returns detials of a organization
     */
    public function update($organizationId, array $data = array())
    {
        return $this->curl->put('organizations/' . $organizationId, $data);
    }

    /**
     * Adds a organization
     *
     * @param  array $data organizations detials
     * @return array returns detials of a organization
     */
    public function add(array $data)
    {
        //if there is no name set throw error as it is a required field
        if (!isset($data['name'])) {
            throw new PipedriveMissingFieldError('You must include a "name" field when inserting an organization');
        }

        return $this->curl->post('organizations', $data);
    }
}
