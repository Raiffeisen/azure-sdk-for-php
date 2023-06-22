<?php

namespace Azure\Communication\Chat;

use Azure\Core\Model;

class SendMessageOptions extends Model
{
    /** @var string Chat message content. */
    public string $content;

    /** @var MessageType The chat message type. */
    public MessageType $type;

    /** @var string The display name of the chat message sender. This property is used to populate sender name for push notifications. */
    public string $senderDisplayName;

    /** @var null|array Message metadata. */
    public ?array $metadata = null;


    /**
     * @throws \Exception
     */
    public function __construct(array $config = [])
    {
        if (!isset($config['content'])) {
            throw new \Exception("content is required");
        }
        if (!isset($config['type'])) {
            $config['type'] = MessageType::text();
        }
        if (!isset($config['senderDisplayName'])) {
            throw new \Exception("senderDisplayName is required");
        }

        parent::__construct($config);
    }
}
