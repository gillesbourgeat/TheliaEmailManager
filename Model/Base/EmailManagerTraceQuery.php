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
use TheliaEmailManager\Model\EmailManagerTrace as ChildEmailManagerTrace;
use TheliaEmailManager\Model\EmailManagerTraceI18nQuery as ChildEmailManagerTraceI18nQuery;
use TheliaEmailManager\Model\EmailManagerTraceQuery as ChildEmailManagerTraceQuery;
use TheliaEmailManager\Model\Map\EmailManagerTraceTableMap;

/**
 * Base class that represents a query for the 'email_manager_trace' table.
 *
 *
 *
 * @method     ChildEmailManagerTraceQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildEmailManagerTraceQuery orderByHash($order = Criteria::ASC) Order by the hash column
 * @method     ChildEmailManagerTraceQuery orderByDisableHistory($order = Criteria::ASC) Order by the disable_history column
 * @method     ChildEmailManagerTraceQuery orderByDisableSending($order = Criteria::ASC) Order by the disable_sending column
 * @method     ChildEmailManagerTraceQuery orderByForceSameCustomerDisable($order = Criteria::ASC) Order by the force_same_customer_disable column
 * @method     ChildEmailManagerTraceQuery orderByNumberOfCatch($order = Criteria::ASC) Order by the number_of_catch column
 * @method     ChildEmailManagerTraceQuery orderByEmailBcc($order = Criteria::ASC) Order by the email_bcc column
 * @method     ChildEmailManagerTraceQuery orderByEmailRedirect($order = Criteria::ASC) Order by the email_redirect column
 * @method     ChildEmailManagerTraceQuery orderByDetail($order = Criteria::ASC) Order by the detail column
 * @method     ChildEmailManagerTraceQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildEmailManagerTraceQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildEmailManagerTraceQuery groupById() Group by the id column
 * @method     ChildEmailManagerTraceQuery groupByHash() Group by the hash column
 * @method     ChildEmailManagerTraceQuery groupByDisableHistory() Group by the disable_history column
 * @method     ChildEmailManagerTraceQuery groupByDisableSending() Group by the disable_sending column
 * @method     ChildEmailManagerTraceQuery groupByForceSameCustomerDisable() Group by the force_same_customer_disable column
 * @method     ChildEmailManagerTraceQuery groupByNumberOfCatch() Group by the number_of_catch column
 * @method     ChildEmailManagerTraceQuery groupByEmailBcc() Group by the email_bcc column
 * @method     ChildEmailManagerTraceQuery groupByEmailRedirect() Group by the email_redirect column
 * @method     ChildEmailManagerTraceQuery groupByDetail() Group by the detail column
 * @method     ChildEmailManagerTraceQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildEmailManagerTraceQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildEmailManagerTraceQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildEmailManagerTraceQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildEmailManagerTraceQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildEmailManagerTraceQuery leftJoinEmailManagerHistory($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmailManagerHistory relation
 * @method     ChildEmailManagerTraceQuery rightJoinEmailManagerHistory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmailManagerHistory relation
 * @method     ChildEmailManagerTraceQuery innerJoinEmailManagerHistory($relationAlias = null) Adds a INNER JOIN clause to the query using the EmailManagerHistory relation
 *
 * @method     ChildEmailManagerTraceQuery leftJoinEmailManagerTraceI18n($relationAlias = null) Adds a LEFT JOIN clause to the query using the EmailManagerTraceI18n relation
 * @method     ChildEmailManagerTraceQuery rightJoinEmailManagerTraceI18n($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EmailManagerTraceI18n relation
 * @method     ChildEmailManagerTraceQuery innerJoinEmailManagerTraceI18n($relationAlias = null) Adds a INNER JOIN clause to the query using the EmailManagerTraceI18n relation
 *
 * @method     ChildEmailManagerTrace findOne(ConnectionInterface $con = null) Return the first ChildEmailManagerTrace matching the query
 * @method     ChildEmailManagerTrace findOneOrCreate(ConnectionInterface $con = null) Return the first ChildEmailManagerTrace matching the query, or a new ChildEmailManagerTrace object populated from the query conditions when no match is found
 *
 * @method     ChildEmailManagerTrace findOneById(int $id) Return the first ChildEmailManagerTrace filtered by the id column
 * @method     ChildEmailManagerTrace findOneByHash(string $hash) Return the first ChildEmailManagerTrace filtered by the hash column
 * @method     ChildEmailManagerTrace findOneByDisableHistory(boolean $disable_history) Return the first ChildEmailManagerTrace filtered by the disable_history column
 * @method     ChildEmailManagerTrace findOneByDisableSending(boolean $disable_sending) Return the first ChildEmailManagerTrace filtered by the disable_sending column
 * @method     ChildEmailManagerTrace findOneByForceSameCustomerDisable(boolean $force_same_customer_disable) Return the first ChildEmailManagerTrace filtered by the force_same_customer_disable column
 * @method     ChildEmailManagerTrace findOneByNumberOfCatch(int $number_of_catch) Return the first ChildEmailManagerTrace filtered by the number_of_catch column
 * @method     ChildEmailManagerTrace findOneByEmailBcc(array $email_bcc) Return the first ChildEmailManagerTrace filtered by the email_bcc column
 * @method     ChildEmailManagerTrace findOneByEmailRedirect(array $email_redirect) Return the first ChildEmailManagerTrace filtered by the email_redirect column
 * @method     ChildEmailManagerTrace findOneByDetail(string $detail) Return the first ChildEmailManagerTrace filtered by the detail column
 * @method     ChildEmailManagerTrace findOneByCreatedAt(string $created_at) Return the first ChildEmailManagerTrace filtered by the created_at column
 * @method     ChildEmailManagerTrace findOneByUpdatedAt(string $updated_at) Return the first ChildEmailManagerTrace filtered by the updated_at column
 *
 * @method     array findById(int $id) Return ChildEmailManagerTrace objects filtered by the id column
 * @method     array findByHash(string $hash) Return ChildEmailManagerTrace objects filtered by the hash column
 * @method     array findByDisableHistory(boolean $disable_history) Return ChildEmailManagerTrace objects filtered by the disable_history column
 * @method     array findByDisableSending(boolean $disable_sending) Return ChildEmailManagerTrace objects filtered by the disable_sending column
 * @method     array findByForceSameCustomerDisable(boolean $force_same_customer_disable) Return ChildEmailManagerTrace objects filtered by the force_same_customer_disable column
 * @method     array findByNumberOfCatch(int $number_of_catch) Return ChildEmailManagerTrace objects filtered by the number_of_catch column
 * @method     array findByEmailBcc(array $email_bcc) Return ChildEmailManagerTrace objects filtered by the email_bcc column
 * @method     array findByEmailRedirect(array $email_redirect) Return ChildEmailManagerTrace objects filtered by the email_redirect column
 * @method     array findByDetail(string $detail) Return ChildEmailManagerTrace objects filtered by the detail column
 * @method     array findByCreatedAt(string $created_at) Return ChildEmailManagerTrace objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return ChildEmailManagerTrace objects filtered by the updated_at column
 *
 */
abstract class EmailManagerTraceQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \TheliaEmailManager\Model\Base\EmailManagerTraceQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\TheliaEmailManager\\Model\\EmailManagerTrace', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildEmailManagerTraceQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildEmailManagerTraceQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \TheliaEmailManager\Model\EmailManagerTraceQuery) {
            return $criteria;
        }
        $query = new \TheliaEmailManager\Model\EmailManagerTraceQuery();
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
     * @return ChildEmailManagerTrace|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = EmailManagerTraceTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(EmailManagerTraceTableMap::DATABASE_NAME);
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
     * @return   ChildEmailManagerTrace A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, HASH, DISABLE_HISTORY, DISABLE_SENDING, FORCE_SAME_CUSTOMER_DISABLE, NUMBER_OF_CATCH, EMAIL_BCC, EMAIL_REDIRECT, DETAIL, CREATED_AT, UPDATED_AT FROM email_manager_trace WHERE ID = :p0';
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
            $obj = new ChildEmailManagerTrace();
            $obj->hydrate($row);
            EmailManagerTraceTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildEmailManagerTrace|array|mixed the result, formatted by the current formatter
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
     * @return ChildEmailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EmailManagerTraceTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildEmailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EmailManagerTraceTableMap::ID, $keys, Criteria::IN);
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
     * @return ChildEmailManagerTraceQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(EmailManagerTraceTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(EmailManagerTraceTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailManagerTraceTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the hash column
     *
     * Example usage:
     * <code>
     * $query->filterByHash('fooValue');   // WHERE hash = 'fooValue'
     * $query->filterByHash('%fooValue%'); // WHERE hash LIKE '%fooValue%'
     * </code>
     *
     * @param     string $hash The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByHash($hash = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($hash)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $hash)) {
                $hash = str_replace('*', '%', $hash);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EmailManagerTraceTableMap::HASH, $hash, $comparison);
    }

    /**
     * Filter the query on the disable_history column
     *
     * Example usage:
     * <code>
     * $query->filterByDisableHistory(true); // WHERE disable_history = true
     * $query->filterByDisableHistory('yes'); // WHERE disable_history = true
     * </code>
     *
     * @param     boolean|string $disableHistory The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByDisableHistory($disableHistory = null, $comparison = null)
    {
        if (is_string($disableHistory)) {
            $disable_history = in_array(strtolower($disableHistory), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(EmailManagerTraceTableMap::DISABLE_HISTORY, $disableHistory, $comparison);
    }

    /**
     * Filter the query on the disable_sending column
     *
     * Example usage:
     * <code>
     * $query->filterByDisableSending(true); // WHERE disable_sending = true
     * $query->filterByDisableSending('yes'); // WHERE disable_sending = true
     * </code>
     *
     * @param     boolean|string $disableSending The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByDisableSending($disableSending = null, $comparison = null)
    {
        if (is_string($disableSending)) {
            $disable_sending = in_array(strtolower($disableSending), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(EmailManagerTraceTableMap::DISABLE_SENDING, $disableSending, $comparison);
    }

    /**
     * Filter the query on the force_same_customer_disable column
     *
     * Example usage:
     * <code>
     * $query->filterByForceSameCustomerDisable(true); // WHERE force_same_customer_disable = true
     * $query->filterByForceSameCustomerDisable('yes'); // WHERE force_same_customer_disable = true
     * </code>
     *
     * @param     boolean|string $forceSameCustomerDisable The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByForceSameCustomerDisable($forceSameCustomerDisable = null, $comparison = null)
    {
        if (is_string($forceSameCustomerDisable)) {
            $force_same_customer_disable = in_array(strtolower($forceSameCustomerDisable), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(EmailManagerTraceTableMap::FORCE_SAME_CUSTOMER_DISABLE, $forceSameCustomerDisable, $comparison);
    }

    /**
     * Filter the query on the number_of_catch column
     *
     * Example usage:
     * <code>
     * $query->filterByNumberOfCatch(1234); // WHERE number_of_catch = 1234
     * $query->filterByNumberOfCatch(array(12, 34)); // WHERE number_of_catch IN (12, 34)
     * $query->filterByNumberOfCatch(array('min' => 12)); // WHERE number_of_catch > 12
     * </code>
     *
     * @param     mixed $numberOfCatch The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByNumberOfCatch($numberOfCatch = null, $comparison = null)
    {
        if (is_array($numberOfCatch)) {
            $useMinMax = false;
            if (isset($numberOfCatch['min'])) {
                $this->addUsingAlias(EmailManagerTraceTableMap::NUMBER_OF_CATCH, $numberOfCatch['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($numberOfCatch['max'])) {
                $this->addUsingAlias(EmailManagerTraceTableMap::NUMBER_OF_CATCH, $numberOfCatch['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailManagerTraceTableMap::NUMBER_OF_CATCH, $numberOfCatch, $comparison);
    }

    /**
     * Filter the query on the email_bcc column
     *
     * @param     array $emailBcc The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByEmailBcc($emailBcc = null, $comparison = null)
    {
        $key = $this->getAliasedColName(EmailManagerTraceTableMap::EMAIL_BCC);
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            foreach ($emailBcc as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_SOME) {
            foreach ($emailBcc as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addOr($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            foreach ($emailBcc as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::NOT_LIKE);
                } else {
                    $this->add($key, $value, Criteria::NOT_LIKE);
                }
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(EmailManagerTraceTableMap::EMAIL_BCC, $emailBcc, $comparison);
    }

    /**
     * Filter the query on the email_redirect column
     *
     * @param     array $emailRedirect The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByEmailRedirect($emailRedirect = null, $comparison = null)
    {
        $key = $this->getAliasedColName(EmailManagerTraceTableMap::EMAIL_REDIRECT);
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            foreach ($emailRedirect as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_SOME) {
            foreach ($emailRedirect as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addOr($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            foreach ($emailRedirect as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::NOT_LIKE);
                } else {
                    $this->add($key, $value, Criteria::NOT_LIKE);
                }
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(EmailManagerTraceTableMap::EMAIL_REDIRECT, $emailRedirect, $comparison);
    }

    /**
     * Filter the query on the detail column
     *
     * Example usage:
     * <code>
     * $query->filterByDetail('fooValue');   // WHERE detail = 'fooValue'
     * $query->filterByDetail('%fooValue%'); // WHERE detail LIKE '%fooValue%'
     * </code>
     *
     * @param     string $detail The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByDetail($detail = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($detail)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $detail)) {
                $detail = str_replace('*', '%', $detail);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EmailManagerTraceTableMap::DETAIL, $detail, $comparison);
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
     * @return ChildEmailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(EmailManagerTraceTableMap::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(EmailManagerTraceTableMap::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailManagerTraceTableMap::CREATED_AT, $createdAt, $comparison);
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
     * @return ChildEmailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(EmailManagerTraceTableMap::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(EmailManagerTraceTableMap::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailManagerTraceTableMap::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \TheliaEmailManager\Model\EmailManagerHistory object
     *
     * @param \TheliaEmailManager\Model\EmailManagerHistory|ObjectCollection $emailManagerHistory  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByEmailManagerHistory($emailManagerHistory, $comparison = null)
    {
        if ($emailManagerHistory instanceof \TheliaEmailManager\Model\EmailManagerHistory) {
            return $this
                ->addUsingAlias(EmailManagerTraceTableMap::ID, $emailManagerHistory->getTraceId(), $comparison);
        } elseif ($emailManagerHistory instanceof ObjectCollection) {
            return $this
                ->useEmailManagerHistoryQuery()
                ->filterByPrimaryKeys($emailManagerHistory->getPrimaryKeys())
                ->endUse();
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
     * @return ChildEmailManagerTraceQuery The current query, for fluid interface
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
     * Filter the query by a related \TheliaEmailManager\Model\EmailManagerTraceI18n object
     *
     * @param \TheliaEmailManager\Model\EmailManagerTraceI18n|ObjectCollection $emailManagerTraceI18n  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEmailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByEmailManagerTraceI18n($emailManagerTraceI18n, $comparison = null)
    {
        if ($emailManagerTraceI18n instanceof \TheliaEmailManager\Model\EmailManagerTraceI18n) {
            return $this
                ->addUsingAlias(EmailManagerTraceTableMap::ID, $emailManagerTraceI18n->getId(), $comparison);
        } elseif ($emailManagerTraceI18n instanceof ObjectCollection) {
            return $this
                ->useEmailManagerTraceI18nQuery()
                ->filterByPrimaryKeys($emailManagerTraceI18n->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEmailManagerTraceI18n() only accepts arguments of type \TheliaEmailManager\Model\EmailManagerTraceI18n or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EmailManagerTraceI18n relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildEmailManagerTraceQuery The current query, for fluid interface
     */
    public function joinEmailManagerTraceI18n($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EmailManagerTraceI18n');

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
            $this->addJoinObject($join, 'EmailManagerTraceI18n');
        }

        return $this;
    }

    /**
     * Use the EmailManagerTraceI18n relation EmailManagerTraceI18n object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \TheliaEmailManager\Model\EmailManagerTraceI18nQuery A secondary query class using the current class as primary query
     */
    public function useEmailManagerTraceI18nQuery($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        return $this
            ->joinEmailManagerTraceI18n($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EmailManagerTraceI18n', '\TheliaEmailManager\Model\EmailManagerTraceI18nQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildEmailManagerTrace $emailManagerTrace Object to remove from the list of results
     *
     * @return ChildEmailManagerTraceQuery The current query, for fluid interface
     */
    public function prune($emailManagerTrace = null)
    {
        if ($emailManagerTrace) {
            $this->addUsingAlias(EmailManagerTraceTableMap::ID, $emailManagerTrace->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the email_manager_trace table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EmailManagerTraceTableMap::DATABASE_NAME);
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
            EmailManagerTraceTableMap::clearInstancePool();
            EmailManagerTraceTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildEmailManagerTrace or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildEmailManagerTrace object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(EmailManagerTraceTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(EmailManagerTraceTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        EmailManagerTraceTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            EmailManagerTraceTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    // i18n behavior

    /**
     * Adds a JOIN clause to the query using the i18n relation
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildEmailManagerTraceQuery The current query, for fluid interface
     */
    public function joinI18n($locale = 'en_US', $relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $relationName = $relationAlias ? $relationAlias : 'EmailManagerTraceI18n';

        return $this
            ->joinEmailManagerTraceI18n($relationAlias, $joinType)
            ->addJoinCondition($relationName, $relationName . '.Locale = ?', $locale);
    }

    /**
     * Adds a JOIN clause to the query and hydrates the related I18n object.
     * Shortcut for $c->joinI18n($locale)->with()
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildEmailManagerTraceQuery The current query, for fluid interface
     */
    public function joinWithI18n($locale = 'en_US', $joinType = Criteria::LEFT_JOIN)
    {
        $this
            ->joinI18n($locale, null, $joinType)
            ->with('EmailManagerTraceI18n');
        $this->with['EmailManagerTraceI18n']->setIsWithOneToMany(false);

        return $this;
    }

    /**
     * Use the I18n relation query object
     *
     * @see       useQuery()
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildEmailManagerTraceI18nQuery A secondary query class using the current class as primary query
     */
    public function useI18nQuery($locale = 'en_US', $relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinI18n($locale, $relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EmailManagerTraceI18n', '\TheliaEmailManager\Model\EmailManagerTraceI18nQuery');
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     ChildEmailManagerTraceQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(EmailManagerTraceTableMap::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     ChildEmailManagerTraceQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(EmailManagerTraceTableMap::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     ChildEmailManagerTraceQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(EmailManagerTraceTableMap::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     ChildEmailManagerTraceQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(EmailManagerTraceTableMap::UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     ChildEmailManagerTraceQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(EmailManagerTraceTableMap::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     ChildEmailManagerTraceQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(EmailManagerTraceTableMap::CREATED_AT);
    }

} // EmailManagerTraceQuery
