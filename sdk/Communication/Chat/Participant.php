<?php

namespace Azure\Communication\Chat;

use Azure\Communication\Identity\Identity;
use Azure\Core\Model;

class Participant extends Model
{
    /** @var Identity User identity of the chat participant. */
    public Identity $user;

    /** @var string Display name for the chat participant. */
    public string $displayName;

    /** @var \DateTime Time from which the chat history is shared with the participant. */
    public \DateTime $shareHistoryTime;


    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function __construct(array $config = [])
    {
        if (!$config['shareHistoryTime'] instanceof \DateTime) {
            $config['shareHistoryTime'] = $this->parseDateTime($config['shareHistoryTime']);
        }
        parent::__construct($config);
    }

    /**
     * Parses a rest response array into a Participant object.
     * @param array $participant
     * @return static
     * @throws \Exception
     */
    public static function fromArray(array $participant): static
    {
        return new static([
            'user' => new Identity($participant['communicationIdentifier']['communicationUser']['id']),
            'displayName' => $participant['displayName'] ?? '',
            'shareHistoryTime' => $participant['shareHistoryTime'],
        ]);
    }

    /**
     * Converts the Participant object into an array for the request body.
     * @return array
     */
    public function toRequestArray(): array
    {
        return [
            'communicationIdentifier' => [
                'rawId' => $this->user->id,
                'communicationUser' => [
                    'id' => $this->user->id
                ]
            ],
            'displayName' => $this->displayName,
            'shareHistoryTime' => $this->shareHistoryTime->format('Y-m-d\TH:i:s.u\Z'),
        ];
    }
}
