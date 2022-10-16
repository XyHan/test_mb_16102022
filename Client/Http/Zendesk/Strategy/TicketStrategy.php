<?php

namespace MobilityWork\Client\Http\Zendesk\Strategy;

use Zendesk\API\HttpClient as ZendeskAPI;

class TicketStrategy implements ZendeskStrategy
{
    public function save(ZendeskAPI $client, array $params): object
    {
        return $client->tickets()->create($params);
    }
}
