<?php

namespace Azure\Communication\Chat;

use Azure\Communication\Identity\UserIdentifierAndToken;
use Azure\Core\ConnectionString;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

class ThreadClient
{
    /** @var string The thread id */
    public string $threadId;
    /**
     * @var RestClient The rest connection
     */
    private RestClient $_restClient;

    /**
     * Initializes a new instance of CommunicationIdentityClient
     * @param string $threadId
     * @param string $connectionString
     * @param UserIdentifierAndToken $userIdentifierAndToken
     * @param array $options Client option exposing apiVersion, Guzzle http client options
     * @throws \Exception
     * @see RequestOptions
     */
    public function __construct(string $threadId, string $connectionString, UserIdentifierAndToken $userIdentifierAndToken, array $options = [])
    {
        $this->threadId = $threadId;

        $cs = ConnectionString::parse($connectionString);

        $config = $options['clientConfig'] ?? [];
        if (isset($options['apiVersion'])) {
            $this->_restClient = new RestClient(
                $cs->getRequired('endpoint'),
                $userIdentifierAndToken,
                $config,
                $options['apiVersion']
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
     * Updates the thread's topic.
     * @param string $topic Chat thread topic.
     * @return bool
     * @throws GuzzleException
     */
    public function updateTopic(string $topic): bool
    {
        $response = $this->_restClient->patch('chat/threads/' . $this->threadId, [
            RequestOptions::HEADERS => [
                'Content-Type' => 'application/merge-patch+json'
            ],
            RequestOptions::JSON => [
                'topic' => $topic
            ]
        ]);

        return str_starts_with($response->getStatusCode(), "2");
    }

    /**
     * Gets a chat thread.
     * @return ThreadProperties|false
     * @throws GuzzleException
     * @throws \Exception
     */
    public function getProperties(): ThreadProperties|false
    {
        $response = $this->_restClient->get('chat/threads/' . $this->threadId);
        return ThreadProperties::fromResponse($response);
    }

    /**
     * Sends a message to a thread.
     * @param SendMessageOptions $options
     * @return string|false
     * @throws GuzzleException
     */
    public function sendMessage(SendMessageOptions $options): string|false
    {
        $response = $this->_restClient->post('chat/threads/' . $this->threadId . '/messages', [
            RequestOptions::JSON => [
                'content' => $options->content,
                'metadata' => $options->metadata,
                'senderDisplayName' => $options->senderDisplayName,
                'type' => (string)$options->type
            ]
        ]);

        if (str_starts_with($response->getStatusCode(), "2")) {
            return json_decode($response->getBody()->getContents())->id;
        } else {
            return false;
        }
    }

    /**
     * Gets a message by id.
     * @param string $messageId The message id.
     * @return false|Message
     * @throws GuzzleException
     * @throws \Exception
     */
    public function getMessage(string $messageId): false|Message
    {
        $response = $this->_restClient->get('chat/threads/' . $this->threadId . '/messages/' . $messageId);
        return Message::fromResponse($response);
    }

    /**
     * Gets a list of messages from a thread.
     * @param int|null $maxPageSize The maximum number of messages to be returned per page.
     * @param \DateTime|null $startTime The earliest point in time to get messages up to.
     * @return array|false
     * @throws GuzzleException
     * @throws \Exception
     */
    public function getMessages(?int $maxPageSize = null, \DateTime $startTime = null): array|false
    {
        $options = [];
        if ($maxPageSize !== null) {
            $options[RequestOptions::QUERY]['maxPageSize'] = $maxPageSize;
        }
        if ($startTime !== null) {
            $options[RequestOptions::QUERY]['startTime'] = $startTime->format('Y-m-d\TH:i:s\Z');
        }

        $response = $this->_restClient->get('chat/threads/' . $this->threadId . '/messages', $options);

        if (str_starts_with($response->getStatusCode(), 2)) {
            $json = json_decode($response->getBody()->getContents(), true);
            $arr = [];
            foreach ($json['value'] as $message) {
                $arr[] = Message::fromArray($message);
            }
            return $arr;
        }

        return false;
    }

    /**
     * Updates a message.
     * @param UpdateMessageOptions $updateMessageOptions
     * @return bool
     * @throws GuzzleException
     */
    public function updateMessage(UpdateMessageOptions $updateMessageOptions): bool
    {
        $options = [
            RequestOptions::HEADERS => [
                'Content-Type' => 'application/merge-patch+json'
            ]
        ];

        if ($updateMessageOptions->content !== null) {
            $options[RequestOptions::JSON]['content'] = $updateMessageOptions->content;
        }
        if ($updateMessageOptions->metadata !== null) {
            $options[RequestOptions::JSON]['metadata'] = $updateMessageOptions->metadata;
        }

        $response = $this->_restClient->patch('chat/threads/' . $this->threadId . '/messages/' . $updateMessageOptions->messageId, $options);
        return str_starts_with($response->getStatusCode(), "2");
    }

    /**
     * Deletes a message.
     * @param string $messageId The message id.
     * @return bool
     * @throws GuzzleException
     */
    public function deleteMessage(string $messageId): bool
    {
        $response = $this->_restClient->delete('chat/threads/' . $this->threadId . '/messages/' . $messageId);
        return str_starts_with($response->getStatusCode(), "2");
    }

    /**
     * Adds thread participant to a thread. If participant already exist, no change occurs.
     * @param Participant $participant Participant to add to a chat thread.
     * @return array
     * @throws GuzzleException
     */
    public function addParticipant(Participant $participant): array
    {
        return $this->addParticipants([$participant]);
    }

    /**
     * Adds thread participants to a thread. If participants already exist, no change occurs.
     * @param array|Participant[] $participants
     * @return array List of invalid participants.
     * @throws GuzzleException
     */
    public function addParticipants(array $participants): array
    {
        $options = [
            RequestOptions::JSON => [
                'participants' => []
            ]
        ];
        foreach ($participants as $participant) {
            $options[RequestOptions::JSON]['participants'][] = $participant->toRequestArray();
        }

        $response = $this->_restClient->post('chat/threads/' . $this->threadId . '/participants/:add', $options);
        return json_decode($response->getBody()->getContents(), true)['invalidParticipants'] ?? [];
    }

    /**
     * Gets the participants of a thread.
     * @param int|null $maxPageSize The maximum number of participants to be returned per page.
     * @param int|null $skip Skips participants up to a specified position in response.
     * @return false|array|Participant[]
     * @throws GuzzleException
     * @throws \Exception
     */
    public function getParticipants(?int $maxPageSize = null, ?int $skip = null): false|array
    {
        $options = [];
        if ($maxPageSize !== null) {
            $options[RequestOptions::QUERY]['maxPageSize'] = $maxPageSize;
        }
        if ($skip !== null) {
            $options[RequestOptions::QUERY]['skip'] = $skip;
        }

        $response = $this->_restClient->get('chat/threads/' . $this->threadId . '/participants', $options);

        if (str_starts_with($response->getStatusCode(), 2)) {
            $json = json_decode($response->getBody()->getContents(), true);
            $arr = [];
            foreach ($json['value'] as $participant) {
                $arr[] = Participant::fromArray($participant);
            }
            return $arr;
        }

        return false;
    }

    /**
     * Removes a participant from a thread.
     * @param string $userId Id of the thread participant to remove from the thread.
     * @return bool
     * @throws GuzzleException
     */
    public function removeParticipant(string $userId): bool
    {
        $response = $this->_restClient->post('chat/threads/' . $this->threadId . '/participants/:remove', [
            RequestOptions::JSON => [
                'rawId' => $userId,
                'communicationUser' => [
                    'id' => $userId
                ]
            ]
        ]);
        return str_starts_with($response->getStatusCode(), "2");
    }

    /**
     * Posts a typing event to a thread, on behalf of a user.
     * @param string $displayName The display name of the typing notification sender. This property is used to populate sender name for push notifications.
     * @return bool
     * @throws GuzzleException
     */
    public function sendTypingNotification(string $displayName): bool
    {
        $response = $this->_restClient->post('chat/threads/' . $this->threadId . '/typing', [
            RequestOptions::JSON => [
                'senderDisplayName' => $displayName
            ]
        ]);
        return str_starts_with($response->getStatusCode(), "2");
    }

    /**
     * Sends a read receipt event to a thread, on behalf of a user.
     * @param string $messageId Id of the latest chat message read by the user.
     * @return bool
     * @throws GuzzleException
     */
    public function sendReadReceipt(string $messageId): bool
    {
        $response = $this->_restClient->post('chat/threads/' . $this->threadId . '/readReceipts', [
            RequestOptions::JSON => [
                'chatMessageId' => $messageId
            ]
        ]);
        return str_starts_with($response->getStatusCode(), "2");
    }

    /**
     * Gets chat message read receipts for a thread.
     * @param int|null $maxPageSize The maximum number of chat message read receipts to be returned per page.
     * @param int|null $skip Skips chat message read receipts up to a specified position in response.
     * @return false|array|MessageReadReceipt[]
     * @throws GuzzleException
     * @throws \Exception
     */
    public function getReadReceipts(?int $maxPageSize = null, ?int $skip = null): false|array
    {
        $options = [];
        if ($maxPageSize !== null) {
            $options[RequestOptions::QUERY]['maxPageSize'] = $maxPageSize;
        }
        if ($skip !== null) {
            $options[RequestOptions::QUERY]['skip'] = $skip;
        }

        $response = $this->_restClient->get('chat/threads/' . $this->threadId . '/readReceipts', $options);

        if (str_starts_with($response->getStatusCode(), 2)) {
            $json = json_decode($response->getBody()->getContents(), true);
            $arr = [];
            foreach ($json['value'] as $messageReadReceipt) {
                $arr[] = MessageReadReceipt::fromArray($messageReadReceipt);
            }
            return $arr;
        }

        return false;
    }
}
