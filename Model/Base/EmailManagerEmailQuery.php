<?php

namespace TheliaEmailManager\Model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use TheliaEmailManager\Model\EmailManagerEmail as ChildEmailManagerEmail;
use TheliaEmailManager\Model\EmailManagerEmailQuery as ChildEmailManagerEmailQuery;
use TheliaEmailManager\Model\Map\EmailManagerEmailTableMap;

/**
 * Base class that represents a query for the 'email_manager_email' table.
 *
 *
 *
 * @method     ChildEmailManagerEmailQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildEmailManagerEmailQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     ChildEmailManagerEmailQuery orderByDisableSend($order = Criteria::ASC) Order by the disable_send column
 * @method     ChildEmailManagerEmailQuery orderByDisableSendDate($order = Criteria::ASC) Order by the disable_send_date column
 * @method     ChildEmailManagerEmailQuery orderByDisableHash($order = Criteria::ASC) Order by the disable_hash column
 * @method     ChildEmailManagerEmailQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildEmailManagerEmailQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildEmailManagerEmailQuery groupById() Group by the id column
 * @method     ChildEmailManagerEmailQuery groupByEmail() Group by the email column
 * @method     ChildEmailManagerEmailQuery groupByDisableSend() Group by the disable_send column
 * @method     ChildEmailManagerEmailQuery groupByDisableSendDate() Group by the disable_send_date column
 * @method     ChildEmailManagerEmailQuery groupByDisableHash() Group by the disable_hash column
 * @method     ChildEmailManagerEmailQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildEmailManagerEmailQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildEmailManagerEmailQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildEmailManagerEmailQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildEmailManagerEmailQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildEmailManagerEmail findOne(ConnectionInterface $con = null) Return the first ChildEmailManagerEmail matching the query
 * @method     ChildEmailManagerEmail findOneOrCreate(ConnectionInterface $con = null) Return the first ChildEmailManagerEmail matching the query, or a new ChildEmailManagerEmail object populated from the query conditions when no match is found
 *
 * @method     ChildEmailManagerEmail findOneById(int $id) Return the first ChildEmailManagerEmail filtered by the id column
 * @method     ChildEmailManagerEmail findOneByEmail(string $email) Return the first ChildEmailManagerEmail filtered by the email column
 * @method     ChildEmailManagerEmail findOneByDisableSend(boolean $disable_send) Return the first ChildEmailManagerEmail filtered by the disable_send column
 * @method     ChildEmailManagerEmail findOneByDisableSendDate(string $disable_send_date) Return the first ChildEmailManagerEmail filtered by the disable_send_date column
 * @method     ChildEmailManagerEmail findOneByDisableHash(string $disable_hash) Return the first ChildEmailManagerEmail filtered by the disable_hash column
 * @method     ChildEmailManagerEmail findOneByCreatedAt(string $created_at) Return the first ChildEmailManagerEmail filtered by the created_at column
 * @method     ChildEmailManagerEmail findOneByUpdatedAt(string $updated_at) Return the first ChildEmailManagerEmail filtered by the updated_at column
 *
 * @method     array findById(int $id) Return ChildEmailManagerEmail objects filtered by the id column
 * @method     array findByEmail(string $email) Return ChildEmailManagerEmail objects filtered by the email column
 * @method     array findByDisableSend(boolean $disable_send) Return ChildEmailManagerEmail objects filtered by the disable_send column
 * @method     array findByDisableSendDate(string $disable_send_date) Return ChildEmailManagerEmail objects filtered by the disable_send_date column
 * @method     array findByDisableHash(string $disable_hash) Return ChildEmailManagerEmail objects filtered by the disable_hash column
 * @method     array findByCreatedAt(string $created_at) Return ChildEmailManagerEmail objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return ChildEmailManagerEmail objects filtered by the updated_at column
 *
 */
abstract class EmailManagerEmailQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \TheliaEmailManager\Model\Base\EmailManagerEmailQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\TheliaEmailManager\\Model\\EmailManagerEmail', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildEmailManagerEmailQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildEmailManagerEmailQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \TheliaEmailManager\Model\EmailManagerEmailQuery) {
            return $criteria;
        }
        $query = new \TheliaEmailManager\Model\EmailManagerEmailQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildEmailManagerEmail|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = EmailManagerEmailTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(EmailManagerEmailTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildEmailManagerEmail A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, EMAIL, DISABLE_SEND, DISABLE_SEND_DATE, DISABLE_HASH, CREATED_AT, UPDATED_AT FROM email_manager_email WHERE ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildEmailManagerEmail();
            $obj->hydrate($row);
            EmailManagerEmailTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildEmailManagerEmail|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildEmailManagerEmailQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EmailManagerEmailTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildEmailManagerEmailQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EmailManagerEmailTableMap::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerEmailQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(EmailManagerEmailTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(EmailManagerEmailTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailManagerEmailTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%'); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerEmailQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $email)) {
                $email = str_replace('*', '%', $email);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EmailManagerEmailTableMap::EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the disable_send column
     *
     * Example usage:
     * <code>
     * $query->filterByDisableSend(true); // WHERE disable_send = true
     * $query->filterByDisableSend('yes'); // WHERE disable_send = true
     * </code>
     *
     * @param     boolean|string $disableSend The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerEmailQuery The current query, for fluid interface
     */
    public function filterByDisableSend($disableSend = null, $comparison = null)
    {
        if (is_string($disableSend)) {
            $disable_send = in_array(strtolower($disableSend), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(EmailManagerEmailTableMap::DISABLE_SEND, $disableSend, $comparison);
    }

    /**
     * Filter the query on the disable_send_date column
     *
     * Example usage:
     * <code>
     * $query->filterByDisableSendDate('2011-03-14'); // WHERE disable_send_date = '2011-03-14'
     * $query->filterByDisableSendDate('now'); // WHERE disable_send_date = '2011-03-14'
     * $query->filterByDisableSendDate(array('max' => 'yesterday')); // WHERE disable_send_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $disableSendDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerEmailQuery The current query, for fluid interface
     */
    public function filterByDisableSendDate($disableSendDate = null, $comparison = null)
    {
        if (is_array($disableSendDate)) {
            $useMinMax = false;
            if (isset($disableSendDate['min'])) {
                $this->addUsingAlias(EmailManagerEmailTableMap::DISABLE_SEND_DATE, $disableSendDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($disableSendDate['max'])) {
                $this->addUsingAlias(EmailManagerEmailTableMap::DISABLE_SEND_DATE, $disableSendDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailManagerEmailTableMap::DISABLE_SEND_DATE, $disableSendDate, $comparison);
    }

    /**
     * Filter the query on the disable_hash column
     *
     * Example usage:
     * <code>
     * $query->filterByDisableHash('fooValue');   // WHERE disable_hash = 'fooValue'
     * $query->filterByDisableHash('%fooValue%'); // WHERE disable_hash LIKE '%fooValue%'
     * </code>
     *
     * @param     string $disableHash The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerEmailQuery The current query, for fluid interface
     */
    public function filterByDisableHash($disableHash = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($disableHash)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $disableHash)) {
                $disableHash = str_replace('*', '%', $disableHash);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EmailManagerEmailTableMap::DISABLE_HASH, $disableHash, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerEmailQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(EmailManagerEmailTableMap::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(EmailManagerEmailTableMap::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailManagerEmailTableMap::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerEmailQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(EmailManagerEmailTableMap::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(EmailManagerEmailTableMap::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailManagerEmailTableMap::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildEmailManagerEmail $emailManagerEmail Object to remove from the list of results
     *
     * @return ChildEmailManagerEmailQuery The current query, for fluid interface
     */
    public function prune($emailManagerEmail = null)
    {
        if ($emailManagerEmail) {
            $this->addUsingAlias(EmailManagerEmailTableMap::ID, $emailManagerEmail->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the email_manager_email table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EmailManagerEmailTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            EmailManagerEmailTableMap::clearInstancePool();
            EmailManagerEmailTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildEmailManagerEmail or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildEmailManagerEmail object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EmailManagerEmailTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(EmailManagerEmailTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        EmailManagerEmailTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            EmailManagerEmailTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     ChildEmailManagerEmailQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(EmailManagerEmailTableMap::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     ChildEmailManagerEmailQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(EmailManagerEmailTableMap::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     ChildEmailManagerEmailQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(EmailManagerEmailTableMap::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     ChildEmailManagerEmailQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(EmailManagerEmailTableMap::UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     ChildEmailManagerEmailQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(EmailManagerEmailTableMap::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     ChildEmailManagerEmailQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(EmailManagerEmailTableMap::CREATED_AT);
    }

} // EmailManagerEmailQuery
