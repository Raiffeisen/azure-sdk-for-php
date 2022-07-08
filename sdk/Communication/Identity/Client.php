<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication\Identity;

use Azure\Core\ConnectionString;
use GuzzleHttp\RequestOptions;

class Client
{
    public const SCOPE_CHAT = 'chat';
    public const SCOPE_VOIP = 'voip';

    /**
     * @var RestClient The rest connection
     */
    private RestClient $_restClient;

    /**
     * Initializes a new instance of CommunicationIdentityClient
     * @param string $connectionString Connection string acquired from the Azure Communication Services resource.
     * @param array $options Client option exposing apiVersion, Guzzle http client options
     * @throws \Exception
     * @see \GuzzleHttp\RequestOptions
     */
    public function __construct(string $connectionString, array $options = [])
    {
        $cs = ConnectionString::parse($connectionString);

        $config = $options['clientConfig'] ?? [];
        if (isset($options['apiVersion'])) {
            $this->_restClient = new RestClient(
                $cs->getRequired('endpoint'),
                $cs->getRequired('accesskey'),
                $config,
                $options['apiVersion']
            );
        } else {
            $this->_restClient = new RestClient(
                $cs->getRequired('endpoint'),
                $cs->getRequired('accesskey'),
                $config
            );
        }
    }

    /**
     * Create a new identity.
     * @param string[] $scopes Also create access token for the created identity. One of the Client::SCOPE_* constants.
     * @return UserIdentifierAndToken
     * @throws \GuzzleHttp\Exception\GuzzleException|\Exception
     */
    public function createUser(array $scopes = []): UserIdentifierAndToken
    {
        foreach ($scopes as $scope) {
            if ($scope !== self::SCOPE_CHAT && $scope !== self::SCOPE_VOIP) {
                throw new \Exception("Invalid scope '$scope'");
            }
        }
        $body = ['createTokenWithScopes' => []];
        if (!empty($scopes)) {
            $body['createTokenWithScopes'] = array_values($scopes);
        }

        $response = $this->_restClient->post('identities', [
            RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            RequestOptions::JSON => $body
        ]);

        return UserIdentifierAndToken::fromJson($response->getBody()->getContents());
    }

    /**
     * Delete the identity, revoke all tokens for the identity and delete all associated data.
     * @param UserIdentifierAndToken $communicationUser The user to be deleted.
     * @return boolean
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteUser(UserIdentifierAndToken $communicationUser): bool
    {
        $response = $this->_restClient->delete('identities/' . $communicationUser->identity->id, [
            RequestOptions::HEADERS => [
                'Accept' => 'application/json'
            ]
        ]);

        return substr($response->getStatusCode(), 0, 1) === '2';
    }

    /**
     * Issue a new token for an identity.
     * @param UserIdentifierAndToken $communicationUser Identifier of the identity to issue token for.
     * @param array $scopes Also create access token for the created identity. One of the Client::SCOPE_* constants.
     * @return UserIdentifierAndToken
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function getToken(UserIdentifierAndToken $communicationUser, array $scopes): UserIdentifierAndToken
    {
        foreach ($scopes as $scope) {
            if ($scope !== self::SCOPE_CHAT && $scope !== self::SCOPE_VOIP) {
                throw new \Exception("Invalid scope '$scope'");
            }
        }
        $body = ['scopes' => []];
        if (!empty($scopes)) {
            $body['scopes'] = array_values($scopes);
        }

        $response = $this->_restClient->post('identities/' . $communicationUser->identity->id . '/:issueAccessToken', [
            RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            RequestOptions::JSON => $body
        ]);
        $communicationUser->accessToken = AccessToken::fromJson($response->getBody()->getContents());

        return $communicationUser;
    }

    /**
     * Revoke all access tokens for the specific identity.
     * @param UserIdentifierAndToken $communicationUser Identifier of the identity.
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function revokeTokens(UserIdentifierAndToken $communicationUser): bool
    {
        $response = $this->_restClient->post('identities/' . $communicationUser->identity->id . '/:revokeAccessTokens', [
            RequestOptions::HEADERS => [
                'Accept' => 'application/json'
            ]
        ]);

        return substr($response->getStatusCode(), 0, 1) === '2';
    }
}
