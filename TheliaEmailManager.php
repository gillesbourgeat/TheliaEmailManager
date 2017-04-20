<?php
/*
 * This file is part of the Symfony package.
 *
 * (c) Gilles Bourgeat <gilles.bourgeat@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TheliaEmailManager;

use Propel\Runtime\Connection\ConnectionInterface;
use Symfony\Component\Finder\Finder;
use Thelia\Install\Database;
use Thelia\Model\Resource;
use Thelia\Model\ResourceQuery;
use Thelia\Module\BaseModule;
use TheliaEmailManager\DataTransformer\EmailListTransformer;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class TheliaEmailManager extends BaseModule
{
    const DOMAIN_NAME = 'theliaemailmanager';

    const ROUTER = 'router.TheliaEmailManager';

    const SETUP_PATH = __DIR__ . DS . 'setup';

    const UPDATE_PATH = __DIR__ . DS . 'setup' . DS . 'update';

    const CONFIG_ENABLE_HISTORY = 'enable_history';

    const CONFIG_DISABLE_SENDING = 'disable_sending';

    const CONFIG_REDIRECT_ALL_TO = 'redirect_all_to';

    const RESOURCE_CONFIGURATION = 'admin.email-manager.configuration';

    const RESOURCE_TRACE = 'admin.email-manager.trace';

    const RESOURCE_HISTORY = 'admin.email-manager.history';

    const RESOURCE_EMAIL = 'admin.email-manager.email';

    const STATUS_SUCCESS = 1;

    const STATUS_BLOCKED = 2;

    const STATUS_ERROR = 3;

    protected static $config = [];

    /**
     * @inheritdoc
     */
    public function install(ConnectionInterface $con = null)
    {
        $database = new Database($con);

        $database->insertSql(
            null,
            [self::SETUP_PATH . DS . "tables.sql", self::SETUP_PATH . DS . "insert.sql"]
        );

        static::setEnableHistory(true);
        static::setDisableSending(false);
        static::setRedirectAllTo([]);

        $this->addResources();
    }

    /**
     * @inheritdoc
     */
    public function update($currentVersion, $newVersion, ConnectionInterface $con = null)
    {
        $finder = (new Finder())->files()->name('#.*?\.sql#')->sortByName()->in(self::UPDATE_PATH);

        if ($finder->count() === 0) {
            return;
        }

        $database = new Database($con);

        /** @var \Symfony\Component\Finder\SplFileInfo $updateSQLFile */
        foreach ($finder as $updateSQLFile) {
            if (version_compare($currentVersion, str_replace('.sql', '', $updateSQLFile->getFilename()), '<')) {
                $database->insertSql(null, [$updateSQLFile->getPathname()]);
            }
        }

        $this->addResources();
    }

    protected function addResources()
    {
        $resources = [
            self::RESOURCE_CONFIGURATION => [
                ['locale'=>'en_US','title'=>'Thelia email manager configuration'],
                ['locale'=>'fr_FR','title'=>'Configuration de Thelia email manager']
            ],
            self::RESOURCE_TRACE => [
                ['locale'=>'en_US','title'=>'Thelia email manager trace'],
                ['locale'=>'fr_FR','title'=>'Trace de Thelia email manager']
            ],
            self::RESOURCE_HISTORY => [
                ['locale'=>'en_US','title'=>'Thelia email manager history'],
                ['locale'=>'fr_FR','title'=>'Historique de Thelia email manager']
            ],
            self::RESOURCE_EMAIL => [
                ['locale'=>'en_US','title'=>'Thelia email manager email'],
                ['locale'=>'fr_FR','title'=>'Email de Thelia email manager']
            ]
        ];

        foreach ($resources as $code => $i18ns) {
            $resourceExist = ResourceQuery::create()
                ->findOneByCode($code);

            if (!$resourceExist) {
                $resource = (new Resource())
                    ->setCode($code);
                foreach ($i18ns as $i18n) {
                    $resource->setLocale($i18n['locale'])
                        ->setTitle($i18n['title'])
                        ->save();
                }
            }
        }

    }

    /**
     * @inheritdoc
     */
    public function destroy(ConnectionInterface $con = null, $deleteModuleData = false)
    {
        if ($deleteModuleData) {
            $database = new Database($con);

            $database->insertSql(
                null,
                [self::SETUP_PATH . DS . "uninstall.sql"]
            );
        }
    }

    /**
     * @inheritdoc
     */
    public static function getConfigValue($variableName, $defaultValue = null, $valueLocale = null)
    {
        if (isset(self::$config[$variableName])) {
            return self::$config[$variableName];
        }

        self::$config[$variableName] = parent::getConfigValue($variableName, $defaultValue, $valueLocale);

        return self::$config[$variableName];
    }

    /**
     * @inheritdoc
     */
    public static function setConfigValue($variableName, $variableValue, $valueLocale = null, $createIfNotExists = true)
    {
        unset(self::$config[$variableName]);
        parent::setConfigValue($variableName, $variableValue, $valueLocale, $createIfNotExists);
    }

    /**
     * @return bool
     */
    public static function getEnableHistory()
    {
        return static::getConfigValue(self::CONFIG_ENABLE_HISTORY, true) ? true : false;
    }

    /**
     * @param bool $bool true for enable all histories, false for disable all histories
     */
    public static function setEnableHistory($bool)
    {
        static::setConfigValue(self::CONFIG_ENABLE_HISTORY, (bool) $bool);
    }

    /**
     * @return bool
     */
    public static function getDisableSending()
    {
        return static::getConfigValue(self::CONFIG_DISABLE_SENDING, false) ? true : false;
    }

    /**
     * @param $bool true for disable sending all mails, false for enable sending all emails
     */
    public static function setDisableSending($bool)
    {
        static::setConfigValue(self::CONFIG_DISABLE_SENDING, (bool) $bool);
    }

    /**
     * @return string[] list of mail
     */
    public static function getRedirectAllTo()
    {
        return (new EmailListTransformer())
            ->reverseTransform(static::getConfigValue(self::CONFIG_REDIRECT_ALL_TO, ""));
    }

    /**
     * @param string[] $mails list of mail
     */
    public static function setRedirectAllTo(array $mails)
    {
        $mails = (new EmailListTransformer())->transform($mails);

        static::setConfigValue(self::CONFIG_REDIRECT_ALL_TO, $mails);
    }
}
