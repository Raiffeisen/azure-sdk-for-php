<?php

namespace Azure\Communication\Chat;

use Azure\Communication\Identity\Identity;
use Azure\Core\Model;
use Psr\Http\Message\ResponseInterface;

class Message extends Model
{
    /** @var string The id of the chat message. This id is server generated. */
    public string $id;

    /** @var MessageType The chat message type. */
    public MessageType $type;

    /** @var string Sequence of the chat message in the conversation. */
    public string $sequenceId;

    /** @var string Version of the chat message. */
    public string $version;

    /** @var MessageContent Content of a chat message. */
    public MessageContent $content;

    /** @var string The display name of the chat message sender. This property is used to populate sender name for push notifications. */
    public string $senderDisplayName;

    /** @var \DateTime The timestamp when the chat message arrived at the server. The timestamp is in RFC3339 format: `yyyy-MM-ddTHH:mm:ssZ`. */
    public \DateTime $createdOn;

    /** @var Identity The identifier of the chat message sender. */
    public Identity $sender;

    /** @var \DateTime|null The timestamp (if applicable) when the message was deleted. The timestamp is in RFC3339 format: `yyyy-MM-ddTHH:mm:ssZ`. */
    public ?\DateTime $deletedOn = null;

    /** @var \DateTime|null The last timestamp (if applicable) when the message was edited. The timestamp is in RFC3339 format: `yyyy-MM-ddTHH:mm:ssZ`. */
    public ?\DateTime $editedOn = null;

    /** @var array|null Properties */
    public ?array $metadata = null;


    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function __construct(array $config = [])
    {
        $config['createdOn'] = $this->parseDateTime($config['createdOn']);

        if ($config['deletedOn'] !== null) {
            $config['deletedOn'] = $this->parseDateTime($config['deletedOn']);
        }

        if ($config['editedOn'] !== null) {
            $config['editedOn'] = $this->parseDateTime($config['editedOn']);
        }

        parent::__construct($config);
    }

    /**
     * Parses a rest response into a Message object.
     * @param ResponseInterface $response
     * @return static|false
     * @throws \Exception
     */
    public static function fromResponse(ResponseInterface $response): static|false
    {
        if (str_starts_with($response->getStatusCode(), '2')) {
            $message = json_decode($response->getBody()->getContents(), true);
            return static::fromArray($message);
        }
        return false;
    }

    /**
     * Parses a rest response array into a Message object.
     * @param array $message
     * @return static
     * @throws \Exception
     */
    public static function fromArray(array $message): static
    {
        $self = new static([
            'id' => $message['id'],
            'type' => new MessageType($message['type']),
            'sequenceId' => $message['sequenceId'],
            'version' => $message['version'],
            'content' => MessageContent::createFromResponse($message['content']),
            'createdOn' => $message['createdOn'],
            'deletedOn' => array_key_exists('deletedOn', $message) ? $message['deletedOn'] : null,
            'editedOn' => array_key_exists('editedOn', $message) ? $message['editedOn'] : null,
            'metadata' => array_key_exists('metadata', $message) ? $message['metadata'] : null
        ]);

        if ($self->type == MessageType::HTML_VALUE || $self->type == MessageType::TEXT_VALUE) {
            $self->senderDisplayName = $message['senderDisplayName'];
            $self->sender = new Identity($message['senderCommunicationIdentifier']['communicationUser']['id']);
        }

        return $self;
    }
}
