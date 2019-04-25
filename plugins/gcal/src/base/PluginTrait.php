<?php

namespace michtio\gcal\base;

use Craft;
use craft\db\Query;
use craft\helpers\Db;
use michtio\gcal\models\Info;
use michtio\gcal\Gcal;

/**
 * PluginTrait implements the common methods and properties for plugin classes.
 *
 * @property \michtio\gcal\services\Gcal                        $gcal                       The gcal service
 * @property \michtio\gcal\services\Apis                        $apis                       The apis service
 * @property \michtio\gcal\Oauth                                $oauth                      The oauth service
 */
trait PluginTrait
{
    // Properties
    // =========================================================================

    /**
     * @var
     */
    private $_info;

    /**
     * @var
     */
    private $_isInstalled;

    // Public Methods
    // =========================================================================

    /**
     * Returns the gcal service.
     *
     * @return \michtio\gcal\services\GoogleCal The gcal service
     * @throws \yii\base\InvalidConfigException
     */
    public function getCalendar()
    {
        /** @var Gcal $this */
        return $this->get('gcal');
    }

    /**
     * Returns the apis service.
     *
     * @return \michtio\gcal\services\Apis The apis service
     * @throws \yii\base\InvalidConfigException
     */
    public function getApis()
    {
        /** @var Gcal $this */
        return $this->get('apis');
    }

    /**
     * Returns the oauth service.
     *
     * @return \michtio\gcal\services\Oauth The oauth service
     * @throws \yii\base\InvalidConfigException
     */
    public function getOauth()
    {
        /** @var Gcal $this */
        return $this->get('oauth');
    }

    /**
     * Updates the info row.
     *
     * @param Info $info
     *
     * @return bool
     * @throws \yii\db\Exception
     */
    public function saveInfo(Info $info): bool
    {
        $attributes = Db::prepareValuesForDb($info);

        if (array_key_exists('id', $attributes) && $attributes['id'] === null) {
            unset($attributes['id']);
        }

        if ($this->getIsInstalled()) {
            Craft::$app->getDb()->createCommand()
                ->update('{{%gcal_info}}', $attributes)
                ->execute();
        } else {
            Craft::$app->getDb()->createCommand()
                ->insert('{{%gcal_info}}', $attributes)
                ->execute();

            if (Craft::$app->getIsInstalled()) {
                // Set the new id
                $info->id = Craft::$app->getDb()->getLastInsertID('{{%gcal_info}}');
            }
        }

        $this->_info = $info;

        return true;
    }

    /**
     * Returns the info model, or just a particular attribute.
     *
     * @return Info
     * @throws ServerErrorHttpException if the info table is missing its row
     */
    public function getInfo(): Info
    {
        /** @var WebApplication|ConsoleApplication $this */
        if ($this->_info !== null) {
            return $this->_info;
        }

        if (!$this->getIsInstalled()) {
            return new Info();
        }

        $row = (new Query())
            ->from(['{{%gcal_info}}'])
            ->one();

        if (!$row) {
            $tableName = $this->getDb()->getSchema()->getRawTableName('{{%gcal_info}}');
            throw new ServerErrorHttpException("The {$tableName} table is missing its row");
        }

        return $this->_info = new Info($row);
    }

    /**
     * Returns whether Craft is installed.
     *
     * @return bool
     */
    public function getIsInstalled(): bool
    {
        /** @var WebApplication|ConsoleApplication $this */
        if ($this->_isInstalled !== null) {
            return $this->_isInstalled;
        }

        $infoRowExists = false;

        if(Craft::$app->getDb()->tableExists('{{%gcal_info}}', false)) {
            $infoRowExists = (new Query())
                ->from(['{{%gcal_info}}'])
                ->one();
        }

        return $this->_isInstalled = (
            Craft::$app->getIsDbConnectionValid() &&
            $infoRowExists
        );
    }
}
