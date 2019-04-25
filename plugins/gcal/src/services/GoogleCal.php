<?php
/**
 * gcal plugin for Craft CMS 3.x
 *
 * oAuth and routing for Google Calendar
 *
 * @link      michtio.dev
 * @copyright Copyright (c) 2019 Michael Thomas
 */

namespace michtio\gcal\services;

use michtio\gcal\Gcal;

use Craft;
use craft\base\Component;

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
class GoogleCal extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * Returns the element URL path.
     *
     * @param int      $elementId
     * @param int|null $siteId
     *
     * @return string
     */
    public function getElementUrlPath($elementId, $siteId): string
    {
        $element = Craft::$app->elements->getElementById($elementId, null, $siteId);

        $url = $element->url;

        $components = parse_url($url);

        return substr($url, \strlen($components['scheme'].'://'.$components['host']));
    }

    /**
     * Returns D3 format locale definition.
     *
     * @param array $options
     *
     * @return array
     */
    public function getD3LocaleDefinition(array $options = [])
    {
        // Figure out which D3 i18n script to load

        $language = Craft::$app->language;

        if (in_array($language, ['ca-ES', 'de-CH', 'de-DE', 'en-CA', 'en-GB', 'en-US', 'es-ES', 'fi-FI', 'fr-CA', 'fr-FR', 'he-IL', 'hu-HU', 'it-IT', 'ja-JP', 'ko-KR', 'nl-NL', 'pl-PL', 'pt-BR', 'ru-RU', 'sv-SE', 'zh-CN'], true)) {
            $d3Language = $language;
        } else {
            $languageId = Craft::$app->getLocale()->getLanguageID();

            $d3LanguageIds = [
                'ca' => 'ca-ES',
                'de' => 'de-DE',
                'en' => 'en-US',
                'es' => 'es-ES',
                'fi' => 'fi-FI',
                'fr' => 'fr-FR',
                'he' => 'he-IL',
                'hu' => 'hu-HU',
                'it' => 'it-IT',
                'ja' => 'ja-JP',
                'ko' => 'ko-KR',
                'nl' => 'nl-NL',
                'pl' => 'pl-PL',
                'pt' => 'pt-BR',
                'ru' => 'ru-RU',
                'sv' => 'sv-SE',
                'zh' => 'zh-CN',
            ];

            if (array_key_exists($language, $d3LanguageIds)) {
                $d3Language = $d3LanguageIds[$language];
            } else {
                if (array_key_exists($languageId, $d3LanguageIds)) {
                    $d3Language = $d3LanguageIds[$languageId];
                } else {
                    $d3Language = 'en-US';
                }
            }
        }

        $formatLocalePath = Craft::getAlias('@lib') . "/d3-format/{$d3Language}.json";

        $localeDefinition = json_decode(file_get_contents($formatLocalePath), true);

        if (isset($options['currency'])) {
            $localeDefinition['currency'] = $this->getD3LocaleDefinitionCurrency($options['currency']);
        }

        return $localeDefinition;

    }

    /**
     * Checks if the OAuth provider is configured.
     *
     * @return bool
     */
    public function isOauthProviderConfigured()
    {
        $oauthClientId = Gcal::$plugin->getSettings()->oauthClientId;
        $oauthClientSecret = Gcal::$plugin->getSettings()->oauthClientSecret;

        return !empty($oauthClientId) && !empty($oauthClientSecret);
    }

    /**
     * Checks plugin requirements (dependencies, configured OAuth provider, and token).
     *
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function checkPluginRequirements()
    {
        if ($this->isOauthProviderConfigured()) {
            if ($this->isTokenSet()) {
                return true;
            }

            return false;
        }

        return false;
    }

    // Private Methods
    // =========================================================================

    /**
     * Checks if the token is set.
     *
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    private function isTokenSet()
    {
        $token = Gcal::$plugin->getOauth()->getToken(false);

        if ($token) {
            return true;
        }

        return false;
    }


    /**
     * _getGclid get the `gclid` and sets the 'gclid' cookie
     */
    private function _getGclid()
    {
        $gclid = '';
        if (isset($_GET['gclid'])) {
            $gclid = $_GET['gclid'];
            if (!empty($gclid)) {
                setcookie('gclid', $gclid, time() + (10 * 365 * 24 * 60 * 60), '/');
            }
        }

        return $gclid;
    } /* -- _getGclid */
}
