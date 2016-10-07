<?php

namespace TheliaMailManager;

use Propel\Runtime\Connection\ConnectionInterface;
use Symfony\Component\Finder\Finder;
use Thelia\Install\Database;
use Thelia\Module\BaseModule;

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
}
