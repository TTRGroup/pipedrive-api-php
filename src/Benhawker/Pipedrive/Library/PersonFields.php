<?php namespace Benhawker\Pipedrive\Library;

use Benhawker\Pipedrive\Exceptions\PipedriveMissingFieldError;

/**
 * Pipedrive PersonFields Methods
 *
 * PersonFields represent the near-complete schema for a Person in the context of the company of the authorized user.
 * Each company can have a different schema for their Persons, with various custom fields. In the context of using
 * PersonFields as a schema for defining the data fields of a Person, it must be kept in mind that some types of custom
 * fields can have additional data fields which are not separate PersonFields per se. Such is the case with monetary,
 * daterange and timerange fields – each of these fields will have one additional data field in addition to the one
 * presented in the context of PersonFields. For example, if there is a monetary field with the key 'ffk9s9' stored on
 * the account, 'ffk9s9' would hold the numeric value of the field, and 'ffk9s9_currency' would hold the ISO currency
 * code that goes along with the numeric value. To find out which data fields are available, fetch one Person and list
 * its keys.
 *
 */
class PersonFields
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
     * Returns all person fields
     *
     * @return array returns all personFields
     */
    public function getAll()
    {
        return $this->curl->get('personFields');
    }

    /**
     * Returns a deal field
     *
     * @param  int   $id pipedrive personField id
     * @return array returns details of a personField
     */
    public function getById($id)
    {
        return $this->curl->get('personFields/' . $id);
    }

    /**
     * Adds a dealField
     *
     * @param  array $data deal field details
     * @return array returns details of the personField
     */
    public function add(array $data)
    {
        //if there is no name set throw error as it is a required field
        if (!isset($data['name'])) {
            throw new PipedriveMissingFieldError('You must include a "name" field when inserting a personField');
        } elseif (!isset($data['field_type'])) {
            throw new PipedriveMissingFieldError('You must include a "field_type" field when inserting a personField');
        }

        return $this->curl->post('personFields', $data);
    }
}