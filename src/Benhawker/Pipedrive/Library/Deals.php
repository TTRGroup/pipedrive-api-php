<?php namespace Benhawker\Pipedrive\Library;

use Benhawker\Pipedrive\Exceptions\PipedriveMissingFieldError;

/**
 * Pipedrive Deals Methods
 *
 * Deals represent ongoing, lost or won sales to an Organization or to a Person.
 * Each deal has a monetary value and must be placed in a Stage. Deals can be
 * owned by a User, and followed by one or many Users. Each Deal consists of
 * standard data fields but can also contain a number of custom fields. The
 * custom fields can be recognized by long hashes as keys. These hashes can be
 * mapped against DealField.key. The corresponding label for each such custom
 * field can be obtained from DealField.name.
 *
 */
class Deals
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
     * Retrieve deals by status.
     * @param int $start the record to start pagination at
     * @param int $limit the amount of records to return
     */
    public function getByStatus($status, $start = 0, $limit = 10) {
        return $this->curl->get('deals', array(
            "status" => $status,
            "start" => $start,
            "limit" => $limit
        ));
    }

    /**
     * Returns a deal
     *
     * @param  int   $id pipedrive deals id
     * @return array returns detials of a deal
     */
    public function getById($id)
    {
        return $this->curl->get('deals/' . $id);
    }

    /**
     * Returns a deal / deals
     *
     * @param  string $name pipedrive deals title
     * @return array  returns detials of a deal
     */
    public function getByName($name, $personId=null, $orgId=null)
    {
        $params = array('term' => $name);
        if($personId) {
            $params['person_id'] = $personId;
        }
        if($orgId) {
            $params['org_id'] = $orgId;
        }
        return $this->curl->get('deals/find', $params);
    }

    /**
     * Lists products associated with a deal.
     *
     * @param  array $data (id, start, limit)
     * @return array products
     */
    public function products(array $data)
    {
        //if there is no id set throw error as it is a required field
        if (!isset($data['id'])) {
            throw new PipedriveMissingFieldError('You must include the "id" of the deal when getting products');
        }

        return $this->curl->get('deals/' . $data['id'] . '/products');
    }

    /**
     * Retrieves deals matching the timeline criteria provided in the data parameter.
     *
     * @param array $data (start_date, interval, amount, field_key)
     * @return array deals
     **/
    public function timeline($data) {
        if (!isset($data['start_date']) || !isset($data['interval']) || !isset($data['amount']) || !isset($data['field_key'])) {
            throw new PipedriveMissingFieldError('You must include the start_date, interval, amound and field_key when getting deals via timeline.');
        }

        return $this->curl->get('deals/timeline/', $data);
    }

    /**
     * Adds a deal
     *
     * @param  array $data deal detials
     * @return array returns detials of the deal
     */
    public function add(array $data)
    {
        //if there is no title set throw error as it is a required field
        if (!isset($data['title'])) {
            throw new PipedriveMissingFieldError('You must include a "title" field when inserting a deal');
        }

        return $this->curl->post('deals', $data);
    }

    /**
     * Adds a product to a deal
     *
     * @param  int   $dealId deal id
     * @param  array $data deal and product detials
     * @return array returns detials of the deal-product
     * @throws PipedriveMissingFieldError
     */
    public function addProduct($dealId, array $data)
    {
        //if there is no product_id set throw error as it is a required field
        if (!isset($data['product_id'])) {
            throw new PipedriveMissingFieldError('You must include a "pdoruct_id" field when adding a product to a deal');
        }
        //if there is no item_price set throw error as it is a required field
        if (!isset($data['item_price'])) {
            throw new PipedriveMissingFieldError('You must include a "item_price" field when adding a product to a deal');
        }
        //if there is no quantity set throw error as it is a required field
        if (!isset($data['quantity'])) {
            throw new PipedriveMissingFieldError('You must include a "quantity" field when adding a product to a deal');
        }

        return $this->curl->post('deals/' . $dealId . '/products', $data);
    }

    /**
     * Updates a deal
     *
     * @param  int   $dealId pipedrives deal Id
     * @param  array $data   new detials of deal
     * @return array returns detials of a deal
     */
    public function update($dealId, array $data = array())
    {
        return $this->curl->put('deals/' . $dealId, $data);
    }

    /**
     * Moves deal to a new stage
     *
     * @param  int   $dealId  deal id
     * @param  int   $stageId stage id
     * @return array returns detials of the deal
     */
    public function moveStage($dealId, $stageId)
    {
        return $this->curl->put('deals/' . $dealId, array('stage_id' => $stageId));
    }

}
