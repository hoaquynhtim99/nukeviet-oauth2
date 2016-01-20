<?php

namespace OAuth\OAuth2\Service;

use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\OAuth2\Token\StdOAuth2Token;

class NukeViet extends AbstractService
{
    /**
     * Defined scopes
     */
    const SCOPE_EMAIL                         = 'email';

    protected $baseApiUri = 'http://server.oauth2.nukeviet.vn/oauth2/resource';
    protected $authorizationEndpoint = 'http://server.oauth2.nukeviet.vn/oauth2/authorize';
    protected $accessTokenEndpoint = 'http://server.oauth2.nukeviet.vn/oauth2/token';
    protected $authorizationMethod = self::AUTHORIZATION_METHOD_HEADER_BEARER;

    /**
     * {@inheritdoc}
     */
    protected function parseAccessTokenResponse($responseBody)
    {
        $data = json_decode($responseBody, true);
        
		if (null === $data || !is_array($data)) {
            throw new TokenResponseException('Unable to parse response.');
        } elseif (isset($data['error'])) {
            throw new TokenResponseException('Error in retrieving token: "' . $data['error'] . '"');
        }

        $token = new StdOAuth2Token();
        $token->setAccessToken($data['access_token']);
        $token->setLifetime($data['expires_in']);

        if (isset($data['refresh_token'])) {
            $token->setRefreshToken($data['refresh_token']);
            unset($data['refresh_token']);
        }

        unset($data['access_token']);
        unset($data['expires_in']);

        $token->setExtraParams($data);

        return $token;
    }
}
