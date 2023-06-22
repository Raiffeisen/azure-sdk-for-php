<?php

namespace Azure\Communication\Chat;

use Azure\Communication\Identity\Identity;
use Azure\Core\Model;

class MessageContent extends Model
{
    /** @var Identity */
    public Identity $initiator;

    /** @var string Chat message content for type 'text' or 'html' messages. */
    public string $message;

    /** @var array|Participant[] Chat message content for type 'topicUpdated' messages. */
    public array $participants;

    /** @var string Chat message content for type 'topicUpdated' messages. */
    public string $topic;

    /**
     * Parses a rest response into a MessageContent object.
     * @throws \Exception
     */
    public static function createFromResponse(array $data): static
    {
        $self = new static();

        if (array_key_exists('message', $data)) {
            $self->message = $data['message'];
        } elseif (array_key_exists('topic', $data)) {
            $self->initiator = new Identity($data['initiatorCommunicationIdentifier']['communicationUser']['id']);
            $self->topic = $data['topic'];
        } elseif (array_key_exists('participants', $data)) {
            $self->initiator = new Identity($data['initiatorCommunicationIdentifier']['communicationUser']['id']);
            $self->participants = array_map(function ($participant) {
                return Participant::fromArray($participant);
            }, $data['participants']);
        }

        return $self;
    }
}
