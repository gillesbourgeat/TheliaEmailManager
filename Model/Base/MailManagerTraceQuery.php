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
use TheliaMailManager\Model\MailManagerTrace as ChildMailManagerTrace;
use TheliaMailManager\Model\MailManagerTraceQuery as ChildMailManagerTraceQuery;
use TheliaMailManager\Model\Map\MailManagerTraceTableMap;

/**
 * Base class that represents a query for the 'mail_manager_trace' table.
 *
 *
 *
 * @method     ChildMailManagerTraceQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildMailManagerTraceQuery orderByHash($order = Criteria::ASC) Order by the hash column
 * @method     ChildMailManagerTraceQuery orderByDisableHistory($order = Criteria::ASC) Order by the disable_history column
 * @method     ChildMailManagerTraceQuery orderByDisableSending($order = Criteria::ASC) Order by the disable_sending column
 * @method     ChildMailManagerTraceQuery orderByForceSameCustomerDisable($order = Criteria::ASC) Order by the force_same_customer_disable column
 * @method     ChildMailManagerTraceQuery orderByNumberOfCatch($order = Criteria::ASC) Order by the number_of_catch column
 * @method     ChildMailManagerTraceQuery orderByEmailBcc($order = Criteria::ASC) Order by the email_bcc column
 * @method     ChildMailManagerTraceQuery orderByEmailRedirect($order = Criteria::ASC) Order by the email_redirect column
 * @method     ChildMailManagerTraceQuery orderByDetail($order = Criteria::ASC) Order by the detail column
 * @method     ChildMailManagerTraceQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildMailManagerTraceQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildMailManagerTraceQuery groupById() Group by the id column
 * @method     ChildMailManagerTraceQuery groupByHash() Group by the hash column
 * @method     ChildMailManagerTraceQuery groupByDisableHistory() Group by the disable_history column
 * @method     ChildMailManagerTraceQuery groupByDisableSending() Group by the disable_sending column
 * @method     ChildMailManagerTraceQuery groupByForceSameCustomerDisable() Group by the force_same_customer_disable column
 * @method     ChildMailManagerTraceQuery groupByNumberOfCatch() Group by the number_of_catch column
 * @method     ChildMailManagerTraceQuery groupByEmailBcc() Group by the email_bcc column
 * @method     ChildMailManagerTraceQuery groupByEmailRedirect() Group by the email_redirect column
 * @method     ChildMailManagerTraceQuery groupByDetail() Group by the detail column
 * @method     ChildMailManagerTraceQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildMailManagerTraceQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildMailManagerTraceQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMailManagerTraceQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMailManagerTraceQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMailManagerTraceQuery leftJoinMailManagerTraceComment($relationAlias = null) Adds a LEFT JOIN clause to the query using the MailManagerTraceComment relation
 * @method     ChildMailManagerTraceQuery rightJoinMailManagerTraceComment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MailManagerTraceComment relation
 * @method     ChildMailManagerTraceQuery innerJoinMailManagerTraceComment($relationAlias = null) Adds a INNER JOIN clause to the query using the MailManagerTraceComment relation
 *
 * @method     ChildMailManagerTraceQuery leftJoinMailManagerHistory($relationAlias = null) Adds a LEFT JOIN clause to the query using the MailManagerHistory relation
 * @method     ChildMailManagerTraceQuery rightJoinMailManagerHistory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MailManagerHistory relation
 * @method     ChildMailManagerTraceQuery innerJoinMailManagerHistory($relationAlias = null) Adds a INNER JOIN clause to the query using the MailManagerHistory relation
 *
 * @method     ChildMailManagerTraceQuery leftJoinMailManagerHistoryMail($relationAlias = null) Adds a LEFT JOIN clause to the query using the MailManagerHistoryMail relation
 * @method     ChildMailManagerTraceQuery rightJoinMailManagerHistoryMail($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MailManagerHistoryMail relation
 * @method     ChildMailManagerTraceQuery innerJoinMailManagerHistoryMail($relationAlias = null) Adds a INNER JOIN clause to the query using the MailManagerHistoryMail relation
 *
 * @method     ChildMailManagerTrace findOne(ConnectionInterface $con = null) Return the first ChildMailManagerTrace matching the query
 * @method     ChildMailManagerTrace findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMailManagerTrace matching the query, or a new ChildMailManagerTrace object populated from the query conditions when no match is found
 *
 * @method     ChildMailManagerTrace findOneById(int $id) Return the first ChildMailManagerTrace filtered by the id column
 * @method     ChildMailManagerTrace findOneByHash(string $hash) Return the first ChildMailManagerTrace filtered by the hash column
 * @method     ChildMailManagerTrace findOneByDisableHistory(boolean $disable_history) Return the first ChildMailManagerTrace filtered by the disable_history column
 * @method     ChildMailManagerTrace findOneByDisableSending(boolean $disable_sending) Return the first ChildMailManagerTrace filtered by the disable_sending column
 * @method     ChildMailManagerTrace findOneByForceSameCustomerDisable(boolean $force_same_customer_disable) Return the first ChildMailManagerTrace filtered by the force_same_customer_disable column
 * @method     ChildMailManagerTrace findOneByNumberOfCatch(int $number_of_catch) Return the first ChildMailManagerTrace filtered by the number_of_catch column
 * @method     ChildMailManagerTrace findOneByEmailBcc(array $email_bcc) Return the first ChildMailManagerTrace filtered by the email_bcc column
 * @method     ChildMailManagerTrace findOneByEmailRedirect(array $email_redirect) Return the first ChildMailManagerTrace filtered by the email_redirect column
 * @method     ChildMailManagerTrace findOneByDetail(string $detail) Return the first ChildMailManagerTrace filtered by the detail column
 * @method     ChildMailManagerTrace findOneByCreatedAt(string $created_at) Return the first ChildMailManagerTrace filtered by the created_at column
 * @method     ChildMailManagerTrace findOneByUpdatedAt(string $updated_at) Return the first ChildMailManagerTrace filtered by the updated_at column
 *
 * @method     array findById(int $id) Return ChildMailManagerTrace objects filtered by the id column
 * @method     array findByHash(string $hash) Return ChildMailManagerTrace objects filtered by the hash column
 * @method     array findByDisableHistory(boolean $disable_history) Return ChildMailManagerTrace objects filtered by the disable_history column
 * @method     array findByDisableSending(boolean $disable_sending) Return ChildMailManagerTrace objects filtered by the disable_sending column
 * @method     array findByForceSameCustomerDisable(boolean $force_same_customer_disable) Return ChildMailManagerTrace objects filtered by the force_same_customer_disable column
 * @method     array findByNumberOfCatch(int $number_of_catch) Return ChildMailManagerTrace objects filtered by the number_of_catch column
 * @method     array findByEmailBcc(array $email_bcc) Return ChildMailManagerTrace objects filtered by the email_bcc column
 * @method     array findByEmailRedirect(array $email_redirect) Return ChildMailManagerTrace objects filtered by the email_redirect column
 * @method     array findByDetail(string $detail) Return ChildMailManagerTrace objects filtered by the detail column
 * @method     array findByCreatedAt(string $created_at) Return ChildMailManagerTrace objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return ChildMailManagerTrace objects filtered by the updated_at column
 *
 */
abstract class MailManagerTraceQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \TheliaMailManager\Model\Base\MailManagerTraceQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\TheliaMailManager\\Model\\MailManagerTrace', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMailManagerTraceQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMailManagerTraceQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \TheliaMailManager\Model\MailManagerTraceQuery) {
            return $criteria;
        }
        $query = new \TheliaMailManager\Model\MailManagerTraceQuery();
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
     * @return ChildMailManagerTrace|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = MailManagerTraceTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MailManagerTraceTableMap::DATABASE_NAME);
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
     * @return   ChildMailManagerTrace A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, HASH, DISABLE_HISTORY, DISABLE_SENDING, FORCE_SAME_CUSTOMER_DISABLE, NUMBER_OF_CATCH, EMAIL_BCC, EMAIL_REDIRECT, DETAIL, CREATED_AT, UPDATED_AT FROM mail_manager_trace WHERE ID = :p0';
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
            $obj = new ChildMailManagerTrace();
            $obj->hydrate($row);
            MailManagerTraceTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildMailManagerTrace|array|mixed the result, formatted by the current formatter
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
     * @return ChildMailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MailManagerTraceTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildMailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MailManagerTraceTableMap::ID, $keys, Criteria::IN);
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
     * @return ChildMailManagerTraceQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MailManagerTraceTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MailManagerTraceTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailManagerTraceTableMap::ID, $id, $comparison);
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
     * @return ChildMailManagerTraceQuery The current query, for fluid interface
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

        return $this->addUsingAlias(MailManagerTraceTableMap::HASH, $hash, $comparison);
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
     * @return ChildMailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByDisableHistory($disableHistory = null, $comparison = null)
    {
        if (is_string($disableHistory)) {
            $disable_history = in_array(strtolower($disableHistory), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(MailManagerTraceTableMap::DISABLE_HISTORY, $disableHistory, $comparison);
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
     * @return ChildMailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByDisableSending($disableSending = null, $comparison = null)
    {
        if (is_string($disableSending)) {
            $disable_sending = in_array(strtolower($disableSending), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(MailManagerTraceTableMap::DISABLE_SENDING, $disableSending, $comparison);
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
     * @return ChildMailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByForceSameCustomerDisable($forceSameCustomerDisable = null, $comparison = null)
    {
        if (is_string($forceSameCustomerDisable)) {
            $force_same_customer_disable = in_array(strtolower($forceSameCustomerDisable), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(MailManagerTraceTableMap::FORCE_SAME_CUSTOMER_DISABLE, $forceSameCustomerDisable, $comparison);
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
     * @return ChildMailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByNumberOfCatch($numberOfCatch = null, $comparison = null)
    {
        if (is_array($numberOfCatch)) {
            $useMinMax = false;
            if (isset($numberOfCatch['min'])) {
                $this->addUsingAlias(MailManagerTraceTableMap::NUMBER_OF_CATCH, $numberOfCatch['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($numberOfCatch['max'])) {
                $this->addUsingAlias(MailManagerTraceTableMap::NUMBER_OF_CATCH, $numberOfCatch['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailManagerTraceTableMap::NUMBER_OF_CATCH, $numberOfCatch, $comparison);
    }

    /**
     * Filter the query on the email_bcc column
     *
     * @param     array $emailBcc The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByEmailBcc($emailBcc = null, $comparison = null)
    {
        $key = $this->getAliasedColName(MailManagerTraceTableMap::EMAIL_BCC);
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

        return $this->addUsingAlias(MailManagerTraceTableMap::EMAIL_BCC, $emailBcc, $comparison);
    }

    /**
     * Filter the query on the email_redirect column
     *
     * @param     array $emailRedirect The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByEmailRedirect($emailRedirect = null, $comparison = null)
    {
        $key = $this->getAliasedColName(MailManagerTraceTableMap::EMAIL_REDIRECT);
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

        return $this->addUsingAlias(MailManagerTraceTableMap::EMAIL_REDIRECT, $emailRedirect, $comparison);
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
     * @return ChildMailManagerTraceQuery The current query, for fluid interface
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

        return $this->addUsingAlias(MailManagerTraceTableMap::DETAIL, $detail, $comparison);
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
     * @return ChildMailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(MailManagerTraceTableMap::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(MailManagerTraceTableMap::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailManagerTraceTableMap::CREATED_AT, $createdAt, $comparison);
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
     * @return ChildMailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(MailManagerTraceTableMap::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(MailManagerTraceTableMap::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailManagerTraceTableMap::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \TheliaMailManager\Model\MailManagerTraceComment object
     *
     * @param \TheliaMailManager\Model\MailManagerTraceComment|ObjectCollection $mailManagerTraceComment  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByMailManagerTraceComment($mailManagerTraceComment, $comparison = null)
    {
        if ($mailManagerTraceComment instanceof \TheliaMailManager\Model\MailManagerTraceComment) {
            return $this
                ->addUsingAlias(MailManagerTraceTableMap::ID, $mailManagerTraceComment->getTraceId(), $comparison);
        } elseif ($mailManagerTraceComment instanceof ObjectCollection) {
            return $this
                ->useMailManagerTraceCommentQuery()
                ->filterByPrimaryKeys($mailManagerTraceComment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMailManagerTraceComment() only accepts arguments of type \TheliaMailManager\Model\MailManagerTraceComment or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MailManagerTraceComment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildMailManagerTraceQuery The current query, for fluid interface
     */
    public function joinMailManagerTraceComment($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MailManagerTraceComment');

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
            $this->addJoinObject($join, 'MailManagerTraceComment');
        }

        return $this;
    }

    /**
     * Use the MailManagerTraceComment relation MailManagerTraceComment object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \TheliaMailManager\Model\MailManagerTraceCommentQuery A secondary query class using the current class as primary query
     */
    public function useMailManagerTraceCommentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMailManagerTraceComment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MailManagerTraceComment', '\TheliaMailManager\Model\MailManagerTraceCommentQuery');
    }

    /**
     * Filter the query by a related \TheliaMailManager\Model\MailManagerHistory object
     *
     * @param \TheliaMailManager\Model\MailManagerHistory|ObjectCollection $mailManagerHistory  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByMailManagerHistory($mailManagerHistory, $comparison = null)
    {
        if ($mailManagerHistory instanceof \TheliaMailManager\Model\MailManagerHistory) {
            return $this
                ->addUsingAlias(MailManagerTraceTableMap::ID, $mailManagerHistory->getTraceId(), $comparison);
        } elseif ($mailManagerHistory instanceof ObjectCollection) {
            return $this
                ->useMailManagerHistoryQuery()
                ->filterByPrimaryKeys($mailManagerHistory->getPrimaryKeys())
                ->endUse();
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
     * @return ChildMailManagerTraceQuery The current query, for fluid interface
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
     * Filter the query by a related \TheliaMailManager\Model\MailManagerHistoryMail object
     *
     * @param \TheliaMailManager\Model\MailManagerHistoryMail|ObjectCollection $mailManagerHistoryMail  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMailManagerTraceQuery The current query, for fluid interface
     */
    public function filterByMailManagerHistoryMail($mailManagerHistoryMail, $comparison = null)
    {
        if ($mailManagerHistoryMail instanceof \TheliaMailManager\Model\MailManagerHistoryMail) {
            return $this
                ->addUsingAlias(MailManagerTraceTableMap::ID, $mailManagerHistoryMail->getMailId(), $comparison);
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
     * @return ChildMailManagerTraceQuery The current query, for fluid interface
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
     * @param   ChildMailManagerTrace $mailManagerTrace Object to remove from the list of results
     *
     * @return ChildMailManagerTraceQuery The current query, for fluid interface
     */
    public function prune($mailManagerTrace = null)
    {
        if ($mailManagerTrace) {
            $this->addUsingAlias(MailManagerTraceTableMap::ID, $mailManagerTrace->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the mail_manager_trace table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MailManagerTraceTableMap::DATABASE_NAME);
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
            MailManagerTraceTableMap::clearInstancePool();
            MailManagerTraceTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildMailManagerTrace or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildMailManagerTrace object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(MailManagerTraceTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MailManagerTraceTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        MailManagerTraceTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MailManagerTraceTableMap::clearRelatedInstancePool();
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
     * @return     ChildMailManagerTraceQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(MailManagerTraceTableMap::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     ChildMailManagerTraceQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(MailManagerTraceTableMap::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     ChildMailManagerTraceQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(MailManagerTraceTableMap::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     ChildMailManagerTraceQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(MailManagerTraceTableMap::UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     ChildMailManagerTraceQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(MailManagerTraceTableMap::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     ChildMailManagerTraceQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(MailManagerTraceTableMap::CREATED_AT);
    }

} // MailManagerTraceQuery
