<?php

namespace MobilityWork\Client\Http\Zendesk\Strategy;

use Zendesk\API\HttpClient as ZendeskAPI;

interface ZendeskStrategy
{
    public function save(ZendeskAPI $client, array $params): object;
}
