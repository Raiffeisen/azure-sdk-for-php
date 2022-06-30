<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Core;

class RetryOptions
{
    private $_maxRetries = 3;
    private $_delay = .8;
    private $_maxDelay = 60;
    private $_mode = RetryMode::EXPONENTIAL;
    private $_networkTimeout = 100;

    /**
     * The maximum number of retry attempts before giving up.
     * @return int
     */
    public function getMaxRetries(): int
    {
        return $this->_maxRetries;
    }

    /**
     * The maximum number of retry attempts before giving up.
     * @param int $maxRetries
     */
    public function setMaxRetries(int $maxRetries): void
    {
        $this->_maxRetries = $maxRetries;
    }

    /**
     * The delay between retry attempts for a fixed approach or the delay on which to base calculations for a
     * backoff-based approach.
     * If the service provides a Retry-After response header, the next retry will be delayed by the duration specified
     * by the header value.
     * @return float
     */
    public function getDelay(): float
    {
        return $this->_delay;
    }

    /**
     * The delay between retry attempts for a fixed approach or the delay on which to base calculations for a
     * backoff-based approach.
     * If the service provides a Retry-After response header, the next retry will be delayed by the duration specified
     * by the header value.
     * @param float $delay
     */
    public function setDelay(float $delay): void
    {
        $this->_delay = $delay;
    }

    /**
     * The maximum permissible delay between retry attempts when the service does not provide a Retry-After response header.
     * If the service provides a Retry-After response header, the next retry will be delayed by the duration specified by the header value.
     * @return float
     */
    public function getMaxDelay(): float
    {
        return $this->_maxDelay;
    }

    /**
     * The maximum permissible delay between retry attempts when the service does not provide a Retry-After response header.
     * If the service provides a Retry-After response header, the next retry will be delayed by the duration specified by the header value.
     * @param float $maxDelay
     */
    public function setMaxDelay(float $maxDelay): void
    {
        $this->_maxDelay = $maxDelay;
    }

    /**
     * The approach to use for calculating retry delays. One of the RetryMode::* constants.
     * @return string
     */
    public function getMode(): string
    {
        return $this->_mode;
    }

    /**
     * The approach to use for calculating retry delays. One of the RetryMode::* constants.
     * @param string $mode
     */
    public function setMode(string $mode): void
    {
        $this->_mode = $mode;
    }

    /**
     * The timeout applied to an individual network operations.
     * @return int
     */
    public function getNetworkTimeout(): int
    {
        return $this->_networkTimeout;
    }

    /**
     * The timeout applied to an individual network operations.
     * @param int $networkTimeout
     */
    public function setNetworkTimeout(int $networkTimeout): void
    {
        $this->_networkTimeout = $networkTimeout;
    }
}
