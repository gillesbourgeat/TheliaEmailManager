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
use TheliaMailManager\Model\MailManagerHistory as ChildMailManagerHistory;
use TheliaMailManager\Model\MailManagerHistoryQuery as ChildMailManagerHistoryQuery;
use TheliaMailManager\Model\Map\MailManagerHistoryTableMap;

/**
 * Base class that represents a query for the 'mail_manager_history' table.
 *
 *
 *
 * @method     ChildMailManagerHistoryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildMailManagerHistoryQuery orderByTraceId($order = Criteria::ASC) Order by the trace_id column
 * @method     ChildMailManagerHistoryQuery orderBySubject($order = Criteria::ASC) Order by the subject column
 * @method     ChildMailManagerHistoryQuery orderByBody($order = Criteria::ASC) Order by the body column
 * @method     ChildMailManagerHistoryQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildMailManagerHistoryQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildMailManagerHistoryQuery groupById() Group by the id column
 * @method     ChildMailManagerHistoryQuery groupByTraceId() Group by the trace_id column
 * @method     ChildMailManagerHistoryQuery groupBySubject() Group by the subject column
 * @method     ChildMailManagerHistoryQuery groupByBody() Group by the body column
 * @method     ChildMailManagerHistoryQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildMailManagerHistoryQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildMailManagerHistoryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMailManagerHistoryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMailManagerHistoryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMailManagerHistoryQuery leftJoinMailManagerTrace($relationAlias = null) Adds a LEFT JOIN clause to the query using the MailManagerTrace relation
 * @method     ChildMailManagerHistoryQuery rightJoinMailManagerTrace($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MailManagerTrace relation
 * @method     ChildMailManagerHistoryQuery innerJoinMailManagerTrace($relationAlias = null) Adds a INNER JOIN clause to the query using the MailManagerTrace relation
 *
 * @method     ChildMailManagerHistoryQuery leftJoinMailManagerHistoryMail($relationAlias = null) Adds a LEFT JOIN clause to the query using the MailManagerHistoryMail relation
 * @method     ChildMailManagerHistoryQuery rightJoinMailManagerHistoryMail($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MailManagerHistoryMail relation
 * @method     ChildMailManagerHistoryQuery innerJoinMailManagerHistoryMail($relationAlias = null) Adds a INNER JOIN clause to the query using the MailManagerHistoryMail relation
 *
 * @method     ChildMailManagerHistory findOne(ConnectionInterface $con = null) Return the first ChildMailManagerHistory matching the query
 * @method     ChildMailManagerHistory findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMailManagerHistory matching the query, or a new ChildMailManagerHistory object populated from the query conditions when no match is found
 *
 * @method     ChildMailManagerHistory findOneById(int $id) Return the first ChildMailManagerHistory filtered by the id column
 * @method     ChildMailManagerHistory findOneByTraceId(int $trace_id) Return the first ChildMailManagerHistory filtered by the trace_id column
 * @method     ChildMailManagerHistory findOneBySubject(string $subject) Return the first ChildMailManagerHistory filtered by the subject column
 * @method     ChildMailManagerHistory findOneByBody(resource $body) Return the first ChildMailManagerHistory filtered by the body column
 * @method     ChildMailManagerHistory findOneByCreatedAt(string $created_at) Return the first ChildMailManagerHistory filtered by the created_at column
 * @method     ChildMailManagerHistory findOneByUpdatedAt(string $updated_at) Return the first ChildMailManagerHistory filtered by the updated_at column
 *
 * @method     array findById(int $id) Return ChildMailManagerHistory objects filtered by the id column
 * @method     array findByTraceId(int $trace_id) Return ChildMailManagerHistory objects filtered by the trace_id column
 * @method     array findBySubject(string $subject) Return ChildMailManagerHistory objects filtered by the subject column
 * @method     array findByBody(resource $body) Return ChildMailManagerHistory objects filtered by the body column
 * @method     array findByCreatedAt(string $created_at) Return ChildMailManagerHistory objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return ChildMailManagerHistory objects filtered by the updated_at column
 *
 */
abstract class MailManagerHistoryQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \TheliaMailManager\Model\Base\MailManagerHistoryQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\TheliaMailManager\\Model\\MailManagerHistory', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMailManagerHistoryQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMailManagerHistoryQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \TheliaMailManager\Model\MailManagerHistoryQuery) {
            return $criteria;
        }
        $query = new \TheliaMailManager\Model\MailManagerHistoryQuery();
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
     * @return ChildMailManagerHistory|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = MailManagerHistoryTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MailManagerHistoryTableMap::DATABASE_NAME);
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
     * @return   ChildMailManagerHistory A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, TRACE_ID, SUBJECT, BODY, CREATED_AT, UPDATED_AT FROM mail_manager_history WHERE ID = :p0';
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
            $obj = new ChildMailManagerHistory();
            $obj->hydrate($row);
            MailManagerHistoryTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildMailManagerHistory|array|mixed the result, formatted by the current formatter
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
     * @return ChildMailManagerHistoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MailManagerHistoryTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildMailManagerHistoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MailManagerHistoryTableMap::ID, $keys, Criteria::IN);
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
     * @return ChildMailManagerHistoryQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MailManagerHistoryTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MailManagerHistoryTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailManagerHistoryTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the trace_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTraceId(1234); // WHERE trace_id = 1234
     * $query->filterByTraceId(array(12, 34)); // WHERE trace_id IN (12, 34)
     * $query->filterByTraceId(array('min' => 12)); // WHERE trace_id > 12
     * </code>
     *
     * @see       filterByMailManagerTrace()
     *
     * @param     mixed $traceId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMailManagerHistoryQuery The current query, for fluid interface
     */
    public function filterByTraceId($traceId = null, $comparison = null)
    {
        if (is_array($traceId)) {
            $useMinMax = false;
            if (isset($traceId['min'])) {
                $this->addUsingAlias(MailManagerHistoryTableMap::TRACE_ID, $traceId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($traceId['max'])) {
                $this->addUsingAlias(MailManagerHistoryTableMap::TRACE_ID, $traceId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailManagerHistoryTableMap::TRACE_ID, $traceId, $comparison);
    }

    /**
     * Filter the query on the subject column
     *
     * Example usage:
     * <code>
     * $query->filterBySubject('fooValue');   // WHERE subject = 'fooValue'
     * $query->filterBySubject('%fooValue%'); // WHERE subject LIKE '%fooValue%'
     * </code>
     *
     * @param     string $subject The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMailManagerHistoryQuery The current query, for fluid interface
     */
    public function filterBySubject($subject = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($subject)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $subject)) {
                $subject = str_replace('*', '%', $subject);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(MailManagerHistoryTableMap::SUBJECT, $subject, $comparison);
    }

    /**
     * Filter the query on the body column
     *
     * @param     mixed $body The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMailManagerHistoryQuery The current query, for fluid interface
     */
    public function filterByBody($body = null, $comparison = null)
    {

        return $this->addUsingAlias(MailManagerHistoryTableMap::BODY, $body, $comparison);
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
     * @return ChildMailManagerHistoryQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(MailManagerHistoryTableMap::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(MailManagerHistoryTableMap::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailManagerHistoryTableMap::CREATED_AT, $createdAt, $comparison);
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
     * @return ChildMailManagerHistoryQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(MailManagerHistoryTableMap::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(MailManagerHistoryTableMap::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailManagerHistoryTableMap::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \TheliaMailManager\Model\MailManagerTrace object
     *
     * @param \TheliaMailManager\Model\MailManagerTrace|ObjectCollection $mailManagerTrace The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMailManagerHistoryQuery The current query, for fluid interface
     */
    public function filterByMailManagerTrace($mailManagerTrace, $comparison = null)
    {
        if ($mailManagerTrace instanceof \TheliaMailManager\Model\MailManagerTrace) {
            return $this
                ->addUsingAlias(MailManagerHistoryTableMap::TRACE_ID, $mailManagerTrace->getId(), $comparison);
        } elseif ($mailManagerTrace instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MailManagerHistoryTableMap::TRACE_ID, $mailManagerTrace->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return ChildMailManagerHistoryQuery The current query, for fluid interface
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
     * Filter the query by a related \TheliaMailManager\Model\MailManagerHistoryMail object
     *
     * @param \TheliaMailManager\Model\MailManagerHistoryMail|ObjectCollection $mailManagerHistoryMail  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMailManagerHistoryQuery The current query, for fluid interface
     */
    public function filterByMailManagerHistoryMail($mailManagerHistoryMail, $comparison = null)
    {
        if ($mailManagerHistoryMail instanceof \TheliaMailManager\Model\MailManagerHistoryMail) {
            return $this
                ->addUsingAlias(MailManagerHistoryTableMap::ID, $mailManagerHistoryMail->getHistoryId(), $comparison);
        } elseif ($mailManagerHistoryMail instanceof ObjectCollection) {
            return $this
                ->useMailManagerHistoryMailQuery()
                ->filterByPrimaryKeys($mailManagerHistoryMail->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMailManagerHistoryMail() only accepts arguments of type \TheliaMailManager\Model\MailManagerHistoryMail or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MailManagerHistoryMail relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildMailManagerHistoryQuery The current query, for fluid interface
     */
    public function joinMailManagerHistoryMail($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MailManagerHistoryMail');

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
            $this->addJoinObject($join, 'MailManagerHistoryMail');
        }

        return $this;
    }

    /**
     * Use the MailManagerHistoryMail relation MailManagerHistoryMail object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \TheliaMailManager\Model\MailManagerHistoryMailQuery A secondary query class using the current class as primary query
     */
    public function useMailManagerHistoryMailQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMailManagerHistoryMail($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MailManagerHistoryMail', '\TheliaMailManager\Model\MailManagerHistoryMailQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMailManagerHistory $mailManagerHistory Object to remove from the list of results
     *
     * @return ChildMailManagerHistoryQuery The current query, for fluid interface
     */
    public function prune($mailManagerHistory = null)
    {
        if ($mailManagerHistory) {
            $this->addUsingAlias(MailManagerHistoryTableMap::ID, $mailManagerHistory->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the mail_manager_history table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MailManagerHistoryTableMap::DATABASE_NAME);
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
            MailManagerHistoryTableMap::clearInstancePool();
            MailManagerHistoryTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildMailManagerHistory or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildMailManagerHistory object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(MailManagerHistoryTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MailManagerHistoryTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        MailManagerHistoryTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MailManagerHistoryTableMap::clearRelatedInstancePool();
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
     * @return     ChildMailManagerHistoryQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(MailManagerHistoryTableMap::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     ChildMailManagerHistoryQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(MailManagerHistoryTableMap::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     ChildMailManagerHistoryQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(MailManagerHistoryTableMap::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     ChildMailManagerHistoryQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(MailManagerHistoryTableMap::UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     ChildMailManagerHistoryQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(MailManagerHistoryTableMap::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     ChildMailManagerHistoryQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(MailManagerHistoryTableMap::CREATED_AT);
    }

} // MailManagerHistoryQuery
