<?php

namespace Benhawker\Pipedrive\Library;


/**
 * Pipedrive Filters Methods
 *
 * Each filter is essentially a set of data validation conditions. A filter of
 * the same kind can be applied when fetching list of Deals, Persons,
 * Organizations, Products or Deals in the context of a Pipeline. 
 * When applied, only items matching the conditions of the filter are returned. 
 * Detailed definitions of filter conditions and additional functionality is
 * not yet available.
 *
 */
class Filters {

    /**
     * Hold the pipedrive cURL session
     * @var \Benhawker\Pipedrive\Library\Curl Curl Object
     */
    protected $curl;

    /**
     * Initialise the object load master class
     */
    public function __construct(\Benhawker\Pipedrive\Pipedrive $master) {
        //associate curl class
        $this->curl = $master->curl();
    }

    /**
     * Returns a filter
     *
     * @param  int   $id Pipedrive filter id
     * @return array returns detials of a filter
     */
    public function getById($id) {
        $id = (int) $id;
        return $this->curl->get('filters/' . $id);
    }

    /**
     * Returns a filter / filters
     *
     * @param  string $type ["deals", "org", "people", "products"]
     * @return array  returns detials of a filters
     */
    public function getByType($type = false) {
        $params = array();
        if ($type != false) {
            $params["type"] = $type;
        }
        return $this->curl->get("filters", $params);
    }

}
