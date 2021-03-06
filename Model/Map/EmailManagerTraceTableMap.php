<?php

namespace TheliaEmailManager\Model\Map;

use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;
use TheliaEmailManager\Model\EmailManagerTrace;
use TheliaEmailManager\Model\EmailManagerTraceQuery;


/**
 * This class defines the structure of the 'email_manager_trace' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class EmailManagerTraceTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'TheliaEmailManager.Model.Map.EmailManagerTraceTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'thelia';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'email_manager_trace';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\TheliaEmailManager\\Model\\EmailManagerTrace';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'TheliaEmailManager.Model.EmailManagerTrace';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 14;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 14;

    /**
     * the column name for the ID field
     */
    const ID = 'email_manager_trace.ID';

    /**
     * the column name for the PARENT_ID field
     */
    const PARENT_ID = 'email_manager_trace.PARENT_ID';

    /**
     * the column name for the HASH field
     */
    const HASH = 'email_manager_trace.HASH';

    /**
     * the column name for the CLI field
     */
    const CLI = 'email_manager_trace.CLI';

    /**
     * the column name for the ENVIRONMENT field
     */
    const ENVIRONMENT = 'email_manager_trace.ENVIRONMENT';

    /**
     * the column name for the DISABLE_HISTORY field
     */
    const DISABLE_HISTORY = 'email_manager_trace.DISABLE_HISTORY';

    /**
     * the column name for the DISABLE_SENDING field
     */
    const DISABLE_SENDING = 'email_manager_trace.DISABLE_SENDING';

    /**
     * the column name for the FORCE_SAME_CUSTOMER_DISABLE field
     */
    const FORCE_SAME_CUSTOMER_DISABLE = 'email_manager_trace.FORCE_SAME_CUSTOMER_DISABLE';

    /**
     * the column name for the NUMBER_OF_CATCH field
     */
    const NUMBER_OF_CATCH = 'email_manager_trace.NUMBER_OF_CATCH';

    /**
     * the column name for the EMAIL_BCC field
     */
    const EMAIL_BCC = 'email_manager_trace.EMAIL_BCC';

    /**
     * the column name for the EMAIL_REDIRECT field
     */
    const EMAIL_REDIRECT = 'email_manager_trace.EMAIL_REDIRECT';

    /**
     * the column name for the DETAIL field
     */
    const DETAIL = 'email_manager_trace.DETAIL';

    /**
     * the column name for the CREATED_AT field
     */
    const CREATED_AT = 'email_manager_trace.CREATED_AT';

    /**
     * the column name for the UPDATED_AT field
     */
    const UPDATED_AT = 'email_manager_trace.UPDATED_AT';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    // i18n behavior

    /**
     * The default locale to use for translations.
     *
     * @var string
     */
    const DEFAULT_LOCALE = 'en_US';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'ParentId', 'Hash', 'Cli', 'Environment', 'DisableHistory', 'DisableSending', 'ForceSameCustomerDisable', 'NumberOfCatch', 'EmailBcc', 'EmailRedirect', 'Detail', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'parentId', 'hash', 'cli', 'environment', 'disableHistory', 'disableSending', 'forceSameCustomerDisable', 'numberOfCatch', 'emailBcc', 'emailRedirect', 'detail', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(EmailManagerTraceTableMap::ID, EmailManagerTraceTableMap::PARENT_ID, EmailManagerTraceTableMap::HASH, EmailManagerTraceTableMap::CLI, EmailManagerTraceTableMap::ENVIRONMENT, EmailManagerTraceTableMap::DISABLE_HISTORY, EmailManagerTraceTableMap::DISABLE_SENDING, EmailManagerTraceTableMap::FORCE_SAME_CUSTOMER_DISABLE, EmailManagerTraceTableMap::NUMBER_OF_CATCH, EmailManagerTraceTableMap::EMAIL_BCC, EmailManagerTraceTableMap::EMAIL_REDIRECT, EmailManagerTraceTableMap::DETAIL, EmailManagerTraceTableMap::CREATED_AT, EmailManagerTraceTableMap::UPDATED_AT, ),
        self::TYPE_RAW_COLNAME   => array('ID', 'PARENT_ID', 'HASH', 'CLI', 'ENVIRONMENT', 'DISABLE_HISTORY', 'DISABLE_SENDING', 'FORCE_SAME_CUSTOMER_DISABLE', 'NUMBER_OF_CATCH', 'EMAIL_BCC', 'EMAIL_REDIRECT', 'DETAIL', 'CREATED_AT', 'UPDATED_AT', ),
        self::TYPE_FIELDNAME     => array('id', 'parent_id', 'hash', 'cli', 'environment', 'disable_history', 'disable_sending', 'force_same_customer_disable', 'number_of_catch', 'email_bcc', 'email_redirect', 'detail', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'ParentId' => 1, 'Hash' => 2, 'Cli' => 3, 'Environment' => 4, 'DisableHistory' => 5, 'DisableSending' => 6, 'ForceSameCustomerDisable' => 7, 'NumberOfCatch' => 8, 'EmailBcc' => 9, 'EmailRedirect' => 10, 'Detail' => 11, 'CreatedAt' => 12, 'UpdatedAt' => 13, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'parentId' => 1, 'hash' => 2, 'cli' => 3, 'environment' => 4, 'disableHistory' => 5, 'disableSending' => 6, 'forceSameCustomerDisable' => 7, 'numberOfCatch' => 8, 'emailBcc' => 9, 'emailRedirect' => 10, 'detail' => 11, 'createdAt' => 12, 'updatedAt' => 13, ),
        self::TYPE_COLNAME       => array(EmailManagerTraceTableMap::ID => 0, EmailManagerTraceTableMap::PARENT_ID => 1, EmailManagerTraceTableMap::HASH => 2, EmailManagerTraceTableMap::CLI => 3, EmailManagerTraceTableMap::ENVIRONMENT => 4, EmailManagerTraceTableMap::DISABLE_HISTORY => 5, EmailManagerTraceTableMap::DISABLE_SENDING => 6, EmailManagerTraceTableMap::FORCE_SAME_CUSTOMER_DISABLE => 7, EmailManagerTraceTableMap::NUMBER_OF_CATCH => 8, EmailManagerTraceTableMap::EMAIL_BCC => 9, EmailManagerTraceTableMap::EMAIL_REDIRECT => 10, EmailManagerTraceTableMap::DETAIL => 11, EmailManagerTraceTableMap::CREATED_AT => 12, EmailManagerTraceTableMap::UPDATED_AT => 13, ),
        self::TYPE_RAW_COLNAME   => array('ID' => 0, 'PARENT_ID' => 1, 'HASH' => 2, 'CLI' => 3, 'ENVIRONMENT' => 4, 'DISABLE_HISTORY' => 5, 'DISABLE_SENDING' => 6, 'FORCE_SAME_CUSTOMER_DISABLE' => 7, 'NUMBER_OF_CATCH' => 8, 'EMAIL_BCC' => 9, 'EMAIL_REDIRECT' => 10, 'DETAIL' => 11, 'CREATED_AT' => 12, 'UPDATED_AT' => 13, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'parent_id' => 1, 'hash' => 2, 'cli' => 3, 'environment' => 4, 'disable_history' => 5, 'disable_sending' => 6, 'force_same_customer_disable' => 7, 'number_of_catch' => 8, 'email_bcc' => 9, 'email_redirect' => 10, 'detail' => 11, 'created_at' => 12, 'updated_at' => 13, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('email_manager_trace');
        $this->setPhpName('EmailManagerTrace');
        $this->setClassName('\\TheliaEmailManager\\Model\\EmailManagerTrace');
        $this->setPackage('TheliaEmailManager.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('PARENT_ID', 'ParentId', 'INTEGER', 'email_manager_trace', 'ID', false, null, null);
        $this->addColumn('HASH', 'Hash', 'CHAR', true, 32, null);
        $this->addColumn('CLI', 'Cli', 'BOOLEAN', false, 1, false);
        $this->addColumn('ENVIRONMENT', 'Environment', 'CHAR', true, 32, null);
        $this->addColumn('DISABLE_HISTORY', 'DisableHistory', 'BOOLEAN', false, 1, false);
        $this->addColumn('DISABLE_SENDING', 'DisableSending', 'BOOLEAN', false, 1, false);
        $this->addColumn('FORCE_SAME_CUSTOMER_DISABLE', 'ForceSameCustomerDisable', 'BOOLEAN', false, 1, false);
        $this->addColumn('NUMBER_OF_CATCH', 'NumberOfCatch', 'INTEGER', false, null, 0);
        $this->addColumn('EMAIL_BCC', 'EmailBcc', 'ARRAY', false, null, null);
        $this->addColumn('EMAIL_REDIRECT', 'EmailRedirect', 'ARRAY', false, null, null);
        $this->addColumn('DETAIL', 'Detail', 'LONGVARCHAR', true, null, null);
        $this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('EmailManagerTraceRelatedByParentId', '\\TheliaEmailManager\\Model\\EmailManagerTrace', RelationMap::MANY_TO_ONE, array('parent_id' => 'id', ), 'CASCADE', 'RESTRICT');
        $this->addRelation('EmailManagerTraceRelatedById', '\\TheliaEmailManager\\Model\\EmailManagerTrace', RelationMap::ONE_TO_MANY, array('id' => 'parent_id', ), 'CASCADE', 'RESTRICT', 'EmailManagerTracesRelatedById');
        $this->addRelation('EmailManagerHistory', '\\TheliaEmailManager\\Model\\EmailManagerHistory', RelationMap::ONE_TO_MANY, array('id' => 'trace_id', ), 'CASCADE', 'RESTRICT', 'EmailManagerHistories');
        $this->addRelation('EmailManagerTraceI18n', '\\TheliaEmailManager\\Model\\EmailManagerTraceI18n', RelationMap::ONE_TO_MANY, array('id' => 'id', ), 'CASCADE', null, 'EmailManagerTraceI18ns');
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'i18n' => array('i18n_table' => '%TABLE%_i18n', 'i18n_phpname' => '%PHPNAME%I18n', 'i18n_columns' => 'title, description', 'locale_column' => 'locale', 'locale_length' => '5', 'default_locale' => '', 'locale_alias' => '', ),
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', ),
        );
    } // getBehaviors()
    /**
     * Method to invalidate the instance pool of all tables related to email_manager_trace     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in ".$this->getClassNameFromBuilder($joinedTableTableMapBuilder)." instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
                EmailManagerTraceTableMap::clearInstancePool();
                EmailManagerHistoryTableMap::clearInstancePool();
                EmailManagerTraceI18nTableMap::clearInstancePool();
            }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {

            return (int) $row[
                            $indexType == TableMap::TYPE_NUM
                            ? 0 + $offset
                            : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
                        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? EmailManagerTraceTableMap::CLASS_DEFAULT : EmailManagerTraceTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     * @return array (EmailManagerTrace object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = EmailManagerTraceTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = EmailManagerTraceTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + EmailManagerTraceTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = EmailManagerTraceTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            EmailManagerTraceTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = EmailManagerTraceTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = EmailManagerTraceTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                EmailManagerTraceTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(EmailManagerTraceTableMap::ID);
            $criteria->addSelectColumn(EmailManagerTraceTableMap::PARENT_ID);
            $criteria->addSelectColumn(EmailManagerTraceTableMap::HASH);
            $criteria->addSelectColumn(EmailManagerTraceTableMap::CLI);
            $criteria->addSelectColumn(EmailManagerTraceTableMap::ENVIRONMENT);
            $criteria->addSelectColumn(EmailManagerTraceTableMap::DISABLE_HISTORY);
            $criteria->addSelectColumn(EmailManagerTraceTableMap::DISABLE_SENDING);
            $criteria->addSelectColumn(EmailManagerTraceTableMap::FORCE_SAME_CUSTOMER_DISABLE);
            $criteria->addSelectColumn(EmailManagerTraceTableMap::NUMBER_OF_CATCH);
            $criteria->addSelectColumn(EmailManagerTraceTableMap::EMAIL_BCC);
            $criteria->addSelectColumn(EmailManagerTraceTableMap::EMAIL_REDIRECT);
            $criteria->addSelectColumn(EmailManagerTraceTableMap::DETAIL);
            $criteria->addSelectColumn(EmailManagerTraceTableMap::CREATED_AT);
            $criteria->addSelectColumn(EmailManagerTraceTableMap::UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.PARENT_ID');
            $criteria->addSelectColumn($alias . '.HASH');
            $criteria->addSelectColumn($alias . '.CLI');
            $criteria->addSelectColumn($alias . '.ENVIRONMENT');
            $criteria->addSelectColumn($alias . '.DISABLE_HISTORY');
            $criteria->addSelectColumn($alias . '.DISABLE_SENDING');
            $criteria->addSelectColumn($alias . '.FORCE_SAME_CUSTOMER_DISABLE');
            $criteria->addSelectColumn($alias . '.NUMBER_OF_CATCH');
            $criteria->addSelectColumn($alias . '.EMAIL_BCC');
            $criteria->addSelectColumn($alias . '.EMAIL_REDIRECT');
            $criteria->addSelectColumn($alias . '.DETAIL');
            $criteria->addSelectColumn($alias . '.CREATED_AT');
            $criteria->addSelectColumn($alias . '.UPDATED_AT');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(EmailManagerTraceTableMap::DATABASE_NAME)->getTable(EmailManagerTraceTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(EmailManagerTraceTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(EmailManagerTraceTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new EmailManagerTraceTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a EmailManagerTrace or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or EmailManagerTrace object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EmailManagerTraceTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \TheliaEmailManager\Model\EmailManagerTrace) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(EmailManagerTraceTableMap::DATABASE_NAME);
            $criteria->add(EmailManagerTraceTableMap::ID, (array) $values, Criteria::IN);
        }

        $query = EmailManagerTraceQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { EmailManagerTraceTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { EmailManagerTraceTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the email_manager_trace table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return EmailManagerTraceQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a EmailManagerTrace or Criteria object.
     *
     * @param mixed               $criteria Criteria or EmailManagerTrace object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EmailManagerTraceTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from EmailManagerTrace object
        }

        if ($criteria->containsKey(EmailManagerTraceTableMap::ID) && $criteria->keyContainsValue(EmailManagerTraceTableMap::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.EmailManagerTraceTableMap::ID.')');
        }


        // Set the correct dbName
        $query = EmailManagerTraceQuery::create()->mergeWith($criteria);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = $query->doInsert($con);
            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

} // EmailManagerTraceTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
EmailManagerTraceTableMap::buildTableMap();
