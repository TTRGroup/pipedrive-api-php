<?php

namespace Benhawker\Pipedrive\Library;

use Benhawker\Pipedrive\Exceptions\PipedriveMissingFieldError;

/**
 * Pipedrive OrganizationFields Methods
 *
 */
class OrganizationFields
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
     * Returns all organization fields
     *
     * @return array returns all organizationFields
     */
    public function getAll()
    {
        return $this->curl->get('organizationFields');
    }

    /**
     * Returns a organization field
     *
     * @param  int   $id pipedrive organizationField id
     * @return array returns details of a organizationField
     */
    public function getById($id)
    {
        return $this->curl->get('organizationFields/' . $id);
    }
}
