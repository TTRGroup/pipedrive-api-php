<?php namespace Benhawker\Pipedrive\Library;

use Benhawker\Pipedrive\Exceptions\PipedriveException;
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
     * @param  int   $id pipedrive deals id
     * @return array returns details of a deal
     */
    public function getById($id)
    {
        return $this->curl->get('deals/' . $id);
    }

    /**
     * Returns a deal / deals
     *
     * @param  string $name pipedrive deals title
     * @return array  returns details of a deal
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
     * @throws PipedriveMissingFieldError
     */
    public function products(array $data)
    {
        //if there is no id set throw error as it is a required field
        if (!isset($data['id'])) {
            throw new PipedriveMissingFieldError('You must include the "id" of the deal when getting products');
        }

        return $this->curl->get('deals/' . $data['id'] . '/products', $data);
    }

    /**
     * Adds a deal
     *
     * @param  array $data deal details
     * @return array returns details of the deal
     * @throws PipedriveMissingFieldError
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
     * @param  array $data deal and product details
     * @return array returns details of the deal-product
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
     * Update deal attached product.
     * @param $dealId
     * @param array $data
     * @return array
     * @throws PipedriveMissingFieldError
     */
    public function updateProduct($dealId, array $data)
    {
        if(!isset($data['deal_product_id'])){
            throw new PipedriveMissingFieldError('You must include "deal_product_id" field when updating product.');
        }

        if(!isset($data['item_price'])){
            throw new PipedriveMissingFieldError('You must include "item_price" field when updating product.');
        }

        if(!isset($data['quantity'])){
            throw new PipedriveMissingFieldError('You must include "quantity" field when updating product');
        }

        return $this->curl->put('deals/'.$dealId.'/products/'.$data['deal_product_id'], $data);
    }

    /**
     * Updates a deal
     *
     * @param  int   $dealId pipedrives deal Id
     * @param  array $data   new details of deal
     * @return array returns details of a deal
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
     * @return array returns details of the deal
     */
    public function moveStage($dealId, $stageId)
    {
        return $this->curl->put('deals/' . $dealId, array('stage_id' => $stageId));
    }

    /**
     * Delete single deal by deal id
     *
     * @param $dealId
     * @return array
     */

    public function delete($dealId)
    {
        return $this->curl->delete('deals/'.$dealId);
    }


    /**
     * Bulk delete deals
     *
     * @param $ids string Comma separated ids
     * @return mixed
     */
    public function bulkDelete($ids)
    {
        return $this->curl->bulkDelete('deals', array('ids' => $ids));
    }

    /**
     * Get deals timeline
     * 
     */

    public function getDealsTimeline($params)
    {
        if(!isset($params['start_date'])){
            throw new PipedriveMissingFieldError('You must include "start_date" when getting deals timeline');
        }
        if(!isset($params['interval']) || !in_array($params['interval'],['day','week','month','quarter']) ){
            throw new PipedriveMissingFieldError('You must include "interval" when getting deals timeline');
        }
        if(!isset($params['amount'])){
            throw new PipedriveMissingFieldError('You must include "amount" when getting deals timeline');
        }
        if(!isset($params['field_key'])){
            throw new PipedriveMissingFieldError('You must include "field_key" when getting deals timeline');
        }

        return $this->curl->get('deals/timeline', $params);
    }

    /**
     * Get all deals by filter id
     * @param $params
     * @return array
     */
    public function getAllDeals($params)
    {
        return $this->curl->get('deals', $params);
    }

    /**
     * List all activities associated to deal.
     * @param $dealId
     * @param array $params
     * @return array
     */
    public function listActivities($dealId, $params = [])
    {
        return $this->curl->get('deals/' . $dealId . '/activities', $params);
    }

    /**
     * Add follower to a deal.
     * @param $dealId
     * @param $userId
     * @return array
     */
    public function addFollower($dealId, $userId)
    {
        return $this->curl->post('deals/' . $dealId . '/followers', [
            'id' => $dealId,
            'user_id' => $userId
        ]);
    }

    /**
     * Delete follower from a deal.
     * @param $dealId
     * @param $userId
     * @return array
     */
    public function deleteFollower($dealId, $userId)
    {
        return $this->curl->delete('deals/' . $dealId . '/followers/' . $userId);
    }
}
