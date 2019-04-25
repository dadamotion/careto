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

use michtio\gcal\Gcal;

use craft\base\Model;
use craft\helpers\Json;

/**
 * GcalModel Model
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

class Info extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var int|null ID
     */
    public $id;

    /**
     * @var bool Force connect
     */
    public $forceConnect = false;

    /**
     * @var string|null Token
     */
    public $token;

    /**
     * @var \DateTime|null Date updated
     */
    public $dateUpdated;

    /**
     * @var \DateTime|null Date updated
     */
    public $dateCreated;

    /**
     * @var string|null Uid
     */
    public $uid;

    // Public Methods
    // =========================================================================

    public function init() {
        parent::init();

        // Make sure $forceConnect is a boolean
        if (is_string($this->forceConnect)) {
            $this->forceConnect = (bool)$this->forceConnect;
        }

        if (is_string($this->token)) {
            $this->token = Json::decode($this->token);
        }
    }

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
    public function rules()
    {
        return [
            ['id', 'number', 'integerOnly' => true],
        ];
    }
}
