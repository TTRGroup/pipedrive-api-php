<?php

namespace Benhawker\Pipedrive\Library;

use Benhawker\Pipedrive\Exceptions\PipedriveApiError;
use Benhawker\Pipedrive\Exceptions\PipedriveMissingFieldError;

/**
 * Pipedrive SearchResults Methods
 * Ordered reference objects, pointing to either Deals, Persons, Organizations, Files or Products.
 */
class SearchResults
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
     * Performs a search across the account and returns SearchResults.
     * @param array $data
     * @return array
     * @throws PipedriveApiError
     * @throws PipedriveMissingFieldError
     */
    public function search(array $data)
    {
        $this->validateSearchTerm($data, 2);
        return $this->curl->get('searchResults', $data);
    }

    /**
     * Performs a search from a specific field's values. Results can be either the
     * distinct values of the field (useful for searching autocomplete field values),
     * or actual items IDs (deals, persons, organizations or products). Works only with the
     * following field types: varchar, varchar_auto, double, address, text, phone, date.
     * @param array $data
     * @return array
     * @throws PipedriveApiError
     * @throws PipedriveMissingFieldError
     */
    public function searchField(array $data)
    {
        $this->validateSearchTerm($data, 3);
        return $this->curl->get('searchResults/field', $data);
    }

    /**
     * Validate that search query has term with min length
     * @param array $data
     * @param int $minLength
     * @throws PipedriveApiError
     * @throws PipedriveMissingFieldError
     */
    private function validateSearchTerm(array $data, $minLength = 2)
    {
        if(!isset($data['term'])){
            throw new PipedriveMissingFieldError('You must include "term" in search method.');
        } else {
            if(strlen($data['term']) < $minLength){
                throw new PipedriveApiError('Search term must be at least '. $minLength .' characters long');
            }
        }
    }
}