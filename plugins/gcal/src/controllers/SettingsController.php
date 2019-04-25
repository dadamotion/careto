<?php
/**
 * gcal plugin for Craft CMS 3.x
 *
 * oAuth and routing for Google Calendar
 *
 * @link      michtio.dev
 * @copyright Copyright (c) 2019 Michael Thomas
 */

namespace michtio\gcal\controllers;

use Craft;
use craft\errors\InvalidPluginException;
use craft\web\Controller;
use michtio\gcal\web\assets\settings\SettingsAsset;
use michtio\gcal\Gcal;
use Exception;
use Google_Service_Exception;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use yii\web\Response;

/**
 * Gcal Settings Model
 *
 * This is a model used to define the plugin's settings.
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Michael Thomas
 * @package   Gcal
 * @since     1.0.0
 */
class SettingsController extends Controller
{

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function actionIndex(): Response
    {
        $isOauthProviderConfigured = Gcal::$plugin->getCalendar()->isOauthProviderConfigured();

        if ($isOauthProviderConfigured) {
            $errors = [];

            try {
                $provider = Gcal::$plugin->oauth->getOauthProvider();
                $token = Gcal::$plugin->oauth->getToken();

                if ($token) {
                    $oauthAccount = Gcal::$plugin->cache->get(['getAccount', $token]);

                    if (!$oauthAccount) {
                        $oauthAccount = $provider->getResourceOwner($token);
                        Gcal::$plugin->cache->set(['getAccount', $token], $oauthAccount);
                    }

                    if ($oauthAccount) {
                        Craft::info("Account:\r\n".print_r($oauthAccount, true), __METHOD__);

                        $plugin = Craft::$app->getPlugins()->getPlugin('gcal');
                        $settings = $plugin->getSettings();
                    }
                }
            } catch (Google_Service_Exception $e) {
                Craft::info('Couldn’t get OAuth account: '.$e->getMessage(), __METHOD__);

                foreach ($e->getErrors() as $error) {
                    $errors[] = $error['message'];
                }
            } catch (IdentityProviderException $e) {
                $error = $e->getMessage();
                $data = $e->getResponseBody();

                if (isset($data['error_description'])) {
                    $error = $data['error_description'];
                }

                $errors[] = $error;
            } catch (Exception $e) {
                if (method_exists($e, 'getResponse')) {
                    Craft::info('Couldn’t get OAuth account: '.$e->getResponse(), __METHOD__);
                } else {
                    Craft::info('Couldn’t get OAuth account: '.$e->getMessage(), __METHOD__);
                }

                $errors[] = $e->getMessage();
            }
        }

        Craft::$app->getView()->registerAssetBundle(SettingsAsset::class);

        return $this->renderTemplate('gcal/settings/settings', [
            'isOauthProviderConfigured' => $isOauthProviderConfigured,
            'errors' => $errors ?? null,
            'oauthAccount' => $oauthAccount ?? null,
            'settings' => $settings ?? null,
            'info' => Gcal::getInstance()->getInfo(),
            'googleIconUrl' => Craft::$app->assetManager->getPublishedUrl('@michtio/gcal/icons/google.svg', true),
        ]);
    }

    /**
     * OAuth Settings.
     *
     * @return Response
     * @throws \craft\errors\SiteNotFoundException
     */
    public function actionOauth(): Response
    {
        return $this->renderTemplate('gcal/settings/_oauth', [
            'javascriptOrigin' => Gcal::$plugin->oauth->getJavascriptOrigin(),
            'redirectUri' => Gcal::$plugin->oauth->getRedirectUri(),
            'googleIconUrl' => Gcal::$app->assetManager->getPublishedUrl('@michtio/gcal/icons/google.svg', true),
            'settings' => Gcal::$plugin->getSettings(),
        ]);
    }

    /**
     * Saves the settings.
     *
     * @return null|Response
     * @throws InvalidPluginException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionSaveSettings()
    {
        $this->requirePostRequest();

        $pluginHandle = Craft::$app->getRequest()->getRequiredBodyParam('pluginHandle');
        $settings = Craft::$app->getRequest()->getBodyParam('settings');
        $plugin = Craft::$app->getPlugins()->getPlugin($pluginHandle);

        if (!$plugin) {
            throw new InvalidPluginException($pluginHandle);
        }

        $settings = Gcal::$plugin->getApis()->getCalendar()->populateAccountExplorerSettings($settings);

        if (Craft::$app->getPlugins()->savePluginSettings($plugin, $settings)) {
            Craft::$app->getSession()->setNotice(Craft::t('gcal', 'Plugin settings saved.'));

            return $this->redirectToPostedUrl();
        }

        Craft::$app->getSession()->setError(Craft::t('gcal', 'Couldn’t save plugin settings.'));

        // Send the plugin back to the template
        Craft::$app->getUrlManager()->setRouteParams([
            'plugin' => $plugin
        ]);

        return null;
    }
}
