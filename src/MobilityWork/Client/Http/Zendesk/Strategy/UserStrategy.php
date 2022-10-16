<?php

namespace MobilityWork\Client\Http\Zendesk\Strategy;

use Zendesk\API\HttpClient as ZendeskAPI;

class UserStrategy implements ZendeskStrategy
{
    public function save(ZendeskAPI $client, array $params): object
    {
        return $client->users()->createOrUpdate($params);
    }
}
