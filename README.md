PipeDrive-api-php
============

PHP API client library for the PipeDrive CRM

Only some basic functionality required for my current project has been added. However the basic blocks are to make use of the whole API including file uploading.

API Docs can be found here: https://developers.pipedrive.com/v1 (with relevant information on field names)

Recommend you install this library through composer. https://packagist.org/packages/benhawker/pipedrive

to install jiust include in your composer json file:  "benhawker/pipedrive": "dev-master" and update composer.

Example:

use Benhawker\Pipedrive\Pipedrive;

$pipedrive = new Pipedrive('0deceea867202fcf3889cd507ef93a91789f7e3a');

//add user
$person['name'] = 'John Smith';

$person = $pipedrive->persons()->add($person);

//add note to user
$note['content']   = 'example note';
$note['person_id'] = $person['data']['id'];

$pipedrive->notes()->add($note);

//add deal to user
deal['title']      = 'example title';
$deal['stage_id']  = 8;
$deal['person_id'] = $person['data']['id'];

$pipedrive->deals()->add($deal);

//add activity
$activity = array(
                    'subject' => 'Example send brochure',
                    'type' => 'send-brochure',
                    'person_id' => 17686,
                    'user_id' => 190870,
                    'deal_id' => 88,
                    'due_date' => date('Y-m-d')
                    );

$pipedrive->activities()->add($activity);