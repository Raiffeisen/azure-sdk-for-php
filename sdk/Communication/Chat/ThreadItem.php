<?php

namespace Azure\Communication\Chat;

use Azure\Core\Model;

class ThreadItem extends Model
{
    /** @var string Chat thread id. */
    public string $id;

    /** @var string Chat thread topic. */
    public string $topic;

    /** @var null|\DateTime The timestamp when the chat thread was deleted. The timestamp is in RFC3339 format: `yyyy-MM-ddTHH:mm:ssZ`. */
    public ?\DateTime $DeletedOn;

    /** @var null|\DateTime The timestamp when the last message arrived at the server. The timestamp is in RFC3339 format: `yyyy-MM-ddTHH:mm:ssZ`. */
    public ?\DateTime $lastMessageReceivedOn;


    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function __construct(array $config = [])
    {
        if ($config['deletedOn'] !== null) {
            $config['deletedOn'] = $this->parseDateTime($config['deletedOn']);
        }

        if ($config['lastMessageReceivedOn'] !== null) {
            $config['lastMessageReceivedOn'] = $this->parseDateTime($config['lastMessageReceivedOn']);
        }

        parent::__construct($config);
    }

    /**
     * Parses a rest array into a ThreadItem object.
     * @param array $threadItem
     * @return static
     * @throws \Exception
     */
    public static function fromArray(array $threadItem): static
    {
        return new static([
            'id' => $threadItem['id'],
            'topic' => $threadItem['topic'],
            'deletedOn' => $threadItem['deletedOn'] ?? null,
            'lastMessageReceivedOn' => $threadItem['lastMessageReceivedOn'] ?? null,
        ]);
    }
}
