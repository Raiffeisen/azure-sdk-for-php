<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication\Pipeline;

use Azure\Core\Pipeline\HttpPipelinePolicy;
use GuzzleHttp\Psr7\Request;

class HMACAuthenticationPolicy extends HttpPipelinePolicy
{
    private const DATE_HEADER_NAME = 'x-ms-date';
    private const CONTENT_HEADER_NAME = 'x-ms-content-sha256';

    /**
     * @var string
     */
    private $_keyCredential;

    /**
     * Initialize a new HMACAuthenticationPolicy.
     * @param string $keyCredential
     */
    public function __construct(string $keyCredential)
    {
        $this->_keyCredential = $keyCredential;
    }

    /**
     * {@inheritDoc}
     */
    public function processAsync(Request $message, array $pipeline): \GuzzleHttp\Promise\PromiseInterface
    {
        $contentHash = $this->createContentHashAsync($message);
        $this->addHeaders($message, $contentHash);
        return self::processNextAsync($message, $pipeline);
    }

    /**
     * {@inheritDoc}
     */
    public function process(Request $message, array $pipeline)
    {
        $contentHash = $this->createContentHash($message);
        $this->addHeaders($message, $contentHash);
        self::processNext($message, $pipeline);
    }

    private function createContentHash(Request $message): string
    {
        return base64_encode(hash('sha256', $message->getBody()->getContents()));
    }

    private function createContentHashAsync(Request $message): string
    {
        return $this->createContentHash($message);
    }

    private function addHeaders(Request $message, string $contentHash)
    {
        $utcNowString = gmdate('D, d M Y H:i:s') . ' GMT';
        $authorization = utf8_encode(sprintf(
            "%s\n%s\n%s;%s;%s",
            $message->getMethod(),
            $message->getRequestTarget(),
            $utcNowString,
            $message->getUri()->getAuthority(),
            $contentHash
        ));
        $signature = hash_hmac('sha256', $authorization, base64_decode($this->_keyCredential));

        $message->withAddedHeader(self::CONTENT_HEADER_NAME, $contentHash)
            ->withAddedHeader(self::DATE_HEADER_NAME, $utcNowString)
            ->withAddedHeader('Authorization', sprintf(
                'HMAC-SHA256 SignedHeaders=%s;host;%s&Signature=%s',
                self::DATE_HEADER_NAME,
                self::CONTENT_HEADER_NAME,
                $signature
            ));
    }
}
