<?php

namespace TheliaMailManager\Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;
use TheliaMailManager\Model\MailManagerHistory as ChildMailManagerHistory;
use TheliaMailManager\Model\MailManagerHistoryMail as ChildMailManagerHistoryMail;
use TheliaMailManager\Model\MailManagerHistoryMailQuery as ChildMailManagerHistoryMailQuery;
use TheliaMailManager\Model\MailManagerHistoryQuery as ChildMailManagerHistoryQuery;
use TheliaMailManager\Model\MailManagerTrace as ChildMailManagerTrace;
use TheliaMailManager\Model\MailManagerTraceComment as ChildMailManagerTraceComment;
use TheliaMailManager\Model\MailManagerTraceCommentQuery as ChildMailManagerTraceCommentQuery;
use TheliaMailManager\Model\MailManagerTraceQuery as ChildMailManagerTraceQuery;
use TheliaMailManager\Model\Map\MailManagerTraceTableMap;

abstract class MailManagerTrace implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\TheliaMailManager\\Model\\Map\\MailManagerTraceTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the hash field.
     * @var        string
     */
    protected $hash;

    /**
     * The value for the disable_history field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $disable_history;

    /**
     * The value for the disable_sending field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $disable_sending;

    /**
     * The value for the force_same_customer_disable field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $force_same_customer_disable;

    /**
     * The value for the number_of_catch field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $number_of_catch;

    /**
     * The value for the email_bcc field.
     * @var        array
     */
    protected $email_bcc;

    /**
     * The unserialized $email_bcc value - i.e. the persisted object.
     * This is necessary to avoid repeated calls to unserialize() at runtime.
     * @var object
     */
    protected $email_bcc_unserialized;

    /**
     * The value for the email_redirect field.
     * @var        array
     */
    protected $email_redirect;

    /**
     * The unserialized $email_redirect value - i.e. the persisted object.
     * This is necessary to avoid repeated calls to unserialize() at runtime.
     * @var object
     */
    protected $email_redirect_unserialized;

    /**
     * The value for the detail field.
     * @var        string
     */
    protected $detail;

    /**
     * The value for the created_at field.
     * @var        string
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     * @var        string
     */
    protected $updated_at;

    /**
     * @var        ObjectCollection|ChildMailManagerTraceComment[] Collection to store aggregation of ChildMailManagerTraceComment objects.
     */
    protected $collMailManagerTraceComments;
    protected $collMailManagerTraceCommentsPartial;

    /**
     * @var        ObjectCollection|ChildMailManagerHistory[] Collection to store aggregation of ChildMailManagerHistory objects.
     */
    protected $collMailManagerHistories;
    protected $collMailManagerHistoriesPartial;

    /**
     * @var        ObjectCollection|ChildMailManagerHistoryMail[] Collection to store aggregation of ChildMailManagerHistoryMail objects.
     */
    protected $collMailManagerHistoryMails;
    protected $collMailManagerHistoryMailsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $mailManagerTraceCommentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $mailManagerHistoriesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $mailManagerHistoryMailsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->disable_history = false;
        $this->disable_sending = false;
        $this->force_same_customer_disable = false;
        $this->number_of_catch = 0;
    }

    /**
     * Initializes internal state of TheliaMailManager\Model\Base\MailManagerTrace object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (Boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (Boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>MailManagerTrace</code> instance.  If
     * <code>obj</code> is an instance of <code>MailManagerTrace</code>, delegates to
     * <code>equals(MailManagerTrace)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        $thisclazz = get_class($this);
        if (!is_object($obj) || !($obj instanceof $thisclazz)) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey()
            || null === $obj->getPrimaryKey())  {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        if (null !== $this->getPrimaryKey()) {
            return crc32(serialize($this->getPrimaryKey()));
        }

        return crc32(serialize(clone $this));
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return MailManagerTrace The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     *
     * @return MailManagerTrace The current object, for fluid interface
     */
    public function importFrom($parser, $data)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), TableMap::TYPE_PHPNAME);

        return $this;
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        return array_keys(get_object_vars($this));
    }

    /**
     * Get the [id] column value.
     *
     * @return   int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [hash] column value.
     *
     * @return   string
     */
    public function getHash()
    {

        return $this->hash;
    }

    /**
     * Get the [disable_history] column value.
     *
     * @return   boolean
     */
    public function getDisableHistory()
    {

        return $this->disable_history;
    }

    /**
     * Get the [disable_sending] column value.
     *
     * @return   boolean
     */
    public function getDisableSending()
    {

        return $this->disable_sending;
    }

    /**
     * Get the [force_same_customer_disable] column value.
     *
     * @return   boolean
     */
    public function getForceSameCustomerDisable()
    {

        return $this->force_same_customer_disable;
    }

    /**
     * Get the [number_of_catch] column value.
     *
     * @return   int
     */
    public function getNumberOfCatch()
    {

        return $this->number_of_catch;
    }

    /**
     * Get the [email_bcc] column value.
     *
     * @return   array
     */
    public function getEmailBcc()
    {
        if (null === $this->email_bcc_unserialized) {
            $this->email_bcc_unserialized = array();
        }
        if (!$this->email_bcc_unserialized && null !== $this->email_bcc) {
            $email_bcc_unserialized = substr($this->email_bcc, 2, -2);
            $this->email_bcc_unserialized = $email_bcc_unserialized ? explode(' | ', $email_bcc_unserialized) : array();
        }

        return $this->email_bcc_unserialized;
    }

    /**
     * Get the [email_redirect] column value.
     *
     * @return   array
     */
    public function getEmailRedirect()
    {
        if (null === $this->email_redirect_unserialized) {
            $this->email_redirect_unserialized = array();
        }
        if (!$this->email_redirect_unserialized && null !== $this->email_redirect) {
            $email_redirect_unserialized = substr($this->email_redirect, 2, -2);
            $this->email_redirect_unserialized = $email_redirect_unserialized ? explode(' | ', $email_redirect_unserialized) : array();
        }

        return $this->email_redirect_unserialized;
    }

    /**
     * Get the [detail] column value.
     *
     * @return   string
     */
    public function getDetail()
    {

        return $this->detail;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return mixed Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTime ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return mixed Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->updated_at;
        } else {
            return $this->updated_at instanceof \DateTime ? $this->updated_at->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param      int $v new value
     * @return   \TheliaMailManager\Model\MailManagerTrace The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[MailManagerTraceTableMap::ID] = true;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [hash] column.
     *
     * @param      string $v new value
     * @return   \TheliaMailManager\Model\MailManagerTrace The current object (for fluent API support)
     */
    public function setHash($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->hash !== $v) {
            $this->hash = $v;
            $this->modifiedColumns[MailManagerTraceTableMap::HASH] = true;
        }


        return $this;
    } // setHash()

    /**
     * Sets the value of the [disable_history] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param      boolean|integer|string $v The new value
     * @return   \TheliaMailManager\Model\MailManagerTrace The current object (for fluent API support)
     */
    public function setDisableHistory($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->disable_history !== $v) {
            $this->disable_history = $v;
            $this->modifiedColumns[MailManagerTraceTableMap::DISABLE_HISTORY] = true;
        }


        return $this;
    } // setDisableHistory()

    /**
     * Sets the value of the [disable_sending] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param      boolean|integer|string $v The new value
     * @return   \TheliaMailManager\Model\MailManagerTrace The current object (for fluent API support)
     */
    public function setDisableSending($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->disable_sending !== $v) {
            $this->disable_sending = $v;
            $this->modifiedColumns[MailManagerTraceTableMap::DISABLE_SENDING] = true;
        }


        return $this;
    } // setDisableSending()

    /**
     * Sets the value of the [force_same_customer_disable] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param      boolean|integer|string $v The new value
     * @return   \TheliaMailManager\Model\MailManagerTrace The current object (for fluent API support)
     */
    public function setForceSameCustomerDisable($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->force_same_customer_disable !== $v) {
            $this->force_same_customer_disable = $v;
            $this->modifiedColumns[MailManagerTraceTableMap::FORCE_SAME_CUSTOMER_DISABLE] = true;
        }


        return $this;
    } // setForceSameCustomerDisable()

    /**
     * Set the value of [number_of_catch] column.
     *
     * @param      int $v new value
     * @return   \TheliaMailManager\Model\MailManagerTrace The current object (for fluent API support)
     */
    public function setNumberOfCatch($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->number_of_catch !== $v) {
            $this->number_of_catch = $v;
            $this->modifiedColumns[MailManagerTraceTableMap::NUMBER_OF_CATCH] = true;
        }


        return $this;
    } // setNumberOfCatch()

    /**
     * Set the value of [email_bcc] column.
     *
     * @param      array $v new value
     * @return   \TheliaMailManager\Model\MailManagerTrace The current object (for fluent API support)
     */
    public function setEmailBcc($v)
    {
        if ($this->email_bcc_unserialized !== $v) {
            $this->email_bcc_unserialized = $v;
            $this->email_bcc = '| ' . implode(' | ', $v) . ' |';
            $this->modifiedColumns[MailManagerTraceTableMap::EMAIL_BCC] = true;
        }


        return $this;
    } // setEmailBcc()

    /**
     * Set the value of [email_redirect] column.
     *
     * @param      array $v new value
     * @return   \TheliaMailManager\Model\MailManagerTrace The current object (for fluent API support)
     */
    public function setEmailRedirect($v)
    {
        if ($this->email_redirect_unserialized !== $v) {
            $this->email_redirect_unserialized = $v;
            $this->email_redirect = '| ' . implode(' | ', $v) . ' |';
            $this->modifiedColumns[MailManagerTraceTableMap::EMAIL_REDIRECT] = true;
        }


        return $this;
    } // setEmailRedirect()

    /**
     * Set the value of [detail] column.
     *
     * @param      string $v new value
     * @return   \TheliaMailManager\Model\MailManagerTrace The current object (for fluent API support)
     */
    public function setDetail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->detail !== $v) {
            $this->detail = $v;
            $this->modifiedColumns[MailManagerTraceTableMap::DETAIL] = true;
        }


        return $this;
    } // setDetail()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param      mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return   \TheliaMailManager\Model\MailManagerTrace The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($dt !== $this->created_at) {
                $this->created_at = $dt;
                $this->modifiedColumns[MailManagerTraceTableMap::CREATED_AT] = true;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param      mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return   \TheliaMailManager\Model\MailManagerTrace The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($dt !== $this->updated_at) {
                $this->updated_at = $dt;
                $this->modifiedColumns[MailManagerTraceTableMap::UPDATED_AT] = true;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->disable_history !== false) {
                return false;
            }

            if ($this->disable_sending !== false) {
                return false;
            }

            if ($this->force_same_customer_disable !== false) {
                return false;
            }

            if ($this->number_of_catch !== 0) {
                return false;
            }

        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {


            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : MailManagerTraceTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : MailManagerTraceTableMap::translateFieldName('Hash', TableMap::TYPE_PHPNAME, $indexType)];
            $this->hash = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : MailManagerTraceTableMap::translateFieldName('DisableHistory', TableMap::TYPE_PHPNAME, $indexType)];
            $this->disable_history = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : MailManagerTraceTableMap::translateFieldName('DisableSending', TableMap::TYPE_PHPNAME, $indexType)];
            $this->disable_sending = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : MailManagerTraceTableMap::translateFieldName('ForceSameCustomerDisable', TableMap::TYPE_PHPNAME, $indexType)];
            $this->force_same_customer_disable = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : MailManagerTraceTableMap::translateFieldName('NumberOfCatch', TableMap::TYPE_PHPNAME, $indexType)];
            $this->number_of_catch = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : MailManagerTraceTableMap::translateFieldName('EmailBcc', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email_bcc = $col;
            $this->email_bcc_unserialized = null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : MailManagerTraceTableMap::translateFieldName('EmailRedirect', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email_redirect = $col;
            $this->email_redirect_unserialized = null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : MailManagerTraceTableMap::translateFieldName('Detail', TableMap::TYPE_PHPNAME, $indexType)];
            $this->detail = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : MailManagerTraceTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : MailManagerTraceTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 11; // 11 = MailManagerTraceTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating \TheliaMailManager\Model\MailManagerTrace object", 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MailManagerTraceTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildMailManagerTraceQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collMailManagerTraceComments = null;

            $this->collMailManagerHistories = null;

            $this->collMailManagerHistoryMails = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see MailManagerTrace::setDeleted()
     * @see MailManagerTrace::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(MailManagerTraceTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ChildMailManagerTraceQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(MailManagerTraceTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(MailManagerTraceTableMap::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(MailManagerTraceTableMap::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(MailManagerTraceTableMap::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                MailManagerTraceTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->mailManagerTraceCommentsScheduledForDeletion !== null) {
                if (!$this->mailManagerTraceCommentsScheduledForDeletion->isEmpty()) {
                    \TheliaMailManager\Model\MailManagerTraceCommentQuery::create()
                        ->filterByPrimaryKeys($this->mailManagerTraceCommentsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->mailManagerTraceCommentsScheduledForDeletion = null;
                }
            }

                if ($this->collMailManagerTraceComments !== null) {
            foreach ($this->collMailManagerTraceComments as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->mailManagerHistoriesScheduledForDeletion !== null) {
                if (!$this->mailManagerHistoriesScheduledForDeletion->isEmpty()) {
                    \TheliaMailManager\Model\MailManagerHistoryQuery::create()
                        ->filterByPrimaryKeys($this->mailManagerHistoriesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->mailManagerHistoriesScheduledForDeletion = null;
                }
            }

                if ($this->collMailManagerHistories !== null) {
            foreach ($this->collMailManagerHistories as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->mailManagerHistoryMailsScheduledForDeletion !== null) {
                if (!$this->mailManagerHistoryMailsScheduledForDeletion->isEmpty()) {
                    \TheliaMailManager\Model\MailManagerHistoryMailQuery::create()
                        ->filterByPrimaryKeys($this->mailManagerHistoryMailsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->mailManagerHistoryMailsScheduledForDeletion = null;
                }
            }

                if ($this->collMailManagerHistoryMails !== null) {
            foreach ($this->collMailManagerHistoryMails as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[MailManagerTraceTableMap::ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . MailManagerTraceTableMap::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(MailManagerTraceTableMap::ID)) {
            $modifiedColumns[':p' . $index++]  = 'ID';
        }
        if ($this->isColumnModified(MailManagerTraceTableMap::HASH)) {
            $modifiedColumns[':p' . $index++]  = 'HASH';
        }
        if ($this->isColumnModified(MailManagerTraceTableMap::DISABLE_HISTORY)) {
            $modifiedColumns[':p' . $index++]  = 'DISABLE_HISTORY';
        }
        if ($this->isColumnModified(MailManagerTraceTableMap::DISABLE_SENDING)) {
            $modifiedColumns[':p' . $index++]  = 'DISABLE_SENDING';
        }
        if ($this->isColumnModified(MailManagerTraceTableMap::FORCE_SAME_CUSTOMER_DISABLE)) {
            $modifiedColumns[':p' . $index++]  = 'FORCE_SAME_CUSTOMER_DISABLE';
        }
        if ($this->isColumnModified(MailManagerTraceTableMap::NUMBER_OF_CATCH)) {
            $modifiedColumns[':p' . $index++]  = 'NUMBER_OF_CATCH';
        }
        if ($this->isColumnModified(MailManagerTraceTableMap::EMAIL_BCC)) {
            $modifiedColumns[':p' . $index++]  = 'EMAIL_BCC';
        }
        if ($this->isColumnModified(MailManagerTraceTableMap::EMAIL_REDIRECT)) {
            $modifiedColumns[':p' . $index++]  = 'EMAIL_REDIRECT';
        }
        if ($this->isColumnModified(MailManagerTraceTableMap::DETAIL)) {
            $modifiedColumns[':p' . $index++]  = 'DETAIL';
        }
        if ($this->isColumnModified(MailManagerTraceTableMap::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'CREATED_AT';
        }
        if ($this->isColumnModified(MailManagerTraceTableMap::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'UPDATED_AT';
        }

        $sql = sprintf(
            'INSERT INTO mail_manager_trace (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'ID':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'HASH':
                        $stmt->bindValue($identifier, $this->hash, PDO::PARAM_STR);
                        break;
                    case 'DISABLE_HISTORY':
                        $stmt->bindValue($identifier, (int) $this->disable_history, PDO::PARAM_INT);
                        break;
                    case 'DISABLE_SENDING':
                        $stmt->bindValue($identifier, (int) $this->disable_sending, PDO::PARAM_INT);
                        break;
                    case 'FORCE_SAME_CUSTOMER_DISABLE':
                        $stmt->bindValue($identifier, (int) $this->force_same_customer_disable, PDO::PARAM_INT);
                        break;
                    case 'NUMBER_OF_CATCH':
                        $stmt->bindValue($identifier, $this->number_of_catch, PDO::PARAM_INT);
                        break;
                    case 'EMAIL_BCC':
                        $stmt->bindValue($identifier, $this->email_bcc, PDO::PARAM_STR);
                        break;
                    case 'EMAIL_REDIRECT':
                        $stmt->bindValue($identifier, $this->email_redirect, PDO::PARAM_STR);
                        break;
                    case 'DETAIL':
                        $stmt->bindValue($identifier, $this->detail, PDO::PARAM_STR);
                        break;
                    case 'CREATED_AT':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'UPDATED_AT':
                        $stmt->bindValue($identifier, $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = MailManagerTraceTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getHash();
                break;
            case 2:
                return $this->getDisableHistory();
                break;
            case 3:
                return $this->getDisableSending();
                break;
            case 4:
                return $this->getForceSameCustomerDisable();
                break;
            case 5:
                return $this->getNumberOfCatch();
                break;
            case 6:
                return $this->getEmailBcc();
                break;
            case 7:
                return $this->getEmailRedirect();
                break;
            case 8:
                return $this->getDetail();
                break;
            case 9:
                return $this->getCreatedAt();
                break;
            case 10:
                return $this->getUpdatedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['MailManagerTrace'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['MailManagerTrace'][$this->getPrimaryKey()] = true;
        $keys = MailManagerTraceTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getHash(),
            $keys[2] => $this->getDisableHistory(),
            $keys[3] => $this->getDisableSending(),
            $keys[4] => $this->getForceSameCustomerDisable(),
            $keys[5] => $this->getNumberOfCatch(),
            $keys[6] => $this->getEmailBcc(),
            $keys[7] => $this->getEmailRedirect(),
            $keys[8] => $this->getDetail(),
            $keys[9] => $this->getCreatedAt(),
            $keys[10] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collMailManagerTraceComments) {
                $result['MailManagerTraceComments'] = $this->collMailManagerTraceComments->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMailManagerHistories) {
                $result['MailManagerHistories'] = $this->collMailManagerHistories->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMailManagerHistoryMails) {
                $result['MailManagerHistoryMails'] = $this->collMailManagerHistoryMails->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param      string $name
     * @param      mixed  $value field value
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return void
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = MailManagerTraceTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @param      mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setHash($value);
                break;
            case 2:
                $this->setDisableHistory($value);
                break;
            case 3:
                $this->setDisableSending($value);
                break;
            case 4:
                $this->setForceSameCustomerDisable($value);
                break;
            case 5:
                $this->setNumberOfCatch($value);
                break;
            case 6:
                if (!is_array($value)) {
                    $v = trim(substr($value, 2, -2));
                    $value = $v ? explode(' | ', $v) : array();
                }
                $this->setEmailBcc($value);
                break;
            case 7:
                if (!is_array($value)) {
                    $v = trim(substr($value, 2, -2));
                    $value = $v ? explode(' | ', $v) : array();
                }
                $this->setEmailRedirect($value);
                break;
            case 8:
                $this->setDetail($value);
                break;
            case 9:
                $this->setCreatedAt($value);
                break;
            case 10:
                $this->setUpdatedAt($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = MailManagerTraceTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setHash($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setDisableHistory($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setDisableSending($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setForceSameCustomerDisable($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setNumberOfCatch($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setEmailBcc($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setEmailRedirect($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setDetail($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setCreatedAt($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setUpdatedAt($arr[$keys[10]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(MailManagerTraceTableMap::DATABASE_NAME);

        if ($this->isColumnModified(MailManagerTraceTableMap::ID)) $criteria->add(MailManagerTraceTableMap::ID, $this->id);
        if ($this->isColumnModified(MailManagerTraceTableMap::HASH)) $criteria->add(MailManagerTraceTableMap::HASH, $this->hash);
        if ($this->isColumnModified(MailManagerTraceTableMap::DISABLE_HISTORY)) $criteria->add(MailManagerTraceTableMap::DISABLE_HISTORY, $this->disable_history);
        if ($this->isColumnModified(MailManagerTraceTableMap::DISABLE_SENDING)) $criteria->add(MailManagerTraceTableMap::DISABLE_SENDING, $this->disable_sending);
        if ($this->isColumnModified(MailManagerTraceTableMap::FORCE_SAME_CUSTOMER_DISABLE)) $criteria->add(MailManagerTraceTableMap::FORCE_SAME_CUSTOMER_DISABLE, $this->force_same_customer_disable);
        if ($this->isColumnModified(MailManagerTraceTableMap::NUMBER_OF_CATCH)) $criteria->add(MailManagerTraceTableMap::NUMBER_OF_CATCH, $this->number_of_catch);
        if ($this->isColumnModified(MailManagerTraceTableMap::EMAIL_BCC)) $criteria->add(MailManagerTraceTableMap::EMAIL_BCC, $this->email_bcc);
        if ($this->isColumnModified(MailManagerTraceTableMap::EMAIL_REDIRECT)) $criteria->add(MailManagerTraceTableMap::EMAIL_REDIRECT, $this->email_redirect);
        if ($this->isColumnModified(MailManagerTraceTableMap::DETAIL)) $criteria->add(MailManagerTraceTableMap::DETAIL, $this->detail);
        if ($this->isColumnModified(MailManagerTraceTableMap::CREATED_AT)) $criteria->add(MailManagerTraceTableMap::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(MailManagerTraceTableMap::UPDATED_AT)) $criteria->add(MailManagerTraceTableMap::UPDATED_AT, $this->updated_at);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(MailManagerTraceTableMap::DATABASE_NAME);
        $criteria->add(MailManagerTraceTableMap::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return   int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \TheliaMailManager\Model\MailManagerTrace (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setHash($this->getHash());
        $copyObj->setDisableHistory($this->getDisableHistory());
        $copyObj->setDisableSending($this->getDisableSending());
        $copyObj->setForceSameCustomerDisable($this->getForceSameCustomerDisable());
        $copyObj->setNumberOfCatch($this->getNumberOfCatch());
        $copyObj->setEmailBcc($this->getEmailBcc());
        $copyObj->setEmailRedirect($this->getEmailRedirect());
        $copyObj->setDetail($this->getDetail());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getMailManagerTraceComments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMailManagerTraceComment($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMailManagerHistories() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMailManagerHistory($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMailManagerHistoryMails() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMailManagerHistoryMail($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return                 \TheliaMailManager\Model\MailManagerTrace Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('MailManagerTraceComment' == $relationName) {
            return $this->initMailManagerTraceComments();
        }
        if ('MailManagerHistory' == $relationName) {
            return $this->initMailManagerHistories();
        }
        if ('MailManagerHistoryMail' == $relationName) {
            return $this->initMailManagerHistoryMails();
        }
    }

    /**
     * Clears out the collMailManagerTraceComments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMailManagerTraceComments()
     */
    public function clearMailManagerTraceComments()
    {
        $this->collMailManagerTraceComments = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMailManagerTraceComments collection loaded partially.
     */
    public function resetPartialMailManagerTraceComments($v = true)
    {
        $this->collMailManagerTraceCommentsPartial = $v;
    }

    /**
     * Initializes the collMailManagerTraceComments collection.
     *
     * By default this just sets the collMailManagerTraceComments collection to an empty array (like clearcollMailManagerTraceComments());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMailManagerTraceComments($overrideExisting = true)
    {
        if (null !== $this->collMailManagerTraceComments && !$overrideExisting) {
            return;
        }
        $this->collMailManagerTraceComments = new ObjectCollection();
        $this->collMailManagerTraceComments->setModel('\TheliaMailManager\Model\MailManagerTraceComment');
    }

    /**
     * Gets an array of ChildMailManagerTraceComment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMailManagerTrace is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildMailManagerTraceComment[] List of ChildMailManagerTraceComment objects
     * @throws PropelException
     */
    public function getMailManagerTraceComments($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMailManagerTraceCommentsPartial && !$this->isNew();
        if (null === $this->collMailManagerTraceComments || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMailManagerTraceComments) {
                // return empty collection
                $this->initMailManagerTraceComments();
            } else {
                $collMailManagerTraceComments = ChildMailManagerTraceCommentQuery::create(null, $criteria)
                    ->filterByMailManagerTrace($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMailManagerTraceCommentsPartial && count($collMailManagerTraceComments)) {
                        $this->initMailManagerTraceComments(false);

                        foreach ($collMailManagerTraceComments as $obj) {
                            if (false == $this->collMailManagerTraceComments->contains($obj)) {
                                $this->collMailManagerTraceComments->append($obj);
                            }
                        }

                        $this->collMailManagerTraceCommentsPartial = true;
                    }

                    reset($collMailManagerTraceComments);

                    return $collMailManagerTraceComments;
                }

                if ($partial && $this->collMailManagerTraceComments) {
                    foreach ($this->collMailManagerTraceComments as $obj) {
                        if ($obj->isNew()) {
                            $collMailManagerTraceComments[] = $obj;
                        }
                    }
                }

                $this->collMailManagerTraceComments = $collMailManagerTraceComments;
                $this->collMailManagerTraceCommentsPartial = false;
            }
        }

        return $this->collMailManagerTraceComments;
    }

    /**
     * Sets a collection of MailManagerTraceComment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $mailManagerTraceComments A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildMailManagerTrace The current object (for fluent API support)
     */
    public function setMailManagerTraceComments(Collection $mailManagerTraceComments, ConnectionInterface $con = null)
    {
        $mailManagerTraceCommentsToDelete = $this->getMailManagerTraceComments(new Criteria(), $con)->diff($mailManagerTraceComments);


        $this->mailManagerTraceCommentsScheduledForDeletion = $mailManagerTraceCommentsToDelete;

        foreach ($mailManagerTraceCommentsToDelete as $mailManagerTraceCommentRemoved) {
            $mailManagerTraceCommentRemoved->setMailManagerTrace(null);
        }

        $this->collMailManagerTraceComments = null;
        foreach ($mailManagerTraceComments as $mailManagerTraceComment) {
            $this->addMailManagerTraceComment($mailManagerTraceComment);
        }

        $this->collMailManagerTraceComments = $mailManagerTraceComments;
        $this->collMailManagerTraceCommentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related MailManagerTraceComment objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related MailManagerTraceComment objects.
     * @throws PropelException
     */
    public function countMailManagerTraceComments(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMailManagerTraceCommentsPartial && !$this->isNew();
        if (null === $this->collMailManagerTraceComments || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMailManagerTraceComments) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMailManagerTraceComments());
            }

            $query = ChildMailManagerTraceCommentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMailManagerTrace($this)
                ->count($con);
        }

        return count($this->collMailManagerTraceComments);
    }

    /**
     * Method called to associate a ChildMailManagerTraceComment object to this object
     * through the ChildMailManagerTraceComment foreign key attribute.
     *
     * @param    ChildMailManagerTraceComment $l ChildMailManagerTraceComment
     * @return   \TheliaMailManager\Model\MailManagerTrace The current object (for fluent API support)
     */
    public function addMailManagerTraceComment(ChildMailManagerTraceComment $l)
    {
        if ($this->collMailManagerTraceComments === null) {
            $this->initMailManagerTraceComments();
            $this->collMailManagerTraceCommentsPartial = true;
        }

        if (!in_array($l, $this->collMailManagerTraceComments->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddMailManagerTraceComment($l);
        }

        return $this;
    }

    /**
     * @param MailManagerTraceComment $mailManagerTraceComment The mailManagerTraceComment object to add.
     */
    protected function doAddMailManagerTraceComment($mailManagerTraceComment)
    {
        $this->collMailManagerTraceComments[]= $mailManagerTraceComment;
        $mailManagerTraceComment->setMailManagerTrace($this);
    }

    /**
     * @param  MailManagerTraceComment $mailManagerTraceComment The mailManagerTraceComment object to remove.
     * @return ChildMailManagerTrace The current object (for fluent API support)
     */
    public function removeMailManagerTraceComment($mailManagerTraceComment)
    {
        if ($this->getMailManagerTraceComments()->contains($mailManagerTraceComment)) {
            $this->collMailManagerTraceComments->remove($this->collMailManagerTraceComments->search($mailManagerTraceComment));
            if (null === $this->mailManagerTraceCommentsScheduledForDeletion) {
                $this->mailManagerTraceCommentsScheduledForDeletion = clone $this->collMailManagerTraceComments;
                $this->mailManagerTraceCommentsScheduledForDeletion->clear();
            }
            $this->mailManagerTraceCommentsScheduledForDeletion[]= clone $mailManagerTraceComment;
            $mailManagerTraceComment->setMailManagerTrace(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this MailManagerTrace is new, it will return
     * an empty collection; or if this MailManagerTrace has previously
     * been saved, it will retrieve related MailManagerTraceComments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MailManagerTrace.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildMailManagerTraceComment[] List of ChildMailManagerTraceComment objects
     */
    public function getMailManagerTraceCommentsJoinAdmin($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildMailManagerTraceCommentQuery::create(null, $criteria);
        $query->joinWith('Admin', $joinBehavior);

        return $this->getMailManagerTraceComments($query, $con);
    }

    /**
     * Clears out the collMailManagerHistories collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMailManagerHistories()
     */
    public function clearMailManagerHistories()
    {
        $this->collMailManagerHistories = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMailManagerHistories collection loaded partially.
     */
    public function resetPartialMailManagerHistories($v = true)
    {
        $this->collMailManagerHistoriesPartial = $v;
    }

    /**
     * Initializes the collMailManagerHistories collection.
     *
     * By default this just sets the collMailManagerHistories collection to an empty array (like clearcollMailManagerHistories());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMailManagerHistories($overrideExisting = true)
    {
        if (null !== $this->collMailManagerHistories && !$overrideExisting) {
            return;
        }
        $this->collMailManagerHistories = new ObjectCollection();
        $this->collMailManagerHistories->setModel('\TheliaMailManager\Model\MailManagerHistory');
    }

    /**
     * Gets an array of ChildMailManagerHistory objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMailManagerTrace is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildMailManagerHistory[] List of ChildMailManagerHistory objects
     * @throws PropelException
     */
    public function getMailManagerHistories($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMailManagerHistoriesPartial && !$this->isNew();
        if (null === $this->collMailManagerHistories || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMailManagerHistories) {
                // return empty collection
                $this->initMailManagerHistories();
            } else {
                $collMailManagerHistories = ChildMailManagerHistoryQuery::create(null, $criteria)
                    ->filterByMailManagerTrace($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMailManagerHistoriesPartial && count($collMailManagerHistories)) {
                        $this->initMailManagerHistories(false);

                        foreach ($collMailManagerHistories as $obj) {
                            if (false == $this->collMailManagerHistories->contains($obj)) {
                                $this->collMailManagerHistories->append($obj);
                            }
                        }

                        $this->collMailManagerHistoriesPartial = true;
                    }

                    reset($collMailManagerHistories);

                    return $collMailManagerHistories;
                }

                if ($partial && $this->collMailManagerHistories) {
                    foreach ($this->collMailManagerHistories as $obj) {
                        if ($obj->isNew()) {
                            $collMailManagerHistories[] = $obj;
                        }
                    }
                }

                $this->collMailManagerHistories = $collMailManagerHistories;
                $this->collMailManagerHistoriesPartial = false;
            }
        }

        return $this->collMailManagerHistories;
    }

    /**
     * Sets a collection of MailManagerHistory objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $mailManagerHistories A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildMailManagerTrace The current object (for fluent API support)
     */
    public function setMailManagerHistories(Collection $mailManagerHistories, ConnectionInterface $con = null)
    {
        $mailManagerHistoriesToDelete = $this->getMailManagerHistories(new Criteria(), $con)->diff($mailManagerHistories);


        $this->mailManagerHistoriesScheduledForDeletion = $mailManagerHistoriesToDelete;

        foreach ($mailManagerHistoriesToDelete as $mailManagerHistoryRemoved) {
            $mailManagerHistoryRemoved->setMailManagerTrace(null);
        }

        $this->collMailManagerHistories = null;
        foreach ($mailManagerHistories as $mailManagerHistory) {
            $this->addMailManagerHistory($mailManagerHistory);
        }

        $this->collMailManagerHistories = $mailManagerHistories;
        $this->collMailManagerHistoriesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related MailManagerHistory objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related MailManagerHistory objects.
     * @throws PropelException
     */
    public function countMailManagerHistories(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMailManagerHistoriesPartial && !$this->isNew();
        if (null === $this->collMailManagerHistories || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMailManagerHistories) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMailManagerHistories());
            }

            $query = ChildMailManagerHistoryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMailManagerTrace($this)
                ->count($con);
        }

        return count($this->collMailManagerHistories);
    }

    /**
     * Method called to associate a ChildMailManagerHistory object to this object
     * through the ChildMailManagerHistory foreign key attribute.
     *
     * @param    ChildMailManagerHistory $l ChildMailManagerHistory
     * @return   \TheliaMailManager\Model\MailManagerTrace The current object (for fluent API support)
     */
    public function addMailManagerHistory(ChildMailManagerHistory $l)
    {
        if ($this->collMailManagerHistories === null) {
            $this->initMailManagerHistories();
            $this->collMailManagerHistoriesPartial = true;
        }

        if (!in_array($l, $this->collMailManagerHistories->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddMailManagerHistory($l);
        }

        return $this;
    }

    /**
     * @param MailManagerHistory $mailManagerHistory The mailManagerHistory object to add.
     */
    protected function doAddMailManagerHistory($mailManagerHistory)
    {
        $this->collMailManagerHistories[]= $mailManagerHistory;
        $mailManagerHistory->setMailManagerTrace($this);
    }

    /**
     * @param  MailManagerHistory $mailManagerHistory The mailManagerHistory object to remove.
     * @return ChildMailManagerTrace The current object (for fluent API support)
     */
    public function removeMailManagerHistory($mailManagerHistory)
    {
        if ($this->getMailManagerHistories()->contains($mailManagerHistory)) {
            $this->collMailManagerHistories->remove($this->collMailManagerHistories->search($mailManagerHistory));
            if (null === $this->mailManagerHistoriesScheduledForDeletion) {
                $this->mailManagerHistoriesScheduledForDeletion = clone $this->collMailManagerHistories;
                $this->mailManagerHistoriesScheduledForDeletion->clear();
            }
            $this->mailManagerHistoriesScheduledForDeletion[]= clone $mailManagerHistory;
            $mailManagerHistory->setMailManagerTrace(null);
        }

        return $this;
    }

    /**
     * Clears out the collMailManagerHistoryMails collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMailManagerHistoryMails()
     */
    public function clearMailManagerHistoryMails()
    {
        $this->collMailManagerHistoryMails = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMailManagerHistoryMails collection loaded partially.
     */
    public function resetPartialMailManagerHistoryMails($v = true)
    {
        $this->collMailManagerHistoryMailsPartial = $v;
    }

    /**
     * Initializes the collMailManagerHistoryMails collection.
     *
     * By default this just sets the collMailManagerHistoryMails collection to an empty array (like clearcollMailManagerHistoryMails());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMailManagerHistoryMails($overrideExisting = true)
    {
        if (null !== $this->collMailManagerHistoryMails && !$overrideExisting) {
            return;
        }
        $this->collMailManagerHistoryMails = new ObjectCollection();
        $this->collMailManagerHistoryMails->setModel('\TheliaMailManager\Model\MailManagerHistoryMail');
    }

    /**
     * Gets an array of ChildMailManagerHistoryMail objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMailManagerTrace is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildMailManagerHistoryMail[] List of ChildMailManagerHistoryMail objects
     * @throws PropelException
     */
    public function getMailManagerHistoryMails($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMailManagerHistoryMailsPartial && !$this->isNew();
        if (null === $this->collMailManagerHistoryMails || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMailManagerHistoryMails) {
                // return empty collection
                $this->initMailManagerHistoryMails();
            } else {
                $collMailManagerHistoryMails = ChildMailManagerHistoryMailQuery::create(null, $criteria)
                    ->filterByMailManagerTrace($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMailManagerHistoryMailsPartial && count($collMailManagerHistoryMails)) {
                        $this->initMailManagerHistoryMails(false);

                        foreach ($collMailManagerHistoryMails as $obj) {
                            if (false == $this->collMailManagerHistoryMails->contains($obj)) {
                                $this->collMailManagerHistoryMails->append($obj);
                            }
                        }

                        $this->collMailManagerHistoryMailsPartial = true;
                    }

                    reset($collMailManagerHistoryMails);

                    return $collMailManagerHistoryMails;
                }

                if ($partial && $this->collMailManagerHistoryMails) {
                    foreach ($this->collMailManagerHistoryMails as $obj) {
                        if ($obj->isNew()) {
                            $collMailManagerHistoryMails[] = $obj;
                        }
                    }
                }

                $this->collMailManagerHistoryMails = $collMailManagerHistoryMails;
                $this->collMailManagerHistoryMailsPartial = false;
            }
        }

        return $this->collMailManagerHistoryMails;
    }

    /**
     * Sets a collection of MailManagerHistoryMail objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $mailManagerHistoryMails A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildMailManagerTrace The current object (for fluent API support)
     */
    public function setMailManagerHistoryMails(Collection $mailManagerHistoryMails, ConnectionInterface $con = null)
    {
        $mailManagerHistoryMailsToDelete = $this->getMailManagerHistoryMails(new Criteria(), $con)->diff($mailManagerHistoryMails);


        $this->mailManagerHistoryMailsScheduledForDeletion = $mailManagerHistoryMailsToDelete;

        foreach ($mailManagerHistoryMailsToDelete as $mailManagerHistoryMailRemoved) {
            $mailManagerHistoryMailRemoved->setMailManagerTrace(null);
        }

        $this->collMailManagerHistoryMails = null;
        foreach ($mailManagerHistoryMails as $mailManagerHistoryMail) {
            $this->addMailManagerHistoryMail($mailManagerHistoryMail);
        }

        $this->collMailManagerHistoryMails = $mailManagerHistoryMails;
        $this->collMailManagerHistoryMailsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related MailManagerHistoryMail objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related MailManagerHistoryMail objects.
     * @throws PropelException
     */
    public function countMailManagerHistoryMails(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMailManagerHistoryMailsPartial && !$this->isNew();
        if (null === $this->collMailManagerHistoryMails || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMailManagerHistoryMails) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMailManagerHistoryMails());
            }

            $query = ChildMailManagerHistoryMailQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMailManagerTrace($this)
                ->count($con);
        }

        return count($this->collMailManagerHistoryMails);
    }

    /**
     * Method called to associate a ChildMailManagerHistoryMail object to this object
     * through the ChildMailManagerHistoryMail foreign key attribute.
     *
     * @param    ChildMailManagerHistoryMail $l ChildMailManagerHistoryMail
     * @return   \TheliaMailManager\Model\MailManagerTrace The current object (for fluent API support)
     */
    public function addMailManagerHistoryMail(ChildMailManagerHistoryMail $l)
    {
        if ($this->collMailManagerHistoryMails === null) {
            $this->initMailManagerHistoryMails();
            $this->collMailManagerHistoryMailsPartial = true;
        }

        if (!in_array($l, $this->collMailManagerHistoryMails->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddMailManagerHistoryMail($l);
        }

        return $this;
    }

    /**
     * @param MailManagerHistoryMail $mailManagerHistoryMail The mailManagerHistoryMail object to add.
     */
    protected function doAddMailManagerHistoryMail($mailManagerHistoryMail)
    {
        $this->collMailManagerHistoryMails[]= $mailManagerHistoryMail;
        $mailManagerHistoryMail->setMailManagerTrace($this);
    }

    /**
     * @param  MailManagerHistoryMail $mailManagerHistoryMail The mailManagerHistoryMail object to remove.
     * @return ChildMailManagerTrace The current object (for fluent API support)
     */
    public function removeMailManagerHistoryMail($mailManagerHistoryMail)
    {
        if ($this->getMailManagerHistoryMails()->contains($mailManagerHistoryMail)) {
            $this->collMailManagerHistoryMails->remove($this->collMailManagerHistoryMails->search($mailManagerHistoryMail));
            if (null === $this->mailManagerHistoryMailsScheduledForDeletion) {
                $this->mailManagerHistoryMailsScheduledForDeletion = clone $this->collMailManagerHistoryMails;
                $this->mailManagerHistoryMailsScheduledForDeletion->clear();
            }
            $this->mailManagerHistoryMailsScheduledForDeletion[]= clone $mailManagerHistoryMail;
            $mailManagerHistoryMail->setMailManagerTrace(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this MailManagerTrace is new, it will return
     * an empty collection; or if this MailManagerTrace has previously
     * been saved, it will retrieve related MailManagerHistoryMails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MailManagerTrace.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildMailManagerHistoryMail[] List of ChildMailManagerHistoryMail objects
     */
    public function getMailManagerHistoryMailsJoinMailManagerHistory($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildMailManagerHistoryMailQuery::create(null, $criteria);
        $query->joinWith('MailManagerHistory', $joinBehavior);

        return $this->getMailManagerHistoryMails($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->hash = null;
        $this->disable_history = null;
        $this->disable_sending = null;
        $this->force_same_customer_disable = null;
        $this->number_of_catch = null;
        $this->email_bcc = null;
        $this->email_bcc_unserialized = null;
        $this->email_redirect = null;
        $this->email_redirect_unserialized = null;
        $this->detail = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collMailManagerTraceComments) {
                foreach ($this->collMailManagerTraceComments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMailManagerHistories) {
                foreach ($this->collMailManagerHistories as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMailManagerHistoryMails) {
                foreach ($this->collMailManagerHistoryMails as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collMailManagerTraceComments = null;
        $this->collMailManagerHistories = null;
        $this->collMailManagerHistoryMails = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(MailManagerTraceTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     ChildMailManagerTrace The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[MailManagerTraceTableMap::UPDATED_AT] = true;

        return $this;
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
