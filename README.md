PipeDrive-api-php
=================

PHP API client library for the PipeDrive CRM.

Only some basic functionality required for my current project has been added. However the basic blocks are to make use of the whole API including file uploading.

Recommend you install this library through composer: https://packagist.org/packages/benhawker/pipedrive

    composer require benhawker/pipedrive dev-master

API Docs can be found here: https://developers.pipedrive.com/v1

Example:
--------

```php
use Benhawker\Pipedrive\Pipedrive;

$pipedrive = new Pipedrive('0deceea867202fcf3889cd507ef93a91789f7e3a');

/**
 * Add company.
 */
$organization['name'] = 'Explorer';

$organization = $pipedrive->organizations()->add($organization);

/**
 * Add customer.
 */
$person['name'] = 'John Smith';
$person['org_id'] = $organization['data']['id'];

$person = $pipedrive->persons()->add($person);

/**
 * Add note to customer.
 */
$note['content'] = 'example note';
$note['person_id'] = $person['data']['id'];

$pipedrive->notes()->add($note);

/**
 * Add deal to customer.
 */
$deal['title'] = 'example title';
$deal['stage_id'] = 8;
$deal['person_id'] = $person['data']['id'];

$pipedrive->deals()->add($deal);

/**
 * Add activity.
 */
$activity = array(
    'subject' => 'Example send brochure',
    'type' => 'send-brochure',
    'person_id' => 17686,
    'user_id' => 190870,
    'deal_id' => 88,
    'due_date' => date('Y-m-d')
);

$pipedrive->activities()->add($activity);
```
