<?php

namespace TheliaMailManager\Model\Base;

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
use TheliaMailManager\Model\MailManagerHistoryMail as ChildMailManagerHistoryMail;
use TheliaMailManager\Model\MailManagerHistoryMailQuery as ChildMailManagerHistoryMailQuery;
use TheliaMailManager\Model\Map\MailManagerHistoryMailTableMap;

/**
 * Base class that represents a query for the 'mail_manager_history_mail' table.
 *
 *
 *
 * @method     ChildMailManagerHistoryMailQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildMailManagerHistoryMailQuery orderByHistoryId($order = Criteria::ASC) Order by the history_id column
 * @method     ChildMailManagerHistoryMailQuery orderByMailId($order = Criteria::ASC) Order by the mail_id column
 * @method     ChildMailManagerHistoryMailQuery orderByType($order = Criteria::ASC) Order by the type column
 *
 * @method     ChildMailManagerHistoryMailQuery groupById() Group by the id column
 * @method     ChildMailManagerHistoryMailQuery groupByHistoryId() Group by the history_id column
 * @method     ChildMailManagerHistoryMailQuery groupByMailId() Group by the mail_id column
 * @method     ChildMailManagerHistoryMailQuery groupByType() Group by the type column
 *
 * @method     ChildMailManagerHistoryMailQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMailManagerHistoryMailQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMailManagerHistoryMailQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMailManagerHistoryMailQuery leftJoinMailManagerHistory($relationAlias = null) Adds a LEFT JOIN clause to the query using the MailManagerHistory relation
 * @method     ChildMailManagerHistoryMailQuery rightJoinMailManagerHistory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MailManagerHistory relation
 * @method     ChildMailManagerHistoryMailQuery innerJoinMailManagerHistory($relationAlias = null) Adds a INNER JOIN clause to the query using the MailManagerHistory relation
 *
 * @method     ChildMailManagerHistoryMailQuery leftJoinMailManagerTrace($relationAlias = null) Adds a LEFT JOIN clause to the query using the MailManagerTrace relation
 * @method     ChildMailManagerHistoryMailQuery rightJoinMailManagerTrace($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MailManagerTrace relation
 * @method     ChildMailManagerHistoryMailQuery innerJoinMailManagerTrace($relationAlias = null) Adds a INNER JOIN clause to the query using the MailManagerTrace relation
 *
 * @method     ChildMailManagerHistoryMail findOne(ConnectionInterface $con = null) Return the first ChildMailManagerHistoryMail matching the query
 * @method     ChildMailManagerHistoryMail findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMailManagerHistoryMail matching the query, or a new ChildMailManagerHistoryMail object populated from the query conditions when no match is found
 *
 * @method     ChildMailManagerHistoryMail findOneById(int $id) Return the first ChildMailManagerHistoryMail filtered by the id column
 * @method     ChildMailManagerHistoryMail findOneByHistoryId(int $history_id) Return the first ChildMailManagerHistoryMail filtered by the history_id column
 * @method     ChildMailManagerHistoryMail findOneByMailId(int $mail_id) Return the first ChildMailManagerHistoryMail filtered by the mail_id column
 * @method     ChildMailManagerHistoryMail findOneByType(int $type) Return the first ChildMailManagerHistoryMail filtered by the type column
 *
 * @method     array findById(int $id) Return ChildMailManagerHistoryMail objects filtered by the id column
 * @method     array findByHistoryId(int $history_id) Return ChildMailManagerHistoryMail objects filtered by the history_id column
 * @method     array findByMailId(int $mail_id) Return ChildMailManagerHistoryMail objects filtered by the mail_id column
 * @method     array findByType(int $type) Return ChildMailManagerHistoryMail objects filtered by the type column
 *
 */
abstract class MailManagerHistoryMailQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \TheliaMailManager\Model\Base\MailManagerHistoryMailQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\TheliaMailManager\\Model\\MailManagerHistoryMail', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMailManagerHistoryMailQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMailManagerHistoryMailQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \TheliaMailManager\Model\MailManagerHistoryMailQuery) {
            return $criteria;
        }
        $query = new \TheliaMailManager\Model\MailManagerHistoryMailQuery();
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
     * @return ChildMailManagerHistoryMail|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = MailManagerHistoryMailTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MailManagerHistoryMailTableMap::DATABASE_NAME);
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
     * @return   ChildMailManagerHistoryMail A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, HISTORY_ID, MAIL_ID, TYPE FROM mail_manager_history_mail WHERE ID = :p0';
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
            $obj = new ChildMailManagerHistoryMail();
            $obj->hydrate($row);
            MailManagerHistoryMailTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildMailManagerHistoryMail|array|mixed the result, formatted by the current formatter
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
     * @return ChildMailManagerHistoryMailQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MailManagerHistoryMailTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildMailManagerHistoryMailQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MailManagerHistoryMailTableMap::ID, $keys, Criteria::IN);
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
     * @return ChildMailManagerHistoryMailQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MailManagerHistoryMailTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MailManagerHistoryMailTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailManagerHistoryMailTableMap::ID, $id, $comparison);
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
     * @see       filterByMailManagerHistory()
     *
     * @param     mixed $historyId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMailManagerHistoryMailQuery The current query, for fluid interface
     */
    public function filterByHistoryId($historyId = null, $comparison = null)
    {
        if (is_array($historyId)) {
            $useMinMax = false;
            if (isset($historyId['min'])) {
                $this->addUsingAlias(MailManagerHistoryMailTableMap::HISTORY_ID, $historyId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($historyId['max'])) {
                $this->addUsingAlias(MailManagerHistoryMailTableMap::HISTORY_ID, $historyId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailManagerHistoryMailTableMap::HISTORY_ID, $historyId, $comparison);
    }

    /**
     * Filter the query on the mail_id column
     *
     * Example usage:
     * <code>
     * $query->filterByMailId(1234); // WHERE mail_id = 1234
     * $query->filterByMailId(array(12, 34)); // WHERE mail_id IN (12, 34)
     * $query->filterByMailId(array('min' => 12)); // WHERE mail_id > 12
     * </code>
     *
     * @see       filterByMailManagerTrace()
     *
     * @param     mixed $mailId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMailManagerHistoryMailQuery The current query, for fluid interface
     */
    public function filterByMailId($mailId = null, $comparison = null)
    {
        if (is_array($mailId)) {
            $useMinMax = false;
            if (isset($mailId['min'])) {
                $this->addUsingAlias(MailManagerHistoryMailTableMap::MAIL_ID, $mailId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($mailId['max'])) {
                $this->addUsingAlias(MailManagerHistoryMailTableMap::MAIL_ID, $mailId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailManagerHistoryMailTableMap::MAIL_ID, $mailId, $comparison);
    }

    /**
     * Filter the query on the type column
     *
     * @param     mixed $type The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMailManagerHistoryMailQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        $valueSet = MailManagerHistoryMailTableMap::getValueSet(MailManagerHistoryMailTableMap::TYPE);
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

        return $this->addUsingAlias(MailManagerHistoryMailTableMap::TYPE, $type, $comparison);
    }

    /**
     * Filter the query by a related \TheliaMailManager\Model\MailManagerHistory object
     *
     * @param \TheliaMailManager\Model\MailManagerHistory|ObjectCollection $mailManagerHistory The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMailManagerHistoryMailQuery The current query, for fluid interface
     */
    public function filterByMailManagerHistory($mailManagerHistory, $comparison = null)
    {
        if ($mailManagerHistory instanceof \TheliaMailManager\Model\MailManagerHistory) {
            return $this
                ->addUsingAlias(MailManagerHistoryMailTableMap::HISTORY_ID, $mailManagerHistory->getId(), $comparison);
        } elseif ($mailManagerHistory instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MailManagerHistoryMailTableMap::HISTORY_ID, $mailManagerHistory->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByMailManagerHistory() only accepts arguments of type \TheliaMailManager\Model\MailManagerHistory or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MailManagerHistory relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildMailManagerHistoryMailQuery The current query, for fluid interface
     */
    public function joinMailManagerHistory($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MailManagerHistory');

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
            $this->addJoinObject($join, 'MailManagerHistory');
        }

        return $this;
    }

    /**
     * Use the MailManagerHistory relation MailManagerHistory object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \TheliaMailManager\Model\MailManagerHistoryQuery A secondary query class using the current class as primary query
     */
    public function useMailManagerHistoryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMailManagerHistory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MailManagerHistory', '\TheliaMailManager\Model\MailManagerHistoryQuery');
    }

    /**
     * Filter the query by a related \TheliaMailManager\Model\MailManagerTrace object
     *
     * @param \TheliaMailManager\Model\MailManagerTrace|ObjectCollection $mailManagerTrace The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMailManagerHistoryMailQuery The current query, for fluid interface
     */
    public function filterByMailManagerTrace($mailManagerTrace, $comparison = null)
    {
        if ($mailManagerTrace instanceof \TheliaMailManager\Model\MailManagerTrace) {
            return $this
                ->addUsingAlias(MailManagerHistoryMailTableMap::MAIL_ID, $mailManagerTrace->getId(), $comparison);
        } elseif ($mailManagerTrace instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MailManagerHistoryMailTableMap::MAIL_ID, $mailManagerTrace->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByMailManagerTrace() only accepts arguments of type \TheliaMailManager\Model\MailManagerTrace or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MailManagerTrace relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildMailManagerHistoryMailQuery The current query, for fluid interface
     */
    public function joinMailManagerTrace($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MailManagerTrace');

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
            $this->addJoinObject($join, 'MailManagerTrace');
        }

        return $this;
    }

    /**
     * Use the MailManagerTrace relation MailManagerTrace object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \TheliaMailManager\Model\MailManagerTraceQuery A secondary query class using the current class as primary query
     */
    public function useMailManagerTraceQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMailManagerTrace($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MailManagerTrace', '\TheliaMailManager\Model\MailManagerTraceQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMailManagerHistoryMail $mailManagerHistoryMail Object to remove from the list of results
     *
     * @return ChildMailManagerHistoryMailQuery The current query, for fluid interface
     */
    public function prune($mailManagerHistoryMail = null)
    {
        if ($mailManagerHistoryMail) {
            $this->addUsingAlias(MailManagerHistoryMailTableMap::ID, $mailManagerHistoryMail->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the mail_manager_history_mail table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MailManagerHistoryMailTableMap::DATABASE_NAME);
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
            MailManagerHistoryMailTableMap::clearInstancePool();
            MailManagerHistoryMailTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildMailManagerHistoryMail or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildMailManagerHistoryMail object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(MailManagerHistoryMailTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MailManagerHistoryMailTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        MailManagerHistoryMailTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MailManagerHistoryMailTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // MailManagerHistoryMailQuery
