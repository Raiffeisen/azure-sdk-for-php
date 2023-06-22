<?php

namespace Azure\Communication\Chat;

use Azure\Core\Model;

class UpdateMessageOptions extends Model
{
    /** @var string The id of the chat message. */
    public string $messageId;

    /** @var null|string Content of a chat message. */
    public ?string $content = null;

    /** @var null|array Properties bag for custom attributes to the message in the form of key-value pair. */
    public ?array $metadata = null;
}
