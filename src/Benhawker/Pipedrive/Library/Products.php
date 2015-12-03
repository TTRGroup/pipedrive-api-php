<?php namespace Benhawker\Pipedrive\Library;

use Benhawker\Pipedrive\Exceptions\PipedriveMissingFieldError;

/**
 * Pipedrive Products Methods
 *
 * Products are the goods or services you are dealing with.
 * Each product can have N different price points - first, each Product can
 * have a price in N different currencies, and secondly, each Product can
 * have N variations of itself, each having N prices different currencies.
 * Note that only one price per variation per currency is supported.
 * Products can be instantiated to Deals. In the context of instantiation,
 * a custom price, quantity, duration and discount can be applied.
 */
class Products
{
    /**
     * Hold the pipedrive cURL session
     * @var \Benhawker\Pipedrive\Library\Curl Curl Object
     */
    protected $curl;

    /**
     * Initialize the object load master class
     */
    public function __construct(\Benhawker\Pipedrive\Pipedrive $master)
    {
        //associate curl class
        $this->curl = $master->curl();
    }

    /**
     * Returns a product / products
     *
     * @param  string $name pipedrive product name
     * @return array  returns details of a product
     */
    public function getByName($name)
    {
        $params = array('term' => $name);
        return $this->curl->get('products/find', $params);
    }

}
