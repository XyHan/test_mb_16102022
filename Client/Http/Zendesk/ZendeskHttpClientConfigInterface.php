<?php

namespace MobilityWork\Client\Http\Zendesk;

interface ZendeskHttpClientConfigInterface
{
    public function getSubdomain(): string;
    public function getUsername(): string;
    public function getToken(): string;
}
