<?php

namespace TheliaMailManager;

use Propel\Runtime\Connection\ConnectionInterface;
use Symfony\Component\Finder\Finder;
use Thelia\Install\Database;
use Thelia\Module\BaseModule;
use TheliaMailManager\Util\MailUtil;

/**
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class TheliaMailManager extends BaseModule
{
    /** @var string */
    const DOMAIN_NAME = 'theliamailmanager';

    /** @var string */
    const SETUP_PATH = __DIR__ . DS . 'setup';

    /** @var string */
    const UPDATE_PATH = __DIR__ . DS . 'setup' . DS . 'update';

    const CONFIG_ENABLE_HISTORY = 'enable_history';
    const CONFIG_DISABLE_SEND = 'disable_send';
    const CONFIG_REDIRECT_ALL_TO = 'redirect_all_to';

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
        static::setDisableSend(false);
        static::setRedirectAllTo([]);
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
    public static function getDisableSend()
    {
        return static::getConfigValue(self::CONFIG_DISABLE_SEND, false) ? true : false;
    }

    /**
     * @param $bool true for disable sending all mails, false for enable sending all emails
     */
    public static function setDisableSend($bool)
    {
        static::setConfigValue(self::CONFIG_DISABLE_SEND, (bool) $bool);
    }

    /**
     * @return string[] list of mail
     */
    public static function getRedirectAllTo()
    {
        $mails = explode(',', static::getConfigValue(self::CONFIG_REDIRECT_ALL_TO, ""));

        if (!MailUtil::checkMailStructure($mails[0])) {
            return [];
        }

        return $mails;
    }

    /**
     * @param string[] $mails list of mail
     */
    public static function setRedirectAllTo(array $mails)
    {
        foreach ($mails as $mail) {
            if (!MailUtil::checkMailStructure($mail)) {
                throw new \InvalidArgumentException('Invalid email : ' . $mail);
            }
        }

        static::setConfigValue(self::CONFIG_REDIRECT_ALL_TO, implode(',', $mails));
    }
}
