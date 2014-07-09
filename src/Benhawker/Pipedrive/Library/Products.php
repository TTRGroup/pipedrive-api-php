<?php namespace Benhawker\Pipedrive\Library;

/**
 * Pipedrive Products Methods
 *
 * Products are the goods or services you are dealing with.
 * Each product can have N different price points -
 * first, each Product can have a price in N different currencies,
 * and secondly, each Product can have N variations of itself,
 * each having N prices different currencies.
 * Note that only one price per variation per currency is supported.
 * Products can be instantiated to Deals.
 * In the context of instatiation, a custom price, quantity,
 * duration and discount can be applied.
 *
 */
class Products
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
        //associate curl class
        $this->curl = $master->curl();
    }

    /**
     * Returns a product
     *
     * @param  int   $id pipedrive products id
     * @return array returns detials of a product
     */
    public function getById($id)
    {
        return $this->curl->get('products/' . $id);
    }

    /**
     * Returns a product
     *
     * @param  string $name pipedrive products name
     * @param  array  $data (currency, start, limit)
     * @return array  returns detials of a product
     */
    public function getByName($name, array $data = array())
    {
        if (isset($data['pagination']) && $data['pagination'] == false) {
          return $this->getByNameNoPagination($name, $data);
        }

        $data['term'] = $name;

        return $this->curl->get('products/find', $data);
    }

    /**
     * Returns a product without pagination
     *
     * @param  string $name pipedrive products name
     * @param  array  $data (currency, start, limit)
     * @return array  returns detials of a product
     */
    private function getByNameNoPagination($name, array $data = array())
    {
        $data['term'] = $name;

        $response = $this->curl->get('products/find', $data);

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
     * Returns all products
     *
     * @param  array $data (start, limit)
     * @return array returns detials of all products
     */
    public function getAll(array $data = array())
    {
        if (isset($data['pagination']) && $data['pagination'] == false) {
          return $this->getAllNoPagination($data);
        }

        return $this->curl->get('products/', $data);
    }

    /**
     * Returns all products without pagination
     *
     * @param  array $data (start, limit)
     * @return array returns detials of all products
     */
    private function getAllNoPagination(array $data = array())
    {
        $response = $this->curl->get('products/', $data);

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
}
