<?php
/**
 * gcal plugin for Craft CMS 3.x
 *
 * oAuth and routing for Google Gcal
 *
 * @link      michtio.dev
 * @copyright Copyright (c) 2019 Michael Thomas
 */

namespace michtio\gcal\services;


use yii\base\Component;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Provider\Google;
use craft\helpers\UrlHelper;
use michtio\gcal\Gcal;

/**
 * GcalService Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Michael Thomas
 * @package   Gcal
 * @since     1.0.0
 */
class Oauth extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     Gcal::$plugin->GcalService->exampleService()
     *
     * @return mixed
     */

    /**
     * Save Token
     *
     * @param AccessToken $token
     */
    public function saveToken(AccessToken $token)
    {
        $info = Gcal::getInstance()->getInfo();

        $info->token = [
            'accessToken' => $token->getToken(),
            'expires' => $token->getExpires(),
            'refreshToken' => $token->getRefreshToken(),
            'resourceOwnerId' => $token->getResourceOwnerId(),
            'values' => $token->getValues(),
        ];

        Gcal::getInstance()->saveInfo($info);
    }

    /**
     * Returns the OAuth Token.
     *
     * @param bool $refresh
     *
     * @return AccessToken|null
     */
    public function getToken($refresh = true)
    {
        $info = Gcal::getInstance()->getInfo();

        if (!$info->token) {
            return null;
        }

        $token = new AccessToken([
            'access_token' => $info->token['accessToken'] ?? null,
            'expires' => $info->token['expires'] ?? null,
            'refresh_token' => $info->token['refreshToken'] ?? null,
            'resource_owner_id' => $info->token['resourceOwnerId'] ?? null,
            'values' => $info->token['values'] ?? null,
        ]);

        if ($refresh && $token->hasExpired()) {
            $provider = $this->getOauthProvider();
            $grant = new \League\OAuth2\Client\Grant\RefreshToken();
            $newToken = $provider->getAccessToken($grant, ['refresh_token' => $token->getRefreshToken()]);

            $token = new AccessToken([
                'access_token' => $newToken->getToken(),
                'expires' => $newToken->getExpires(),
                'refresh_token' => $info->token['refreshToken'],
                'resource_owner_id' => $newToken->getResourceOwnerId(),
                'values' => $newToken->getValues(),
            ]);

            $this->saveToken($token);
        }

        return $token;
    }

    /**
     * Delete Token
     *
     * @return bool
     */
    public function deleteToken()
    {
        $info = Gcal::getInstance()->getInfo();
        $info->token = null;
        Gcal::getInstance()->saveInfo($info);
        return true;
    }

    /**
     * Returns a Twitter provider (server) object.
     *
     * @return Google
     */
    public function getOauthProvider()
    {
        $options = [
            'clientId' => Gcal::$plugin->getSettings()->oauthClientId,
            'clientSecret' => Gcal::$plugin->getSettings()->oauthClientSecret
        ];

        $options = array_merge($options, Gcal::$plugin->getSettings()->oauthProviderOptions);

        if (!isset($options['redirectUri'])) {
            $options['redirectUri'] = $this->getRedirectUri();
        }

        return new Google($options);
    }

    /**
     * Returns the javascript orgin URL.
     *
     * @return string
     * @throws \craft\errors\SiteNotFoundException
     */
    public function getJavascriptOrigin()
    {
        return UrlHelper::baseUrl();
    }

    /**
     * Returns the redirect URI.
     *
     * @return string
     */
    public function getRedirectUri()
    {
        return UrlHelper::actionUrl('gcal/oauth/callback');
    }

}
