<?php

namespace MobilityWork\Client\Http\Zendesk;

use MobilityWork\Client\Http\Zendesk\Strategy\ZendeskStrategy;

interface ZendeskHttpClientInterface
{
    public function post(array $params, ZendeskStrategy $strategy): object;
}
