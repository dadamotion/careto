<?php
/**
 * gcal plugin for Craft CMS 3.x
 *
 * oAuth and routing for Google Calendar
 *
 * @link      michtio.dev
 * @copyright Copyright (c) 2019 Michael Thomas
 */

namespace michtio\gcal\models;

use craft\base\models;

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
class Settings extends Model
{

    /**
     * @var string|null The Google API application’s OAuth client ID.
     */
    public $oauthClientId;

    /**
     * @var string|null The Google API application’s OAuth client Secret.
     */
    public $oauthClientSecret;

    /**
     * @var array OAuth provider options.
     */
    public $oauthProviderOptions = [];

}
