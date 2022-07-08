<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication\Identity;

use Azure\Core\Client;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RestClient extends Client
{
    private const DATE_HEADER_NAME = 'x-ms-date';
    private const CONTENT_HEADER_NAME = 'x-ms-content-sha256';

    /**
     * @var string The key credential
     */
    private string $_keyCredential;

    /**
     * @param string $endpoint The endpoint URL.
     * @param string $keyCredential The azure key credential.
     * @param string $apiVersion The api version.
     * @param array $config Client configuration settings.
     */
    public function __construct(string $endpoint, string $keyCredential, array $config = [], string $apiVersion = '2022-06-01')
    {
        $this->_keyCredential = $keyCredential;
        parent::__construct($endpoint, $apiVersion, $config);
    }

    /**
     * {@inheritDoc}
     */
    protected function beforeSend(RequestInterface $request, array $options = []): void
    {
        $request->getUri()->withQuery('api-version=' . $this->apiVersion);
        $contentHash = $this->createContentHash($request);
        $this->addHeaders($request, $contentHash);
    }

    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    protected function afterSend(ResponseInterface $response): void
    {
        if (substr($response->getStatusCode(), 0, 1) !== '2') {
            throw new \Exception($response->getReasonPhrase());
        }
    }

    /**
     * Create the content hash out of a request
     * @param RequestInterface $request The request
     * @return string The content hash
     */
    private final function createContentHash(RequestInterface $request): string
    {
        return base64_encode(hash('sha256', $request->getBody()->getContents()));
    }

    /**
     * Add the needed headers to the request
     * @param RequestInterface $request The request
     * @param string $contentHash The content hash
     * @return void
     */
    private final function addHeaders(RequestInterface $request, string $contentHash): void
    {
        $utcNowString = gmdate('D, d M Y H:i:s') . ' GMT';
        $authorization = utf8_encode(sprintf(
            "%s\n%s\n%s;%s;%s",
            $request->getMethod(),
            $request->getRequestTarget(),
            $utcNowString,
            $request->getUri()->getAuthority(),
            $contentHash
        ));
        $signature = hash_hmac('sha256', $authorization, base64_decode($this->_keyCredential));

        $request->withAddedHeader(self::CONTENT_HEADER_NAME, $contentHash)
            ->withAddedHeader(self::DATE_HEADER_NAME, $utcNowString)
            ->withAddedHeader('Authorization', sprintf(
                'HMAC-SHA256 SignedHeaders=%s;host;%s&Signature=%s',
                self::DATE_HEADER_NAME,
                self::CONTENT_HEADER_NAME,
                $signature
            ));
    }
}
