<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication\Identity;

use Azure\Core\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
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
     * This function is called before every operation.
     * @param string $method The HTTP transfer method.
     * @param string $uri The uri endpoint.
     * @param array $options Request options to apply to the given request and to the transfer.
     */
    protected function beforeSend(string $method, string $uri, array &$options = []): void
    {
        $options[RequestOptions::QUERY] = [
            'api-version' => $this->apiVersion
        ];
        $contentHash = $this->createContentHash($options[RequestOptions::BODY] ?? '');
        $this->addHeaders($method, $uri, $options, $contentHash);
    }

    /**
     * {@inheritDoc}
     */
    public function request(string $method, $uri = '', array $options = []): ResponseInterface
    {
        $this->beforeSend($method, $uri, $options);
        return parent::request($method, $uri, $options);
    }

    /**
     * Create the content hash out of a request
     * @param string $body The request body
     * @return string The content hash
     */
    private function createContentHash(string $body): string
    {
        return base64_encode(hash('sha256', $body));
    }

    /**
     * Add the needed headers to the request
     * @param string $method The request
     * @param string $uri The request target
     * @param array $options The request options
     * @param string $contentHash The content hash
     * @return void
     */
    private function addHeaders(string $method, string $uri, array &$options, string $contentHash): void
    {
        $utcNowString = gmdate('D, d M Y H:i:s') . ' GMT';
        $target = $uri;
        if ($target === '') {
            $target = '/';
        }
        if (!empty($options[RequestOptions::QUERY])) {
            $target .= '?' . http_build_query($options[RequestOptions::QUERY]);
        }
        /** @var Request $request */
        $authorization = utf8_encode(sprintf(
            "%s\n%s\n%s;%s;%s",
            $method,
            $target,
            $utcNowString,
            '', // $request->getUri()->getAuthority()
            $contentHash
        ));
        $signature = hash_hmac('sha256', $authorization, base64_decode($this->_keyCredential));

        $options[RequestOptions::HEADERS] = [
            self::CONTENT_HEADER_NAME => $contentHash,
            self::DATE_HEADER_NAME => $utcNowString,
            'Authorization' => sprintf(
                'HMAC-SHA256 SignedHeaders=%s;host;%s&Signature=%s',
                self::DATE_HEADER_NAME,
                self::CONTENT_HEADER_NAME,
                $signature
            )
        ];
    }
}
