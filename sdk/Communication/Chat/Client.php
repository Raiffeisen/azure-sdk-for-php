<?php

namespace Azure\Communication\Chat;

use Azure\Communication\Identity\UserIdentifierAndToken;
use Azure\Core\ConnectionString;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

class Client
{
    /** @var RestClient The rest connection */
    private RestClient $_restClient;

    /** @var string The connection string */
    private string $_connectionString;

    /** @var UserIdentifierAndToken */
    private UserIdentifierAndToken $_userIdentifierAndToken;

    /** @var array */
    private array $_options;

    /**
     * Initializes a new instance of CommunicationIdentityClient
     * @param string $connectionString Connection string acquired from the Azure Communication Services resource.
     * @param array $options Client option exposing apiVersion, Guzzle http client options
     * @throws \Exception
     * @see RequestOptions
     */
    public function __construct(string $connectionString, UserIdentifierAndToken $userIdentifierAndToken, array $options = [])
    {
        $this->_connectionString = $connectionString;
        $this->_userIdentifierAndToken = $userIdentifierAndToken;
        $this->_options = $options;

        $cs = ConnectionString::parse($connectionString);

        $config = $this->_options['clientConfig'] ?? [];
        if (isset($this->_options['apiVersion'])) {
            $this->_restClient = new RestClient(
                $cs->getRequired('endpoint'),
                $userIdentifierAndToken,
                $config,
                $this->_options['apiVersion']
            );
        } else {
            $this->_restClient = new RestClient(
                $cs->getRequired('endpoint'),
                $userIdentifierAndToken,
                $config
            );
        }
    }

    /**
     * Creates a ThreadClient.
     * @param string $topic
     * @param Participant[]|array $participants
     * @return ThreadProperties|false
     * @throws GuzzleException
     * @throws \Exception
     */
    public function createThread(string $topic, array $participants = []): ThreadProperties|false
    {
        $options[RequestOptions::JSON] = [
            'topic' => $topic
        ];
        foreach ($participants as $participant) {
            $options[RequestOptions::JSON]['participants'][] = $participant->toRequestArray();
        }

        $response = $this->_restClient->post('chat/threads', $options);

        return ThreadProperties::fromResponse($response);
    }

    /**
     * Initializes a new instance of ThreadClient.
     * @param string $threadId
     * @return ThreadClient
     * @throws \Exception
     */
    public function getThreadClient(string $threadId): ThreadClient
    {
        return new ThreadClient($threadId, $this->_connectionString, $this->_userIdentifierAndToken, $this->_options);
    }

    /**
     * Gets the list of chat threads of a user
     * @param int|null $maxPageSize The maximum number of chat threads returned per page.
     * @param \DateTime|null $startTime The earliest point in time to get chat threads up to.
     * @return false|array|ThreadItem[]
     * @throws GuzzleException
     * @throws \Exception
     */
    public function getThreads(?int $maxPageSize = null, ?\DateTime $startTime = null): false|array
    {
        $query = [];
        if ($maxPageSize) {
            $query['maxPageSize'] = $maxPageSize;
        }
        if ($startTime) {
            $query['startTime'] = $startTime->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d\TH:i:s\Z');
        }

        $response = $this->_restClient->get('chat/threads', [
            RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            $query
        ]);

        if (str_starts_with($response->getStatusCode(), 2)) {
            $json = json_decode($response->getBody()->getContents(), true);
            $arr = [];
            foreach ($json['value'] as $threadItem) {
                $arr[] = ThreadItem::fromArray($threadItem);
            }
            return $arr;
        }

        return false;
    }

    /**
     * Deletes a thread.
     * @param string $threadId
     * @return bool
     * @throws GuzzleException
     */
    public function deleteThread(string $threadId): bool
    {
        $response = $this->_restClient->delete('chat/threads/' . $threadId, [
            RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        ]);

        return str_starts_with($response->getStatusCode(), "2");
    }
}
