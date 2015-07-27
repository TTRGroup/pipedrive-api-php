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
     * Returns a deal
     *
     * @param  int   $id ID of the deal
     * @return array details of a deal
     */
    public function getById($dealId)
    {
        return $this->curl->get('deals/' . $dealId);
    }

    /**
     * Find deals by name
     *
     * @param  string $name search term to look for
     * @return array  found deals
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
     * @return array associated products
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
     * Adds a deal
     *
     * @param  array $data details of the deal
     * @return array details of the deal
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
     * @param  int   $dealId ID of the deal
     * @param  array $data deal and product details
     * @return array details of the deal-product
     * @throws PipedriveMissingFieldError
     */
    public function addProduct($dealId, array $data)
    {
        //if there is no product_id set throw error as it is a required field
        if (!isset($data['product_id'])) {
            throw new PipedriveMissingFieldError('You must include a "product_id" field when adding a product to a deal');
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
     * @param  int   $dealId ID of the deal
     * @param  array $data   new details of the deal
     * @return array details of the deal
     */
    public function update($dealId, array $data = array())
    {
        return $this->curl->put('deals/' . $dealId, $data);
    }

    /**
     * Moves deal to a new stage
     *
     * @param  int   $dealId  ID of the deal
     * @param  int   $stageId ID of the stage
     * @return array details of the deal
     */
    public function moveStage($dealId, $stageId)
    {
        return $this->curl->put('deals/' . $dealId, array('stage_id' => $stageId));
    }

}
