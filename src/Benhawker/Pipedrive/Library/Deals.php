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
     * Updates a deal
     *
     * @param  int   $dealId pipedrives deal Id
     * @param  array $data   new detials of deal
     * @return array returns detials of a deal
     */
    public function update($dealId, array $data = array())
    {
        return $this->curl->put('deals' . $dealId, $data);
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

    /**
     * Adds a product to the deal
     *
     * @param  int   $dealId  deal id
     * @param  array $data product detials
     * @return array returns detials of the adding of a product
     */
    public function addProduct($dealId, array $data = array())
    {
        //if there is no product_id set throw error as it is a required field
        if (!isset($data['product_id'])) {
            throw new PipedriveMissingFieldError('You must include a "product_id" field when adding a product');
        }
        //if there is no item_price set throw error as it is a required field
        if (!isset($data['item_price'])) {
            throw new PipedriveMissingFieldError('You must include an "item_price" field when adding a product');
        }
        //if there is no amount set throw error as it is a required field
        if (!isset($data['amount'])) {
            throw new PipedriveMissingFieldError('You must include an "amount" field when adding a product');
        }

        return $this->curl->post('deals/' . $dealId . '/products', $data);
    }

}
