<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication\Identity;

use Azure\Core\ConnectionString;
use GuzzleHttp\Exception\GuzzleException;
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
     * @see RequestOptions
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
     * @throws GuzzleException|\Exception
     */
    public function CreateUserAndToken(array $scopes = []): UserIdentifierAndToken
    {
        foreach ($scopes as $scope) {
            if ($scope !== self::SCOPE_CHAT && $scope !== self::SCOPE_VOIP) {
                throw new \Exception("Invalid scope '$scope'");
            }
        }
        $body = [
            'createTokenWithScopes' => []
        ];
        if (!empty($scopes)) {
            $body['createTokenWithScopes'] = array_values($scopes);
        }

        $response = $this->_restClient->post('identities', [
            RequestOptions::JSON => $body
        ]);

        return UserIdentifierAndToken::fromJson($response->getBody()->getContents());
    }

    /**
     * Delete the identity, revoke all tokens for the identity and delete all associated data.
     * @param UserIdentifierAndToken|string $communicationUser The user to be deleted.
     * @return boolean
     * @throws GuzzleException
     */
    public function deleteUser(UserIdentifierAndToken|string $communicationUser): bool
    {
        if ($communicationUser instanceof UserIdentifierAndToken) {
            $communicationUser = $communicationUser->identity->id;
        }

        $response = $this->_restClient->delete('identities/' . $communicationUser);

        return str_starts_with($response->getStatusCode(), '2');
    }

    /**
     * Exchange an Azure Active Directory (Azure AD) access token of a Teams user for a new Communication Identity access token with a matching expiration time.
     * @param string $appId Client ID of an Azure AD application to be verified against the appid claim in the Azure AD access token.
     * @param string $token Azure AD access token of a Teams User to acquire a new Communication Identity access token.
     * @param string $userId Object ID of an Azure AD user (Teams User) to be verified against the oid claim in the Azure AD access token.
     * @return void
     * @throws \Exception
     */
    public function exchangeAccessToken(string $appId, string $token, string $userId): void
    {
        throw new \Exception('Not implemented yet');
    }

    /**
     * Issue a new token for an identity.
     * @param UserIdentifierAndToken $communicationUser Identifier of the identity to issue token for.
     * @param array $scopes Also create access token for the created identity. One of the Client::SCOPE_* constants.
     * @return UserIdentifierAndToken
     * @throws GuzzleException
     * @throws \Exception
     */
    public function issueToken(UserIdentifierAndToken $communicationUser, array $scopes): UserIdentifierAndToken
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
            RequestOptions::JSON => $body
        ]);
        $communicationUser->accessToken = AccessToken::fromJson($response->getBody()->getContents());

        return $communicationUser;
    }

    /**
     * Revoke all access tokens for the specific identity.
     * @param UserIdentifierAndToken $communicationUser Identifier of the identity.
     * @return bool
     * @throws GuzzleException
     */
    public function revokeTokens(UserIdentifierAndToken $communicationUser): bool
    {
        $response = $this->_restClient->post('identities/' . $communicationUser->identity->id . '/:revokeAccessTokens');
        return str_starts_with($response->getStatusCode(), '2');
    }
}
