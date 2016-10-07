<?php

namespace TheliaMailManager\Model\Map;

use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;
use TheliaMailManager\Model\MailManagerTrace;
use TheliaMailManager\Model\MailManagerTraceQuery;


/**
 * This class defines the structure of the 'mail_manager_trace' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class MailManagerTraceTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'TheliaMailManager.Model.Map.MailManagerTraceTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'thelia';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'mail_manager_trace';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\TheliaMailManager\\Model\\MailManagerTrace';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'TheliaMailManager.Model.MailManagerTrace';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 11;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 11;

    /**
     * the column name for the ID field
     */
    const ID = 'mail_manager_trace.ID';

    /**
     * the column name for the HASH field
     */
    const HASH = 'mail_manager_trace.HASH';

    /**
     * the column name for the DISABLE_HISTORY field
     */
    const DISABLE_HISTORY = 'mail_manager_trace.DISABLE_HISTORY';

    /**
     * the column name for the DISABLE_SENDING field
     */
    const DISABLE_SENDING = 'mail_manager_trace.DISABLE_SENDING';

    /**
     * the column name for the FORCE_SAME_CUSTOMER_DISABLE field
     */
    const FORCE_SAME_CUSTOMER_DISABLE = 'mail_manager_trace.FORCE_SAME_CUSTOMER_DISABLE';

    /**
     * the column name for the NUMBER_OF_CATCH field
     */
    const NUMBER_OF_CATCH = 'mail_manager_trace.NUMBER_OF_CATCH';

    /**
     * the column name for the EMAIL_BCC field
     */
    const EMAIL_BCC = 'mail_manager_trace.EMAIL_BCC';

    /**
     * the column name for the EMAIL_REDIRECT field
     */
    const EMAIL_REDIRECT = 'mail_manager_trace.EMAIL_REDIRECT';

    /**
     * the column name for the DETAIL field
     */
    const DETAIL = 'mail_manager_trace.DETAIL';

    /**
     * the column name for the CREATED_AT field
     */
    const CREATED_AT = 'mail_manager_trace.CREATED_AT';

    /**
     * the column name for the UPDATED_AT field
     */
    const UPDATED_AT = 'mail_manager_trace.UPDATED_AT';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Hash', 'DisableHistory', 'DisableSending', 'ForceSameCustomerDisable', 'NumberOfCatch', 'EmailBcc', 'EmailRedirect', 'Detail', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'hash', 'disableHistory', 'disableSending', 'forceSameCustomerDisable', 'numberOfCatch', 'emailBcc', 'emailRedirect', 'detail', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(MailManagerTraceTableMap::ID, MailManagerTraceTableMap::HASH, MailManagerTraceTableMap::DISABLE_HISTORY, MailManagerTraceTableMap::DISABLE_SENDING, MailManagerTraceTableMap::FORCE_SAME_CUSTOMER_DISABLE, MailManagerTraceTableMap::NUMBER_OF_CATCH, MailManagerTraceTableMap::EMAIL_BCC, MailManagerTraceTableMap::EMAIL_REDIRECT, MailManagerTraceTableMap::DETAIL, MailManagerTraceTableMap::CREATED_AT, MailManagerTraceTableMap::UPDATED_AT, ),
        self::TYPE_RAW_COLNAME   => array('ID', 'HASH', 'DISABLE_HISTORY', 'DISABLE_SENDING', 'FORCE_SAME_CUSTOMER_DISABLE', 'NUMBER_OF_CATCH', 'EMAIL_BCC', 'EMAIL_REDIRECT', 'DETAIL', 'CREATED_AT', 'UPDATED_AT', ),
        self::TYPE_FIELDNAME     => array('id', 'hash', 'disable_history', 'disable_sending', 'force_same_customer_disable', 'number_of_catch', 'email_bcc', 'email_redirect', 'detail', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Hash' => 1, 'DisableHistory' => 2, 'DisableSending' => 3, 'ForceSameCustomerDisable' => 4, 'NumberOfCatch' => 5, 'EmailBcc' => 6, 'EmailRedirect' => 7, 'Detail' => 8, 'CreatedAt' => 9, 'UpdatedAt' => 10, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'hash' => 1, 'disableHistory' => 2, 'disableSending' => 3, 'forceSameCustomerDisable' => 4, 'numberOfCatch' => 5, 'emailBcc' => 6, 'emailRedirect' => 7, 'detail' => 8, 'createdAt' => 9, 'updatedAt' => 10, ),
        self::TYPE_COLNAME       => array(MailManagerTraceTableMap::ID => 0, MailManagerTraceTableMap::HASH => 1, MailManagerTraceTableMap::DISABLE_HISTORY => 2, MailManagerTraceTableMap::DISABLE_SENDING => 3, MailManagerTraceTableMap::FORCE_SAME_CUSTOMER_DISABLE => 4, MailManagerTraceTableMap::NUMBER_OF_CATCH => 5, MailManagerTraceTableMap::EMAIL_BCC => 6, MailManagerTraceTableMap::EMAIL_REDIRECT => 7, MailManagerTraceTableMap::DETAIL => 8, MailManagerTraceTableMap::CREATED_AT => 9, MailManagerTraceTableMap::UPDATED_AT => 10, ),
        self::TYPE_RAW_COLNAME   => array('ID' => 0, 'HASH' => 1, 'DISABLE_HISTORY' => 2, 'DISABLE_SENDING' => 3, 'FORCE_SAME_CUSTOMER_DISABLE' => 4, 'NUMBER_OF_CATCH' => 5, 'EMAIL_BCC' => 6, 'EMAIL_REDIRECT' => 7, 'DETAIL' => 8, 'CREATED_AT' => 9, 'UPDATED_AT' => 10, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'hash' => 1, 'disable_history' => 2, 'disable_sending' => 3, 'force_same_customer_disable' => 4, 'number_of_catch' => 5, 'email_bcc' => 6, 'email_redirect' => 7, 'detail' => 8, 'created_at' => 9, 'updated_at' => 10, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, )
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
        $this->setName('mail_manager_trace');
        $this->setPhpName('MailManagerTrace');
        $this->setClassName('\\TheliaMailManager\\Model\\MailManagerTrace');
        $this->setPackage('TheliaMailManager.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('HASH', 'Hash', 'CHAR', true, 32, null);
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
        $this->addRelation('MailManagerTraceComment', '\\TheliaMailManager\\Model\\MailManagerTraceComment', RelationMap::ONE_TO_MANY, array('id' => 'trace_id', ), 'CASCADE', 'RESTRICT', 'MailManagerTraceComments');
        $this->addRelation('MailManagerHistory', '\\TheliaMailManager\\Model\\MailManagerHistory', RelationMap::ONE_TO_MANY, array('id' => 'trace_id', ), 'CASCADE', 'RESTRICT', 'MailManagerHistories');
        $this->addRelation('MailManagerHistoryMail', '\\TheliaMailManager\\Model\\MailManagerHistoryMail', RelationMap::ONE_TO_MANY, array('id' => 'mail_id', ), 'CASCADE', 'RESTRICT', 'MailManagerHistoryMails');
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
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', ),
        );
    } // getBehaviors()
    /**
     * Method to invalidate the instance pool of all tables related to mail_manager_trace     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in ".$this->getClassNameFromBuilder($joinedTableTableMapBuilder)." instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
                MailManagerTraceCommentTableMap::clearInstancePool();
                MailManagerHistoryTableMap::clearInstancePool();
                MailManagerHistoryMailTableMap::clearInstancePool();
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
        return $withPrefix ? MailManagerTraceTableMap::CLASS_DEFAULT : MailManagerTraceTableMap::OM_CLASS;
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
     * @return array (MailManagerTrace object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = MailManagerTraceTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = MailManagerTraceTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + MailManagerTraceTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = MailManagerTraceTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            MailManagerTraceTableMap::addInstanceToPool($obj, $key);
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
            $key = MailManagerTraceTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = MailManagerTraceTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                MailManagerTraceTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(MailManagerTraceTableMap::ID);
            $criteria->addSelectColumn(MailManagerTraceTableMap::HASH);
            $criteria->addSelectColumn(MailManagerTraceTableMap::DISABLE_HISTORY);
            $criteria->addSelectColumn(MailManagerTraceTableMap::DISABLE_SENDING);
            $criteria->addSelectColumn(MailManagerTraceTableMap::FORCE_SAME_CUSTOMER_DISABLE);
            $criteria->addSelectColumn(MailManagerTraceTableMap::NUMBER_OF_CATCH);
            $criteria->addSelectColumn(MailManagerTraceTableMap::EMAIL_BCC);
            $criteria->addSelectColumn(MailManagerTraceTableMap::EMAIL_REDIRECT);
            $criteria->addSelectColumn(MailManagerTraceTableMap::DETAIL);
            $criteria->addSelectColumn(MailManagerTraceTableMap::CREATED_AT);
            $criteria->addSelectColumn(MailManagerTraceTableMap::UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.HASH');
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
        return Propel::getServiceContainer()->getDatabaseMap(MailManagerTraceTableMap::DATABASE_NAME)->getTable(MailManagerTraceTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(MailManagerTraceTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(MailManagerTraceTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new MailManagerTraceTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a MailManagerTrace or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or MailManagerTrace object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(MailManagerTraceTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \TheliaMailManager\Model\MailManagerTrace) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(MailManagerTraceTableMap::DATABASE_NAME);
            $criteria->add(MailManagerTraceTableMap::ID, (array) $values, Criteria::IN);
        }

        $query = MailManagerTraceQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { MailManagerTraceTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { MailManagerTraceTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the mail_manager_trace table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return MailManagerTraceQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a MailManagerTrace or Criteria object.
     *
     * @param mixed               $criteria Criteria or MailManagerTrace object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MailManagerTraceTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from MailManagerTrace object
        }

        if ($criteria->containsKey(MailManagerTraceTableMap::ID) && $criteria->keyContainsValue(MailManagerTraceTableMap::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.MailManagerTraceTableMap::ID.')');
        }


        // Set the correct dbName
        $query = MailManagerTraceQuery::create()->mergeWith($criteria);

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

} // MailManagerTraceTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
MailManagerTraceTableMap::buildTableMap();
