<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication\Identity;

use Azure\Communication\CommunicationUserIdentifier;
use Azure\Communication\Identity\Models\CommunicationTokenScope;
use Azure\Communication\Identity\Models\GetTokenForTeamsUserOptions;
use Azure\Core\ConnectionString;

class CommunicationIdentityClient
{
    private $_restClient;

    /**
     * Initializes a new instance of CommunicationIdentityClient
     * @param string $connectionString Connection string acquired from the Azure Communication Services resource.
     * @param CommunicationIdentityClientOptions|null $options Client option exposing ClientOptions.Diagnostics, ClientOptions.Retry, ClientOptions.Transport etc.
     * @throws \Exception
     * @see CommunicationIdentityClientOptions
     */
    public function __construct(string $connectionString, CommunicationIdentityClientOptions $options = null)
    {
        $cs = ConnectionString::parse($connectionString);
        if (!$options) {
            $options = CommunicationIdentityClientOptions::default();
        }
        $this->_restClient = new CommunicationIdentityRestClient(
            $options::buildHttpPipeline($cs),
            $cs->getRequired('endpoint'),
            $options->getApiVersion()
        );
    }

    /**
     * Creates a new CommunicationUserIdentifier.
     * @param mixed $cancellationToken The cancellation token to use.
     * @return CommunicationUserIdentifier
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createUser($cancellationToken = null): CommunicationUserIdentifier
    {
        try {
            $identifier = $this->_restClient->create([], $cancellationToken);

            return $identifier->getUser();
        } catch (\Exception|\GuzzleHttp\Exception\GuzzleException $ex) {
            // todo Diagnostics
            throw;
        }
    }

    /**
     * Asynchronously creates a new CommunicationUserIdentifier.
     * @param mixed $cancellationToken The cancellation token to use.
     * @return CommunicationUserIdentifier
     */
    public function createUserAsync($cancellationToken = null): CommunicationUserIdentifier
    {
        try {
            $identifier = $this->_restClient->createAsync([], $cancellationToken);

            return $identifier->getUser();
        } catch (\Exception $ex) {
            // todo Diagnostics
            throw;
        }
    }

    /**
     * Creates a new CommunicationUserIdentifier.
     * @param CommunicationTokenScope[] $scopes The scopes that the token should have.
     * @param mixed $cancellationToken The cancellation token to use.
     */
    public function createUserAndToken(array $scopes = [], $cancellationToken = null): CommunicationUserIdentifierAndToken
    {
        try {
            return $this->_restClient->create($scopes, $cancellationToken);
        } catch (\Exception|\GuzzleHttp\Exception\GuzzleException $ex) {
            throw;
        }
    }

    /**
     * Asynchronously creates a new CommunicationUserIdentifier.
     * @param CommunicationTokenScope[] $scopes The scopes that the token should have.
     * @param mixed $cancellationToken The cancellation token to use.
     */
    public function createUserAndTokenAsync(array $scopes = [], $cancellationToken = null): CommunicationUserIdentifierAndToken
    {
        try {
            return $this->_restClient->createAsync($scopes, $cancellationToken);
        } catch (\Exception $ex) {
            throw;
        }
    }

    /**
     * Deletes a CommunicationUserIdentifier, revokes its token and deletes its data.
     * @param CommunicationUserIdentifier $communicationUser The user to be deleted.
     * @param mixed $cancellationToken The cancellation token to use.
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function deleteUser(CommunicationUserIdentifier $communicationUser, $cancellationToken = null): \Psr\Http\Message\ResponseInterface
    {
        try {
            return $this->_restClient->delete($communicationUser->getId(), $cancellationToken);
        } catch (\Exception|\GuzzleHttp\Exception\GuzzleException $ex) {
            throw;
        }
    }

    /**
     * Asynchronously deletes a CommunicationUserIdentifier, revokes its token and deletes its data.
     * @param CommunicationUserIdentifier $communicationUser The user to be deleted.
     * @param mixed $cancellationToken The cancellation token to use.
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function deleteUserAsync(CommunicationUserIdentifier $communicationUser, $cancellationToken = null): \Psr\Http\Message\ResponseInterface
    {
        try {
            return $this->_restClient->deleteAsync($communicationUser->getId(), $cancellationToken);
        } catch (\Exception $ex) {
            throw;
        }
    }

    /**
     * Gets a token for a CommunicationUserIdentifier.
     * @param CommunicationUserIdentifier $communicationUser The CommunicationUserIdentifier for whom to get a token.
     * @param mixed $cancellationToken The cancellation token to use.
     * @return CommunicationIdentityAccessToken
     */
    public function getToken(CommunicationUserIdentifier $communicationUser, $cancellationToken = null): CommunicationIdentityAccessToken
    {
        try {
            return $this->_restClient->issueAccessToken($communicationUser->getId(), $cancellationToken);
        } catch (\Exception|\GuzzleHttp\Exception\GuzzleException $ex) {
            throw;
        }
    }

    /**
     * Asynchronously gets a token for a CommunicationUserIdentifier.
     * @param CommunicationUserIdentifier $communicationUser The CommunicationUserIdentifier for whom to get a token.
     * @param mixed $cancellationToken The cancellation token to use.
     * @return CommunicationIdentityAccessToken
     */
    public function getTokenAsync(CommunicationUserIdentifier $communicationUser, $cancellationToken = null): CommunicationIdentityAccessToken
    {
        try {
            return $this->_restClient->issueAccessTokenAsync($communicationUser->getId(), $cancellationToken);
        } catch (\Exception $ex) {
            throw;
        }
    }

    /**
     * Revokes all the tokens created for a user.
     * @param CommunicationUserIdentifier $communicationUser The CommunicationUserIdentifier whose tokens will be revoked.
     * @param mixed $cancellationToken The cancellation token to use.
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function revokeTokens(CommunicationUserIdentifier $communicationUser, $cancellationToken = null): \Psr\Http\Message\ResponseInterface
    {
        try {
            return $this->_restClient->revokeAccessTokens($communicationUser->getId(), $cancellationToken);
        } catch (\Exception|\GuzzleHttp\Exception\GuzzleException $ex) {
            throw;
        }
    }

    /**
     * Asynchronously revokes all the tokens created for a user.
     * @param CommunicationUserIdentifier $communicationUser The CommunicationUserIdentifier whose tokens will be revoked.
     * @param mixed $cancellationToken The cancellation token to use.
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function revokeTokensAsync(CommunicationUserIdentifier $communicationUser, $cancellationToken = null): \Psr\Http\Message\ResponseInterface
    {
        try {
            return $this->_restClient->revokeAccessTokensAsync($communicationUser->getId(), $cancellationToken);
        } catch (\Exception $ex) {
            throw;
        }
    }

    /**
     * Exchange an Azure AD access token of a Teams User for a Communication Identity access token.
     * @param GetTokenForTeamsUserOptions $options GetTokenForTeamsUserOptions request options used to exchange an Azure AD access token of a Teams User for a new Communication Identity access token.
     * @param mixed $cancellationToken The cancellation token to use.
     * @return CommunicationIdentityAccessToken
     */
    public function getTokenForTeamsUser(GetTokenForTeamsUserOptions $options, $cancellationToken = null): CommunicationIdentityAccessToken
    {
        try {
            return $this->_restClient->exchangeTeamsUserAccessToken(
                $options->getTeamsUserAadToken(),
                $options->getClientId(),
                $options->getUserObjectId(),
                $cancellationToken
            );
        } catch (\Exception|\GuzzleHttp\Exception\GuzzleException $ex) {
            throw;
        }
    }

    /**
     * Asynchronously exchange an Azure AD access token of a Teams User for a Communication Identity access token.
     * @param GetTokenForTeamsUserOptions $options GetTokenForTeamsUserOptions request options used to exchange an Azure AD access token of a Teams User for a new Communication Identity access token.
     * @param mixed $cancellationToken The cancellation token to use.
     * @return CommunicationIdentityAccessToken
     */
    public function getTokenForTeamsUserAsync(GetTokenForTeamsUserOptions $options, $cancellationToken = null): CommunicationIdentityAccessToken
    {
        try {
            return $this->_restClient->exchangeTeamsUserAccessTokenAsync(
                $options->getTeamsUserAadToken(),
                $options->getClientId(),
                $options->getUserObjectId(),
                $cancellationToken
            );
        } catch (\Exception $ex) {
            throw;
        }
    }

    /**
     * @return CommunicationIdentityRestClient
     */
    private function getRestClient(): CommunicationIdentityRestClient
    {
        return $this->_restClient;
    }
}
