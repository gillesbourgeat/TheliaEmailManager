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
use TheliaEmailManager\Model\EmailManagerHistory as ChildEmailManagerHistory;
use TheliaEmailManager\Model\EmailManagerHistoryQuery as ChildEmailManagerHistoryQuery;
use TheliaEmailManager\Model\Map\EmailManagerHistoryTableMap;

/**
 * Base class that represents a query for the 'email_manager_history' table.
 *
 *
 *
 * @method     ChildEmailManagerHistoryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildEmailManagerHistoryQuery orderByTraceId($order = Criteria::ASC) Order by the trace_id column
 * @method     ChildEmailManagerHistoryQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method     ChildEmailManagerHistoryQuery orderBySubject($order = Criteria::ASC) Order by the subject column
 * @method     ChildEmailManagerHistoryQuery orderByInfo($order = Criteria::ASC) Order by the info column
 * @method     ChildEmailManagerHistoryQuery orderByBody($order = Criteria::ASC) Order by the body column
 * @method     ChildEmailManagerHistoryQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildEmailManagerHistoryQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildEmailManagerHistoryQuery groupById() Group by the id column
 * @method     ChildEmailManagerHistoryQuery groupByTraceId() Group by the trace_id column
 * @method     ChildEmailManagerHistoryQuery groupByStatus() Group by the status column
 * @method     ChildEmailManagerHistoryQuery groupBySubject() Group by the subject column
 * @method     ChildEmailManagerHistoryQuery groupByInfo() Group by the info column
 * @method     ChildEmailManagerHistoryQuery groupByBody() Group by the body column
 * @method     ChildEmailManagerHistoryQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildEmailManagerHistoryQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildEmailManagerHistoryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildEmailManagerHistoryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildEmailManagerHistoryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildEmailManagerHistoryQuery leftJoinEmailManagerTrace($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmailManagerTrace relation
 * @method     ChildEmailManagerHistoryQuery rightJoinEmailManagerTrace($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmailManagerTrace relation
 * @method     ChildEmailManagerHistoryQuery innerJoinEmailManagerTrace($relationAlias = null) Adds a INNER JOIN clause to the query using the EmailManagerTrace relation
 *
 * @method     ChildEmailManagerHistoryQuery leftJoinEmailManagerHistoryEmail($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmailManagerHistoryEmail relation
 * @method     ChildEmailManagerHistoryQuery rightJoinEmailManagerHistoryEmail($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmailManagerHistoryEmail relation
 * @method     ChildEmailManagerHistoryQuery innerJoinEmailManagerHistoryEmail($relationAlias = null) Adds a INNER JOIN clause to the query using the EmailManagerHistoryEmail relation
 *
 * @method     ChildEmailManagerHistory findOne(ConnectionInterface $con = null) Return the first ChildEmailManagerHistory matching the query
 * @method     ChildEmailManagerHistory findOneOrCreate(ConnectionInterface $con = null) Return the first ChildEmailManagerHistory matching the query, or a new ChildEmailManagerHistory object populated from the query conditions when no match is found
 *
 * @method     ChildEmailManagerHistory findOneById(int $id) Return the first ChildEmailManagerHistory filtered by the id column
 * @method     ChildEmailManagerHistory findOneByTraceId(int $trace_id) Return the first ChildEmailManagerHistory filtered by the trace_id column
 * @method     ChildEmailManagerHistory findOneByStatus(int $status) Return the first ChildEmailManagerHistory filtered by the status column
 * @method     ChildEmailManagerHistory findOneBySubject(string $subject) Return the first ChildEmailManagerHistory filtered by the subject column
 * @method     ChildEmailManagerHistory findOneByInfo(string $info) Return the first ChildEmailManagerHistory filtered by the info column
 * @method     ChildEmailManagerHistory findOneByBody(resource $body) Return the first ChildEmailManagerHistory filtered by the body column
 * @method     ChildEmailManagerHistory findOneByCreatedAt(string $created_at) Return the first ChildEmailManagerHistory filtered by the created_at column
 * @method     ChildEmailManagerHistory findOneByUpdatedAt(string $updated_at) Return the first ChildEmailManagerHistory filtered by the updated_at column
 *
 * @method     array findById(int $id) Return ChildEmailManagerHistory objects filtered by the id column
 * @method     array findByTraceId(int $trace_id) Return ChildEmailManagerHistory objects filtered by the trace_id column
 * @method     array findByStatus(int $status) Return ChildEmailManagerHistory objects filtered by the status column
 * @method     array findBySubject(string $subject) Return ChildEmailManagerHistory objects filtered by the subject column
 * @method     array findByInfo(string $info) Return ChildEmailManagerHistory objects filtered by the info column
 * @method     array findByBody(resource $body) Return ChildEmailManagerHistory objects filtered by the body column
 * @method     array findByCreatedAt(string $created_at) Return ChildEmailManagerHistory objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return ChildEmailManagerHistory objects filtered by the updated_at column
 *
 */
abstract class EmailManagerHistoryQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \TheliaEmailManager\Model\Base\EmailManagerHistoryQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\TheliaEmailManager\\Model\\EmailManagerHistory', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildEmailManagerHistoryQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildEmailManagerHistoryQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \TheliaEmailManager\Model\EmailManagerHistoryQuery) {
            return $criteria;
        }
        $query = new \TheliaEmailManager\Model\EmailManagerHistoryQuery();
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
     * @return ChildEmailManagerHistory|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = EmailManagerHistoryTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(EmailManagerHistoryTableMap::DATABASE_NAME);
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
     * @return   ChildEmailManagerHistory A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, TRACE_ID, STATUS, SUBJECT, INFO, BODY, CREATED_AT, UPDATED_AT FROM email_manager_history WHERE ID = :p0';
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
            $obj = new ChildEmailManagerHistory();
            $obj->hydrate($row);
            EmailManagerHistoryTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildEmailManagerHistory|array|mixed the result, formatted by the current formatter
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
     * @return ChildEmailManagerHistoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EmailManagerHistoryTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildEmailManagerHistoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EmailManagerHistoryTableMap::ID, $keys, Criteria::IN);
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
     * @return ChildEmailManagerHistoryQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(EmailManagerHistoryTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(EmailManagerHistoryTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailManagerHistoryTableMap::ID, $id, $comparison);
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
     * @see       filterByEmailManagerTrace()
     *
     * @param     mixed $traceId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerHistoryQuery The current query, for fluid interface
     */
    public function filterByTraceId($traceId = null, $comparison = null)
    {
        if (is_array($traceId)) {
            $useMinMax = false;
            if (isset($traceId['min'])) {
                $this->addUsingAlias(EmailManagerHistoryTableMap::TRACE_ID, $traceId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($traceId['max'])) {
                $this->addUsingAlias(EmailManagerHistoryTableMap::TRACE_ID, $traceId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailManagerHistoryTableMap::TRACE_ID, $traceId, $comparison);
    }

    /**
     * Filter the query on the status column
     *
     * Example usage:
     * <code>
     * $query->filterByStatus(1234); // WHERE status = 1234
     * $query->filterByStatus(array(12, 34)); // WHERE status IN (12, 34)
     * $query->filterByStatus(array('min' => 12)); // WHERE status > 12
     * </code>
     *
     * @param     mixed $status The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerHistoryQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_array($status)) {
            $useMinMax = false;
            if (isset($status['min'])) {
                $this->addUsingAlias(EmailManagerHistoryTableMap::STATUS, $status['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($status['max'])) {
                $this->addUsingAlias(EmailManagerHistoryTableMap::STATUS, $status['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailManagerHistoryTableMap::STATUS, $status, $comparison);
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
     * @return ChildEmailManagerHistoryQuery The current query, for fluid interface
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

        return $this->addUsingAlias(EmailManagerHistoryTableMap::SUBJECT, $subject, $comparison);
    }

    /**
     * Filter the query on the info column
     *
     * Example usage:
     * <code>
     * $query->filterByInfo('fooValue');   // WHERE info = 'fooValue'
     * $query->filterByInfo('%fooValue%'); // WHERE info LIKE '%fooValue%'
     * </code>
     *
     * @param     string $info The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerHistoryQuery The current query, for fluid interface
     */
    public function filterByInfo($info = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($info)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $info)) {
                $info = str_replace('*', '%', $info);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EmailManagerHistoryTableMap::INFO, $info, $comparison);
    }

    /**
     * Filter the query on the body column
     *
     * @param     mixed $body The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerHistoryQuery The current query, for fluid interface
     */
    public function filterByBody($body = null, $comparison = null)
    {

        return $this->addUsingAlias(EmailManagerHistoryTableMap::BODY, $body, $comparison);
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
     * @return ChildEmailManagerHistoryQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(EmailManagerHistoryTableMap::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(EmailManagerHistoryTableMap::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailManagerHistoryTableMap::CREATED_AT, $createdAt, $comparison);
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
     * @return ChildEmailManagerHistoryQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(EmailManagerHistoryTableMap::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(EmailManagerHistoryTableMap::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailManagerHistoryTableMap::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \TheliaEmailManager\Model\EmailManagerTrace object
     *
     * @param \TheliaEmailManager\Model\EmailManagerTrace|ObjectCollection $emailManagerTrace The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerHistoryQuery The current query, for fluid interface
     */
    public function filterByEmailManagerTrace($emailManagerTrace, $comparison = null)
    {
        if ($emailManagerTrace instanceof \TheliaEmailManager\Model\EmailManagerTrace) {
            return $this
                ->addUsingAlias(EmailManagerHistoryTableMap::TRACE_ID, $emailManagerTrace->getId(), $comparison);
        } elseif ($emailManagerTrace instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EmailManagerHistoryTableMap::TRACE_ID, $emailManagerTrace->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByEmailManagerTrace() only accepts arguments of type \TheliaEmailManager\Model\EmailManagerTrace or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EmailManagerTrace relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildEmailManagerHistoryQuery The current query, for fluid interface
     */
    public function joinEmailManagerTrace($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EmailManagerTrace');

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
            $this->addJoinObject($join, 'EmailManagerTrace');
        }

        return $this;
    }

    /**
     * Use the EmailManagerTrace relation EmailManagerTrace object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \TheliaEmailManager\Model\EmailManagerTraceQuery A secondary query class using the current class as primary query
     */
    public function useEmailManagerTraceQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEmailManagerTrace($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EmailManagerTrace', '\TheliaEmailManager\Model\EmailManagerTraceQuery');
    }

    /**
     * Filter the query by a related \TheliaEmailManager\Model\EmailManagerHistoryEmail object
     *
     * @param \TheliaEmailManager\Model\EmailManagerHistoryEmail|ObjectCollection $emailManagerHistoryEmail  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerHistoryQuery The current query, for fluid interface
     */
    public function filterByEmailManagerHistoryEmail($emailManagerHistoryEmail, $comparison = null)
    {
        if ($emailManagerHistoryEmail instanceof \TheliaEmailManager\Model\EmailManagerHistoryEmail) {
            return $this
                ->addUsingAlias(EmailManagerHistoryTableMap::ID, $emailManagerHistoryEmail->getHistoryId(), $comparison);
        } elseif ($emailManagerHistoryEmail instanceof ObjectCollection) {
            return $this
                ->useEmailManagerHistoryEmailQuery()
                ->filterByPrimaryKeys($emailManagerHistoryEmail->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEmailManagerHistoryEmail() only accepts arguments of type \TheliaEmailManager\Model\EmailManagerHistoryEmail or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EmailManagerHistoryEmail relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildEmailManagerHistoryQuery The current query, for fluid interface
     */
    public function joinEmailManagerHistoryEmail($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EmailManagerHistoryEmail');

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
            $this->addJoinObject($join, 'EmailManagerHistoryEmail');
        }

        return $this;
    }

    /**
     * Use the EmailManagerHistoryEmail relation EmailManagerHistoryEmail object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \TheliaEmailManager\Model\EmailManagerHistoryEmailQuery A secondary query class using the current class as primary query
     */
    public function useEmailManagerHistoryEmailQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEmailManagerHistoryEmail($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EmailManagerHistoryEmail', '\TheliaEmailManager\Model\EmailManagerHistoryEmailQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildEmailManagerHistory $emailManagerHistory Object to remove from the list of results
     *
     * @return ChildEmailManagerHistoryQuery The current query, for fluid interface
     */
    public function prune($emailManagerHistory = null)
    {
        if ($emailManagerHistory) {
            $this->addUsingAlias(EmailManagerHistoryTableMap::ID, $emailManagerHistory->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the email_manager_history table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EmailManagerHistoryTableMap::DATABASE_NAME);
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
            EmailManagerHistoryTableMap::clearInstancePool();
            EmailManagerHistoryTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildEmailManagerHistory or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildEmailManagerHistory object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(EmailManagerHistoryTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(EmailManagerHistoryTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        EmailManagerHistoryTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            EmailManagerHistoryTableMap::clearRelatedInstancePool();
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
     * @return     ChildEmailManagerHistoryQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(EmailManagerHistoryTableMap::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     ChildEmailManagerHistoryQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(EmailManagerHistoryTableMap::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     ChildEmailManagerHistoryQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(EmailManagerHistoryTableMap::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     ChildEmailManagerHistoryQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(EmailManagerHistoryTableMap::UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     ChildEmailManagerHistoryQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(EmailManagerHistoryTableMap::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     ChildEmailManagerHistoryQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(EmailManagerHistoryTableMap::CREATED_AT);
    }

} // EmailManagerHistoryQuery
