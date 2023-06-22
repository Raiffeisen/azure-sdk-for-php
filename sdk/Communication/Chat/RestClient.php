<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication\Chat;

use Azure\Communication\Identity\UserIdentifierAndToken;
use Azure\Core\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class RestClient extends Client
{
    /** @var UserIdentifierAndToken  */
    private UserIdentifierAndToken $_userIdentifierAndToken;

    /**
     * @param string $endpoint The endpoint URL.
     * @param string $apiVersion The api version.
     * @param array $config Client configuration settings.
     */
    public function __construct(string $endpoint, UserIdentifierAndToken $userIdentifierAndToken, array $config = [], string $apiVersion = '2022-06-01')
    {
        $this->_userIdentifierAndToken = $userIdentifierAndToken;
        parent::__construct($endpoint, $apiVersion, $config);
    }

    /**
     * {@inheritDoc}
     */
    public function request(string $method, $uri = '', array $options = []): ResponseInterface
    {
        $this->beforeSend($options);
        return parent::request($method, $uri, $options);
    }

    /**
     * This function is called before every operation.
     * @param array $options Request options to apply to the given request and to the transfer.
     */
    protected function beforeSend(array &$options = []): void
    {
        $options[RequestOptions::QUERY] = [
            'api-version' => $this->apiVersion
        ];

        $options[RequestOptions::HEADERS] = array_merge(
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->_userIdentifierAndToken->accessToken->token,
            ],
            $options[RequestOptions::HEADERS] ?? []
        );
    }
}
