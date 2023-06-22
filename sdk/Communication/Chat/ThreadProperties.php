<?php

namespace Azure\Communication\Chat;

use Azure\Communication\Identity\Identity;
use Azure\Core\Model;
use Psr\Http\Message\ResponseInterface;

class ThreadProperties extends Model
{
    /** @var string Chat thread id. */
    public string $id;

    /** @var string Chat thread topic. */
    public string $topic;

    /** @var \DateTime The timestamp when the chat thread was created. */
    public \DateTime $createdOn;

    /** @var Identity Identifier of the chat thread owner. */
    public Identity $createdBy;

    /** @var null|\DateTime timestamp when the chat thread was deleted. */
    public ?\DateTime $deletedOn = null;


    /**
     * @throws \Exception
     */
    public function __construct(array $config = [])
    {
        if (!isset($config['id'])) {
            throw new \Exception("id is required");
        }
        if (!isset($config['topic'])) {
            throw new \Exception("topic is required");
        }
        if (!isset($config['createdOn'])) {
            throw new \Exception("createdOn is required");
        }
        if (!isset($config['createdBy'])) {
            throw new \Exception("createdBy is required");
        }

        $config['createdOn'] = $this->parseDateTime($config['createdOn']);

        if ($config['deletedOn'] !== null) {
            $config['deletedOn'] = $this->parseDateTime($config['deletedOn']);
        }

        // assign options to properties
        parent::__construct($config);
    }

    /**
     * Parses a rest response into a Thread object.
     * @param ResponseInterface $response
     * @return static|false
     * @throws \Exception
     */
    public static function fromResponse(ResponseInterface $response): static|false
    {
        if (str_starts_with($response->getStatusCode(), '2')) {
            $contents = json_decode($response->getBody()->getContents(), true);

            if (array_key_exists('chatThread', $contents)) {
                $thread = $contents['chatThread'];
            } else {
                $thread = $contents;
            }

            return new static([
                'id' => $thread['id'],
                'topic' => $thread['topic'],
                'createdOn' => $thread['createdOn'],
                'createdBy' => new Identity($thread['createdByCommunicationIdentifier']['communicationUser']['id']),
                'deletedOn' => array_key_exists('deletedOn', $thread) ? $thread['deletedOn'] : null
            ]);
        }
        return false;
    }
}
