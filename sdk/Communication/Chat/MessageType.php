<?php

namespace Azure\Communication\Chat;

class MessageType
{
    const TEXT_VALUE = "text";
    const HTML_VALUE = "html";
    const TOPIC_UPDATED_VALUE = "topicUpdated";
    const PARTICIPANT_ADDED_VALUE = "participantAdded";
    const PARTICIPANT_REMOVED_VALUE = "participantRemoved";

    private readonly string $_value;

    public function __construct(string $value)
    {
        $this->_value = $value;
    }

    /**
     * Returns the value
     * @return string
     */
    public function __toString()
    {
        return $this->_value;
    }

    /**
     * text
     * @return static
     */
    public static function text(): static
    {
        return new static(static::TEXT_VALUE);
    }

    /**
     * html
     * @return static
     */
    public static function html(): static
    {
        return new static(static::HTML_VALUE);
    }

    /**
     * topic updated
     * @return static
     */
    public static function topicUpdated(): static
    {
        return new static(static::TOPIC_UPDATED_VALUE);
    }

    /**
     * participant added
     * @return static
     */
    public static function participantAdded(): static
    {
        return new static(static::PARTICIPANT_ADDED_VALUE);
    }

    /**
     * participant removed
     * @return static
     */
    public static function participantRemoved(): static
    {
        return new static(static::PARTICIPANT_REMOVED_VALUE);
    }

    /**
     * Compare 2 MessageType objects
     * @param MessageType $messageType
     * @return bool
     */
    public function isEqual(MessageType $messageType): bool
    {
        return $this->_value == $messageType;
    }
}
