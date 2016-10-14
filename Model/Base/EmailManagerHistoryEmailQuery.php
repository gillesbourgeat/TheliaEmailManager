<?php

namespace TheliaEmailManager\Model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use TheliaEmailManager\Model\EmailManagerHistoryEmail as ChildEmailManagerHistoryEmail;
use TheliaEmailManager\Model\EmailManagerHistoryEmailQuery as ChildEmailManagerHistoryEmailQuery;
use TheliaEmailManager\Model\Map\EmailManagerHistoryEmailTableMap;

/**
 * Base class that represents a query for the 'email_manager_history_email' table.
 *
 *
 *
 * @method     ChildEmailManagerHistoryEmailQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildEmailManagerHistoryEmailQuery orderByHistoryId($order = Criteria::ASC) Order by the history_id column
 * @method     ChildEmailManagerHistoryEmailQuery orderByEmailId($order = Criteria::ASC) Order by the email_id column
 * @method     ChildEmailManagerHistoryEmailQuery orderByType($order = Criteria::ASC) Order by the type column
 *
 * @method     ChildEmailManagerHistoryEmailQuery groupById() Group by the id column
 * @method     ChildEmailManagerHistoryEmailQuery groupByHistoryId() Group by the history_id column
 * @method     ChildEmailManagerHistoryEmailQuery groupByEmailId() Group by the email_id column
 * @method     ChildEmailManagerHistoryEmailQuery groupByType() Group by the type column
 *
 * @method     ChildEmailManagerHistoryEmailQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildEmailManagerHistoryEmailQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildEmailManagerHistoryEmailQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildEmailManagerHistoryEmailQuery leftJoinEmailManagerHistory($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmailManagerHistory relation
 * @method     ChildEmailManagerHistoryEmailQuery rightJoinEmailManagerHistory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmailManagerHistory relation
 * @method     ChildEmailManagerHistoryEmailQuery innerJoinEmailManagerHistory($relationAlias = null) Adds a INNER JOIN clause to the query using the EmailManagerHistory relation
 *
 * @method     ChildEmailManagerHistoryEmailQuery leftJoinEmailManagerEmail($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmailManagerEmail relation
 * @method     ChildEmailManagerHistoryEmailQuery rightJoinEmailManagerEmail($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmailManagerEmail relation
 * @method     ChildEmailManagerHistoryEmailQuery innerJoinEmailManagerEmail($relationAlias = null) Adds a INNER JOIN clause to the query using the EmailManagerEmail relation
 *
 * @method     ChildEmailManagerHistoryEmail findOne(ConnectionInterface $con = null) Return the first ChildEmailManagerHistoryEmail matching the query
 * @method     ChildEmailManagerHistoryEmail findOneOrCreate(ConnectionInterface $con = null) Return the first ChildEmailManagerHistoryEmail matching the query, or a new ChildEmailManagerHistoryEmail object populated from the query conditions when no match is found
 *
 * @method     ChildEmailManagerHistoryEmail findOneById(int $id) Return the first ChildEmailManagerHistoryEmail filtered by the id column
 * @method     ChildEmailManagerHistoryEmail findOneByHistoryId(int $history_id) Return the first ChildEmailManagerHistoryEmail filtered by the history_id column
 * @method     ChildEmailManagerHistoryEmail findOneByEmailId(int $email_id) Return the first ChildEmailManagerHistoryEmail filtered by the email_id column
 * @method     ChildEmailManagerHistoryEmail findOneByType(int $type) Return the first ChildEmailManagerHistoryEmail filtered by the type column
 *
 * @method     array findById(int $id) Return ChildEmailManagerHistoryEmail objects filtered by the id column
 * @method     array findByHistoryId(int $history_id) Return ChildEmailManagerHistoryEmail objects filtered by the history_id column
 * @method     array findByEmailId(int $email_id) Return ChildEmailManagerHistoryEmail objects filtered by the email_id column
 * @method     array findByType(int $type) Return ChildEmailManagerHistoryEmail objects filtered by the type column
 *
 */
abstract class EmailManagerHistoryEmailQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \TheliaEmailManager\Model\Base\EmailManagerHistoryEmailQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\TheliaEmailManager\\Model\\EmailManagerHistoryEmail', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildEmailManagerHistoryEmailQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildEmailManagerHistoryEmailQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \TheliaEmailManager\Model\EmailManagerHistoryEmailQuery) {
            return $criteria;
        }
        $query = new \TheliaEmailManager\Model\EmailManagerHistoryEmailQuery();
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
     * @return ChildEmailManagerHistoryEmail|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = EmailManagerHistoryEmailTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(EmailManagerHistoryEmailTableMap::DATABASE_NAME);
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
     * @return   ChildEmailManagerHistoryEmail A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, HISTORY_ID, EMAIL_ID, TYPE FROM email_manager_history_email WHERE ID = :p0';
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
            $obj = new ChildEmailManagerHistoryEmail();
            $obj->hydrate($row);
            EmailManagerHistoryEmailTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildEmailManagerHistoryEmail|array|mixed the result, formatted by the current formatter
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
     * @return ChildEmailManagerHistoryEmailQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EmailManagerHistoryEmailTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildEmailManagerHistoryEmailQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EmailManagerHistoryEmailTableMap::ID, $keys, Criteria::IN);
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
     * @return ChildEmailManagerHistoryEmailQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(EmailManagerHistoryEmailTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(EmailManagerHistoryEmailTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailManagerHistoryEmailTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the history_id column
     *
     * Example usage:
     * <code>
     * $query->filterByHistoryId(1234); // WHERE history_id = 1234
     * $query->filterByHistoryId(array(12, 34)); // WHERE history_id IN (12, 34)
     * $query->filterByHistoryId(array('min' => 12)); // WHERE history_id > 12
     * </code>
     *
     * @see       filterByEmailManagerHistory()
     *
     * @param     mixed $historyId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerHistoryEmailQuery The current query, for fluid interface
     */
    public function filterByHistoryId($historyId = null, $comparison = null)
    {
        if (is_array($historyId)) {
            $useMinMax = false;
            if (isset($historyId['min'])) {
                $this->addUsingAlias(EmailManagerHistoryEmailTableMap::HISTORY_ID, $historyId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($historyId['max'])) {
                $this->addUsingAlias(EmailManagerHistoryEmailTableMap::HISTORY_ID, $historyId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailManagerHistoryEmailTableMap::HISTORY_ID, $historyId, $comparison);
    }

    /**
     * Filter the query on the email_id column
     *
     * Example usage:
     * <code>
     * $query->filterByEmailId(1234); // WHERE email_id = 1234
     * $query->filterByEmailId(array(12, 34)); // WHERE email_id IN (12, 34)
     * $query->filterByEmailId(array('min' => 12)); // WHERE email_id > 12
     * </code>
     *
     * @see       filterByEmailManagerEmail()
     *
     * @param     mixed $emailId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerHistoryEmailQuery The current query, for fluid interface
     */
    public function filterByEmailId($emailId = null, $comparison = null)
    {
        if (is_array($emailId)) {
            $useMinMax = false;
            if (isset($emailId['min'])) {
                $this->addUsingAlias(EmailManagerHistoryEmailTableMap::EMAIL_ID, $emailId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($emailId['max'])) {
                $this->addUsingAlias(EmailManagerHistoryEmailTableMap::EMAIL_ID, $emailId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailManagerHistoryEmailTableMap::EMAIL_ID, $emailId, $comparison);
    }

    /**
     * Filter the query on the type column
     *
     * @param     mixed $type The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerHistoryEmailQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        $valueSet = EmailManagerHistoryEmailTableMap::getValueSet(EmailManagerHistoryEmailTableMap::TYPE);
        if (is_scalar($type)) {
            if (!in_array($type, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $type));
            }
            $type = array_search($type, $valueSet);
        } elseif (is_array($type)) {
            $convertedValues = array();
            foreach ($type as $value) {
                if (!in_array($value, $valueSet)) {
                    throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $value));
                }
                $convertedValues []= array_search($value, $valueSet);
            }
            $type = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailManagerHistoryEmailTableMap::TYPE, $type, $comparison);
    }

    /**
     * Filter the query by a related \TheliaEmailManager\Model\EmailManagerHistory object
     *
     * @param \TheliaEmailManager\Model\EmailManagerHistory|ObjectCollection $emailManagerHistory The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerHistoryEmailQuery The current query, for fluid interface
     */
    public function filterByEmailManagerHistory($emailManagerHistory, $comparison = null)
    {
        if ($emailManagerHistory instanceof \TheliaEmailManager\Model\EmailManagerHistory) {
            return $this
                ->addUsingAlias(EmailManagerHistoryEmailTableMap::HISTORY_ID, $emailManagerHistory->getId(), $comparison);
        } elseif ($emailManagerHistory instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EmailManagerHistoryEmailTableMap::HISTORY_ID, $emailManagerHistory->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByEmailManagerHistory() only accepts arguments of type \TheliaEmailManager\Model\EmailManagerHistory or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EmailManagerHistory relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildEmailManagerHistoryEmailQuery The current query, for fluid interface
     */
    public function joinEmailManagerHistory($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EmailManagerHistory');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'EmailManagerHistory');
        }

        return $this;
    }

    /**
     * Use the EmailManagerHistory relation EmailManagerHistory object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \TheliaEmailManager\Model\EmailManagerHistoryQuery A secondary query class using the current class as primary query
     */
    public function useEmailManagerHistoryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEmailManagerHistory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EmailManagerHistory', '\TheliaEmailManager\Model\EmailManagerHistoryQuery');
    }

    /**
     * Filter the query by a related \TheliaEmailManager\Model\EmailManagerEmail object
     *
     * @param \TheliaEmailManager\Model\EmailManagerEmail|ObjectCollection $emailManagerEmail The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerHistoryEmailQuery The current query, for fluid interface
     */
    public function filterByEmailManagerEmail($emailManagerEmail, $comparison = null)
    {
        if ($emailManagerEmail instanceof \TheliaEmailManager\Model\EmailManagerEmail) {
            return $this
                ->addUsingAlias(EmailManagerHistoryEmailTableMap::EMAIL_ID, $emailManagerEmail->getId(), $comparison);
        } elseif ($emailManagerEmail instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EmailManagerHistoryEmailTableMap::EMAIL_ID, $emailManagerEmail->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByEmailManagerEmail() only accepts arguments of type \TheliaEmailManager\Model\EmailManagerEmail or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EmailManagerEmail relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildEmailManagerHistoryEmailQuery The current query, for fluid interface
     */
    public function joinEmailManagerEmail($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EmailManagerEmail');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'EmailManagerEmail');
        }

        return $this;
    }

    /**
     * Use the EmailManagerEmail relation EmailManagerEmail object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \TheliaEmailManager\Model\EmailManagerEmailQuery A secondary query class using the current class as primary query
     */
    public function useEmailManagerEmailQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEmailManagerEmail($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EmailManagerEmail', '\TheliaEmailManager\Model\EmailManagerEmailQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildEmailManagerHistoryEmail $emailManagerHistoryEmail Object to remove from the list of results
     *
     * @return ChildEmailManagerHistoryEmailQuery The current query, for fluid interface
     */
    public function prune($emailManagerHistoryEmail = null)
    {
        if ($emailManagerHistoryEmail) {
            $this->addUsingAlias(EmailManagerHistoryEmailTableMap::ID, $emailManagerHistoryEmail->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the email_manager_history_email table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EmailManagerHistoryEmailTableMap::DATABASE_NAME);
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
            EmailManagerHistoryEmailTableMap::clearInstancePool();
            EmailManagerHistoryEmailTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildEmailManagerHistoryEmail or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildEmailManagerHistoryEmail object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(EmailManagerHistoryEmailTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(EmailManagerHistoryEmailTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        EmailManagerHistoryEmailTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            EmailManagerHistoryEmailTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // EmailManagerHistoryEmailQuery
