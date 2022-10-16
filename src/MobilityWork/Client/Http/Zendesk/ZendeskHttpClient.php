<?php

namespace MobilityWork\Client\Http\Zendesk;

use Exception;
use MobilityWork\Client\Http\Zendesk\Strategy\ZendeskStrategy;
use MobilityWork\Lib\LoggerInterface;
use Zendesk\API\HttpClient as ZendeskAPI;

class ZendeskHttpClient implements ZendeskHttpClientInterface
{
    private readonly ZendeskAPI $client;

    public function __construct(
        ZendeskHttpClientConfigInterface $httpClientConfig,
        private readonly LoggerInterface $logger
    ) {
        $this->client = new ZendeskAPI($httpClientConfig->getSubdomain());
        $this->client->setAuth(
            'basic',
            [
                'username' => $httpClientConfig->getUsername(),
                'token' => $httpClientConfig->getToken()
            ]
        );
    }

    public function post(array $params, ZendeskStrategy $strategy): object
    {
        try {
            return $strategy->save($this->client, $params);
        } catch (Exception $exception) {
            $message = sprintf(
                '[%s] Post with params %s has failed. Previous: %s',
                $strategy::class,
                json_encode($params),
                $exception->getMessage()
            );
            $this->logger->addError($message);
            throw new ZendeskHttpClientException($message);
        }
    }
}
