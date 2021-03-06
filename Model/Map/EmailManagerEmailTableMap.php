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
use TheliaEmailManager\Model\EmailManagerEmail;
use TheliaEmailManager\Model\EmailManagerEmailQuery;


/**
 * This class defines the structure of the 'email_manager_email' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class EmailManagerEmailTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'TheliaEmailManager.Model.Map.EmailManagerEmailTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'thelia';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'email_manager_email';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\TheliaEmailManager\\Model\\EmailManagerEmail';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'TheliaEmailManager.Model.EmailManagerEmail';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 8;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 8;

    /**
     * the column name for the ID field
     */
    const ID = 'email_manager_email.ID';

    /**
     * the column name for the EMAIL field
     */
    const EMAIL = 'email_manager_email.EMAIL';

    /**
     * the column name for the NAME field
     */
    const NAME = 'email_manager_email.NAME';

    /**
     * the column name for the DISABLE_SEND field
     */
    const DISABLE_SEND = 'email_manager_email.DISABLE_SEND';

    /**
     * the column name for the DISABLE_SEND_DATE field
     */
    const DISABLE_SEND_DATE = 'email_manager_email.DISABLE_SEND_DATE';

    /**
     * the column name for the DISABLE_HASH field
     */
    const DISABLE_HASH = 'email_manager_email.DISABLE_HASH';

    /**
     * the column name for the CREATED_AT field
     */
    const CREATED_AT = 'email_manager_email.CREATED_AT';

    /**
     * the column name for the UPDATED_AT field
     */
    const UPDATED_AT = 'email_manager_email.UPDATED_AT';

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
        self::TYPE_PHPNAME       => array('Id', 'Email', 'Name', 'DisableSend', 'DisableSendDate', 'DisableHash', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'email', 'name', 'disableSend', 'disableSendDate', 'disableHash', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(EmailManagerEmailTableMap::ID, EmailManagerEmailTableMap::EMAIL, EmailManagerEmailTableMap::NAME, EmailManagerEmailTableMap::DISABLE_SEND, EmailManagerEmailTableMap::DISABLE_SEND_DATE, EmailManagerEmailTableMap::DISABLE_HASH, EmailManagerEmailTableMap::CREATED_AT, EmailManagerEmailTableMap::UPDATED_AT, ),
        self::TYPE_RAW_COLNAME   => array('ID', 'EMAIL', 'NAME', 'DISABLE_SEND', 'DISABLE_SEND_DATE', 'DISABLE_HASH', 'CREATED_AT', 'UPDATED_AT', ),
        self::TYPE_FIELDNAME     => array('id', 'email', 'name', 'disable_send', 'disable_send_date', 'disable_hash', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Email' => 1, 'Name' => 2, 'DisableSend' => 3, 'DisableSendDate' => 4, 'DisableHash' => 5, 'CreatedAt' => 6, 'UpdatedAt' => 7, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'email' => 1, 'name' => 2, 'disableSend' => 3, 'disableSendDate' => 4, 'disableHash' => 5, 'createdAt' => 6, 'updatedAt' => 7, ),
        self::TYPE_COLNAME       => array(EmailManagerEmailTableMap::ID => 0, EmailManagerEmailTableMap::EMAIL => 1, EmailManagerEmailTableMap::NAME => 2, EmailManagerEmailTableMap::DISABLE_SEND => 3, EmailManagerEmailTableMap::DISABLE_SEND_DATE => 4, EmailManagerEmailTableMap::DISABLE_HASH => 5, EmailManagerEmailTableMap::CREATED_AT => 6, EmailManagerEmailTableMap::UPDATED_AT => 7, ),
        self::TYPE_RAW_COLNAME   => array('ID' => 0, 'EMAIL' => 1, 'NAME' => 2, 'DISABLE_SEND' => 3, 'DISABLE_SEND_DATE' => 4, 'DISABLE_HASH' => 5, 'CREATED_AT' => 6, 'UPDATED_AT' => 7, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'email' => 1, 'name' => 2, 'disable_send' => 3, 'disable_send_date' => 4, 'disable_hash' => 5, 'created_at' => 6, 'updated_at' => 7, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
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
        $this->setName('email_manager_email');
        $this->setPhpName('EmailManagerEmail');
        $this->setClassName('\\TheliaEmailManager\\Model\\EmailManagerEmail');
        $this->setPackage('TheliaEmailManager.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('EMAIL', 'Email', 'VARCHAR', true, 255, null);
        $this->addColumn('NAME', 'Name', 'VARCHAR', true, 255, null);
        $this->addColumn('DISABLE_SEND', 'DisableSend', 'BOOLEAN', false, 1, false);
        $this->addColumn('DISABLE_SEND_DATE', 'DisableSendDate', 'TIMESTAMP', false, null, null);
        $this->addColumn('DISABLE_HASH', 'DisableHash', 'CHAR', true, 64, null);
        $this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('EmailManagerHistoryEmail', '\\TheliaEmailManager\\Model\\EmailManagerHistoryEmail', RelationMap::ONE_TO_MANY, array('id' => 'email_id', ), 'CASCADE', 'RESTRICT', 'EmailManagerHistoryEmails');
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
     * Method to invalidate the instance pool of all tables related to email_manager_email     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in ".$this->getClassNameFromBuilder($joinedTableTableMapBuilder)." instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
                EmailManagerHistoryEmailTableMap::clearInstancePool();
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
        return $withPrefix ? EmailManagerEmailTableMap::CLASS_DEFAULT : EmailManagerEmailTableMap::OM_CLASS;
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
     * @return array (EmailManagerEmail object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = EmailManagerEmailTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = EmailManagerEmailTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + EmailManagerEmailTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = EmailManagerEmailTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            EmailManagerEmailTableMap::addInstanceToPool($obj, $key);
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
            $key = EmailManagerEmailTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = EmailManagerEmailTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                EmailManagerEmailTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(EmailManagerEmailTableMap::ID);
            $criteria->addSelectColumn(EmailManagerEmailTableMap::EMAIL);
            $criteria->addSelectColumn(EmailManagerEmailTableMap::NAME);
            $criteria->addSelectColumn(EmailManagerEmailTableMap::DISABLE_SEND);
            $criteria->addSelectColumn(EmailManagerEmailTableMap::DISABLE_SEND_DATE);
            $criteria->addSelectColumn(EmailManagerEmailTableMap::DISABLE_HASH);
            $criteria->addSelectColumn(EmailManagerEmailTableMap::CREATED_AT);
            $criteria->addSelectColumn(EmailManagerEmailTableMap::UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.EMAIL');
            $criteria->addSelectColumn($alias . '.NAME');
            $criteria->addSelectColumn($alias . '.DISABLE_SEND');
            $criteria->addSelectColumn($alias . '.DISABLE_SEND_DATE');
            $criteria->addSelectColumn($alias . '.DISABLE_HASH');
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
        return Propel::getServiceContainer()->getDatabaseMap(EmailManagerEmailTableMap::DATABASE_NAME)->getTable(EmailManagerEmailTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(EmailManagerEmailTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(EmailManagerEmailTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new EmailManagerEmailTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a EmailManagerEmail or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or EmailManagerEmail object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(EmailManagerEmailTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \TheliaEmailManager\Model\EmailManagerEmail) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(EmailManagerEmailTableMap::DATABASE_NAME);
            $criteria->add(EmailManagerEmailTableMap::ID, (array) $values, Criteria::IN);
        }

        $query = EmailManagerEmailQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { EmailManagerEmailTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { EmailManagerEmailTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the email_manager_email table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return EmailManagerEmailQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a EmailManagerEmail or Criteria object.
     *
     * @param mixed               $criteria Criteria or EmailManagerEmail object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EmailManagerEmailTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from EmailManagerEmail object
        }

        if ($criteria->containsKey(EmailManagerEmailTableMap::ID) && $criteria->keyContainsValue(EmailManagerEmailTableMap::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.EmailManagerEmailTableMap::ID.')');
        }


        // Set the correct dbName
        $query = EmailManagerEmailQuery::create()->mergeWith($criteria);

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

} // EmailManagerEmailTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
EmailManagerEmailTableMap::buildTableMap();
