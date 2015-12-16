<?php
namespace Benhawker\Pipedrive\Library;

use Benhawker\Pipedrive\Pipedrive;

/**
 * Pipedrive Files Methods
 *
 * Files are documents of any kind (images, spreadsheets, text files, etc) that are uploaded to Pipedrive, and usually
 * associated with a particular Deal, Person or Organization. Note that the API currently does not support uploading
 * files although it lets you retrieve file meta info along retrieve a download URL where the file can be downloaded
 * using a standard HTTP GET request.
 */
class Files
{
    /**
     * Hold the pipedrive cURL session
     * @var \Benhawker\Pipedrive\Library\Curl Curl Object
     */
    protected $curl;

    /**
     * Initialise the object load master class
     */
    public function __construct(Pipedrive $master)
    {
        //associate curl class
        $this->curl = $master->curl();
    }

    /**
     * Returns all files
     *
     * @param int $start Pagination start
     * @param int $limit Items shown per page
     * @param bool|false $include_deleted_files When enabled, the list of files will also include deleted files.
     * Please note that trying to download these files will not work.
     * @param string $sort Field names and sorting mode separated by comma (field_name_1 ASC, field_name_2 DESC).
     *                     Only first-level field keys are supported (no nested keys).
     *                     Supported fields: id, user_id, deal_id, person_id, org_id, product_id, add_time, update_time,
     *                     file_name, file_type, file_size, comment.
     * @return array returns detials of a deal
     */
    public function getAll($start, $limit, $include_deleted_files = false, $sort = 'id')
    {
        $args = [
            'start' => $start,
            'limit' => $limit,
            'include_deleted_files' => $include_deleted_files,
            'sort' => $sort,
        ];

        return $this->curl->get('files/?' . implode('&', $args));
    }

    /**
     * Returns data about a specific file.
     *
     * @param  int   $id pipedrive file id
     * @return array returns data of a file
     */
    public function getById($id)
    {
        return $this->curl->get('files/' . $id);
    }

    /**
     * Initializes a file download.
     *
     * @param  int   $id pipedrive file id
     * @return file file
     */
    public function downloadById($id)
    {
        return $this->curl->get('files/' . $id . '/download');
    }


    /**
     * Lets you upload one or more files, and associate them with a Deal, a Person, an Organization, an Activity or a
     * Product. IMPORTANT: This endpoint uses multipart/form-data encoding and cannot be tested using the documentation
     * page directly. However, it is a supported action within our NodeJS based API client as of versions 1.5.1. You can
     * use that to test out the file upload.
     *
     * @param $file
     * @param null $deal_id
     * @param null $person_id
     * @param null $org_id
     * @param null $product_id
     * @param null $activity_id
     * @param null $note_id
     * @return array
     */
    public function addFiles(
        $file,
        $deal_id = null,
        $person_id = null,
        $org_id = null,
        $product_id = null,
        $activity_id = null,
        $note_id = null
    )
    {
        $data = [
            'file' => $file,
            'deal_id' => $deal_id,
            'person_id' => $person_id,
            'org_id' => $org_id,
            'product_id' => $product_id,
            'activity_id' => $activity_id,
            'note_id' => $note_id,
        ];

        return $this->curl->post('/files/', $data);
    }
}
