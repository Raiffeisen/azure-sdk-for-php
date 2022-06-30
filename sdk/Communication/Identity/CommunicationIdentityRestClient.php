<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication\Identity;

use Azure\Communication\Identity\Models\CommunicationIdentityAccessTokenRequest;
use Azure\Communication\Identity\Models\CommunicationIdentityCreateRequest;
use Azure\Communication\Identity\Models\CommunicationTokenScope;
use Azure\Communication\Identity\Models\TeamsUserExchangeTokenRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Utils;

class CommunicationIdentityRestClient
{
    /**
     * @var Client
     */
    private $_pipeline;
    /**
     * @var string
     */
    private $_endpoint;
    /**
     * @var string
     */
    private $_apiVersion;

    /**
     * @param Client $pipeline
     * @param string $endpoint
     * @param string $apiVersion
     */
    public function __construct(Client $pipeline, string $endpoint, string $apiVersion = '2022-06-01')
    {
        $this->_pipeline = $pipeline;
        $this->_endpoint = $endpoint;
        $this->_apiVersion = $apiVersion;
    }

    /**
     * @param CommunicationTokenScope[] $createTokenWithScopes
     * @return Request
     */
    private function createCreateRequest(array $createTokenWithScopes): Request
    {
        $uri = new Uri($this->_endpoint);
        $uri->withPath('/identities')
            ->withQuery('api-version=' . $this->_apiVersion);
        $communicationIdentityCreateRequest = new CommunicationIdentityCreateRequest();
        if (!empty($createTokenWithScopes)) {
            foreach ($createTokenWithScopes as $value) {
                $communicationIdentityCreateRequest[] = $value;
            }
        }

        return new Request('POST', $uri, [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ], Utils::jsonEncode($communicationIdentityCreateRequest->toArray()));
    }

    /**
     * @param string $id
     * @return Request
     */
    private function createDeleteRequest(string $id): Request
    {
        $uri = new Uri($this->_endpoint);
        $uri->withPath('/identities/' . $id)
            ->withQuery('api-version=' . $this->_apiVersion);

        return new Request('DELETE', $uri, [
            'Accept' => 'application/json'
        ]);
    }

    /**
     * @param string $id
     * @return Request
     */
    private function createRevokeAccessTokensRequest(string $id): Request
    {
        $uri = new Uri($this->_endpoint);
        $uri->withPath('/identities/' . $id . '/:revokeAccessTokens')
            ->withQuery('api-version=' . $this->_apiVersion);

        return new Request('POST', $uri, [
            'Accept' => 'application/json'
        ]);
    }

    /**
     * @param string $token
     * @param string $appId
     * @param string $userId
     * @return Request
     */
    private function createExchangeTeamsUserAccessTokenRequest(string $token, string $appId, string $userId): Request
    {
        $uri = new Uri($this->_endpoint);
        $uri->withPath('/teamsUser/:exchangeAccessToken')
            ->withQuery('api-version=' . $this->_apiVersion);
        $model = new TeamsUserExchangeTokenRequest($token, $appId, $userId);

        return new Request('POST', $uri, [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ], Utils::jsonEncode($model->toArray()));
    }

    /**
     * @param string $id
     * @param CommunicationTokenScope[] $scopes
     * @return Request
     */
    private function createIssueAccessTokenRequest(string $id, array $scopes): Request
    {
        $uri = new Uri($this->_endpoint);
        $uri->withPath('/identities/' . $id . '/:issueAccessToken')
            ->withQuery('api-version=' . $this->_apiVersion);

        $model = new CommunicationIdentityAccessTokenRequest($scopes);

        return new Request('POST', $uri, [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ], Utils::jsonEncode($model->toArray()));
    }

    /**
     * Create a new identity and optionally, an access token.
     * @param CommunicationTokenScope[]|null $createTokenWithScopes Also create access token for
     * @param mixed $cancellationToken The cancellation token to use.
     * @return CommunicationUserIdentifierAndToken
     * @throws \Exception
     */
    public function createAsync(?array $createTokenWithScopes = null, $cancellationToken = null): CommunicationUserIdentifierAndToken
    {
        $message = $this->createCreateRequest($createTokenWithScopes);

        return $this->_pipeline
            ->sendAsync($message)
            ->then(function ($result) {
                /** @var \GuzzleHttp\Psr7\Response $response */
                [$request, $response] = $result;

                switch ($response->getStatusCode()) {
                    case 201:
                        $document = json_decode($response->getBody()->getContents(), true);

                        return CommunicationUserIdentifierAndToken::deserializeCommunicationUserIdentifierAndToken($document);
                    default:
                        throw new \Exception($response->getReasonPhrase());
                }
            })
            ->wait();
    }

    /**
     * Create a new identity and optionally, an access token.
     * @param CommunicationTokenScope[]|null $createTokenWithScopes Also create access token for
     * @param mixed $cancellationToken The cancellation token to use.
     * @return CommunicationUserIdentifierAndToken
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function create(?array $createTokenWithScopes = null, $cancellationToken = null): CommunicationUserIdentifierAndToken
    {
        $message = $this->createCreateRequest($createTokenWithScopes);
        $response = $this->_pipeline->send($message);
        switch ($response->getStatusCode()) {
            case 201:
                $document = json_decode($response->getBody()->getContents(), true);

                return CommunicationUserIdentifierAndToken::deserializeCommunicationUserIdentifierAndToken($document);
            default:
                throw new \Exception($response->getReasonPhrase());
        }
    }

    /**
     * Delete the identity, revoke all tokens for the identity and delete all associated data.
     * @param string $id Identifier of the identity to be deleted.
     * @param mixed $cancellationToken The cancellation token to use.
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function deleteAsync(string $id, $cancellationToken = null): \Psr\Http\Message\ResponseInterface
    {
        $message = $this->createDeleteRequest($id);

        return $this->_pipeline
            ->sendAsync($message)
            ->then(function ($result) {
                /** @var \GuzzleHttp\Psr7\Response $response */
                [$request, $response] = $result;

                switch ($response->getStatusCode()) {
                    case 204:
                        return $response;
                    default:
                        throw new \Exception($response->getReasonPhrase());
                }
            })
            ->wait();
    }

    /**
     * Delete the identity, revoke all tokens for the identity and delete all associated data.
     * @param string $id Identifier of the identity to be deleted.
     * @param mixed $cancellationToken The cancellation token to use.
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function delete(string $id, $cancellationToken = null): \Psr\Http\Message\ResponseInterface
    {
        $message = $this->createDeleteRequest($id);
        $response = $this->_pipeline->send($message);
        switch ($response->getStatusCode()) {
            case 204:
                return $response;
            default:
                throw new \Exception($response->getReasonPhrase());
        }
    }

    /**
     * Revoke all access tokens for the specific identity.
     * @param string $id Identifier of the identity.
     * @param mixed $cancellationToken The cancellation token to use.
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function revokeAccessTokensAsync(string $id, $cancellationToken = null): \Psr\Http\Message\ResponseInterface
    {
        $message = $this->createRevokeAccessTokensRequest($id);

        return $this->_pipeline
            ->sendAsync($message)
            ->then(function ($result) {
                /** @var \GuzzleHttp\Psr7\Response $response */
                [$request, $response] = $result;

                switch ($response->getStatusCode()) {
                    case 204:
                        return $response;
                    default:
                        throw new \Exception($response->getReasonPhrase());
                }
            })
            ->wait();
    }

    /**
     * Revoke all access tokens for the specific identity.
     * @param string $id Identifier of the identity.
     * @param mixed $cancellationToken The cancellation token to use.
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function revokeAccessTokens(string $id, $cancellationToken = null): \Psr\Http\Message\ResponseInterface
    {
        $message = $this->createRevokeAccessTokensRequest($id);
        $response = $this->_pipeline->send($message);
        switch ($response->getStatusCode()) {
            case 204:
                return $response;
            default:
                throw new \Exception($response->getReasonPhrase());
        }
    }

    /**
     * Exchange an Azure Active Directory (Azure AD) access token of a Teams user for a new Communication Identity
     * access token with a matching expiration time.
     * @param string $token Azure AD access token of a Teams User to acquire a new Communication Identity access token.
     * @param string $appId Client ID of an Azure AD application to be verified against the appid claim in the Azure AD access token.
     * @param string $userId Object ID of an Azure AD user (Teams User) to be verified against the oid claim in the Azure AD access token.
     * @param mixed $cancellationToken The cancellation token to use.
     * @return CommunicationIdentityAccessToken
     * @throws \Exception
     */
    public function exchangeTeamsUserAccessTokenAsync(string $token, string $appId, string $userId, $cancellationToken = null): CommunicationIdentityAccessToken
    {
        $message = $this->createExchangeTeamsUserAccessTokenRequest($token, $appId, $userId);

        return $this->_pipeline
            ->sendAsync($message)
            ->then(function ($result) {
                /** @var \GuzzleHttp\Psr7\Response $response */
                [$request, $response] = $result;

                switch ($response->getStatusCode()) {
                    case 200:
                        $document = json_decode($response->getBody()->getContents(), true);

                        return CommunicationIdentityAccessToken::deserializeCommunicationIdentityAccessToken($document);
                    default:
                        throw new \Exception($response->getReasonPhrase());
                }
            })
            ->wait();
    }

    /**
     * Exchange an Azure Active Directory (Azure AD) access token of a Teams user for a new Communication Identity
     * access token with a matching expiration time.
     * @param string $token Azure AD access token of a Teams User to acquire a new Communication Identity access token.
     * @param string $appId Client ID of an Azure AD application to be verified against the appid claim in the Azure AD access token.
     * @param string $userId Object ID of an Azure AD user (Teams User) to be verified against the oid claim in the Azure AD access token.
     * @param mixed $cancellationToken The cancellation token to use.
     * @return CommunicationIdentityAccessToken
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function exchangeTeamsUserAccessToken(string $token, string $appId, string $userId, $cancellationToken = null): CommunicationIdentityAccessToken
    {
        $message = $this->createExchangeTeamsUserAccessTokenRequest($token, $appId, $userId);
        $response = $this->_pipeline->send($message);
        switch ($response->getStatusCode()) {
            case 200:
                $document = json_decode($response->getBody()->getContents(), true);

                return CommunicationIdentityAccessToken::deserializeCommunicationIdentityAccessToken($document);
            default:
                throw new \Exception($response->getReasonPhrase());
        }
    }

    /**
     * Issue a new token for an identity.
     * @param string $id Identifier of the identity to issue token for.
     * @param array $scopes List of scopes attached to the token.
     * @param mixed $cancellationToken The cancellation token to use.
     * @return CommunicationIdentityAccessToken
     * @throws \Exception
     */
    public function issueAccessTokenAsync(string $id, array $scopes, $cancellationToken = null): CommunicationIdentityAccessToken
    {
        $message = $this->createIssueAccessTokenRequest($id, $scopes);

        return $this->_pipeline
            ->sendAsync($message)
            ->then(function ($result) {
                /** @var \GuzzleHttp\Psr7\Response $response */
                [$request, $response] = $result;

                switch ($response->getStatusCode()) {
                    case 200:
                        $document = json_decode($response->getBody()->getContents(), true);

                        return CommunicationIdentityAccessToken::deserializeCommunicationIdentityAccessToken($document);
                    default:
                        throw new \Exception($response->getReasonPhrase());
                }
            })
            ->wait();
    }

    /**
     * Issue a new token for an identity.
     * @param string $id Identifier of the identity to issue token for.
     * @param array $scopes List of scopes attached to the token.
     * @param mixed $cancellationToken The cancellation token to use.
     * @return CommunicationIdentityAccessToken
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function issueAccessToken(string $id, array $scopes, $cancellationToken = null): CommunicationIdentityAccessToken
    {
        $message = $this->createIssueAccessTokenRequest($id, $scopes);
        $response = $this->_pipeline->send($message);
        switch ($response->getStatusCode()) {
            case 200:
                $document = json_decode($response->getBody()->getContents(), true);

                return CommunicationIdentityAccessToken::deserializeCommunicationIdentityAccessToken($document);
            default:
                throw new \Exception($response->getReasonPhrase());
        }
    }
}
