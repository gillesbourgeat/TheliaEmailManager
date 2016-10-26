<?php

namespace TheliaEmailManager\Model\Base;

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
use TheliaEmailManager\Model\EmailManagerHistory as ChildEmailManagerHistory;
use TheliaEmailManager\Model\EmailManagerHistoryQuery as ChildEmailManagerHistoryQuery;
use TheliaEmailManager\Model\EmailManagerTrace as ChildEmailManagerTrace;
use TheliaEmailManager\Model\EmailManagerTraceI18n as ChildEmailManagerTraceI18n;
use TheliaEmailManager\Model\EmailManagerTraceI18nQuery as ChildEmailManagerTraceI18nQuery;
use TheliaEmailManager\Model\EmailManagerTraceQuery as ChildEmailManagerTraceQuery;
use TheliaEmailManager\Model\Map\EmailManagerTraceTableMap;

abstract class EmailManagerTrace implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\TheliaEmailManager\\Model\\Map\\EmailManagerTraceTableMap';


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
     * The value for the parent_id field.
     * @var        int
     */
    protected $parent_id;

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
     * @var        EmailManagerTrace
     */
    protected $aEmailManagerTraceRelatedByParentId;

    /**
     * @var        ObjectCollection|ChildEmailManagerTrace[] Collection to store aggregation of ChildEmailManagerTrace objects.
     */
    protected $collEmailManagerTracesRelatedById;
    protected $collEmailManagerTracesRelatedByIdPartial;

    /**
     * @var        ObjectCollection|ChildEmailManagerHistory[] Collection to store aggregation of ChildEmailManagerHistory objects.
     */
    protected $collEmailManagerHistories;
    protected $collEmailManagerHistoriesPartial;

    /**
     * @var        ObjectCollection|ChildEmailManagerTraceI18n[] Collection to store aggregation of ChildEmailManagerTraceI18n objects.
     */
    protected $collEmailManagerTraceI18ns;
    protected $collEmailManagerTraceI18nsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    // i18n behavior

    /**
     * Current locale
     * @var        string
     */
    protected $currentLocale = 'en_US';

    /**
     * Current translation objects
     * @var        array[ChildEmailManagerTraceI18n]
     */
    protected $currentTranslations;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $emailManagerTracesRelatedByIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $emailManagerHistoriesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $emailManagerTraceI18nsScheduledForDeletion = null;

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
     * Initializes internal state of TheliaEmailManager\Model\Base\EmailManagerTrace object.
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
     * Compares this with another <code>EmailManagerTrace</code> instance.  If
     * <code>obj</code> is an instance of <code>EmailManagerTrace</code>, delegates to
     * <code>equals(EmailManagerTrace)</code>.  Otherwise, returns <code>false</code>.
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
     * @return EmailManagerTrace The current object, for fluid interface
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
     * @return EmailManagerTrace The current object, for fluid interface
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
     * Get the [parent_id] column value.
     *
     * @return   int
     */
    public function getParentId()
    {

        return $this->parent_id;
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
     * @return   \TheliaEmailManager\Model\EmailManagerTrace The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[EmailManagerTraceTableMap::ID] = true;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [parent_id] column.
     *
     * @param      int $v new value
     * @return   \TheliaEmailManager\Model\EmailManagerTrace The current object (for fluent API support)
     */
    public function setParentId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->parent_id !== $v) {
            $this->parent_id = $v;
            $this->modifiedColumns[EmailManagerTraceTableMap::PARENT_ID] = true;
        }

        if ($this->aEmailManagerTraceRelatedByParentId !== null && $this->aEmailManagerTraceRelatedByParentId->getId() !== $v) {
            $this->aEmailManagerTraceRelatedByParentId = null;
        }


        return $this;
    } // setParentId()

    /**
     * Set the value of [hash] column.
     *
     * @param      string $v new value
     * @return   \TheliaEmailManager\Model\EmailManagerTrace The current object (for fluent API support)
     */
    public function setHash($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->hash !== $v) {
            $this->hash = $v;
            $this->modifiedColumns[EmailManagerTraceTableMap::HASH] = true;
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
     * @return   \TheliaEmailManager\Model\EmailManagerTrace The current object (for fluent API support)
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
            $this->modifiedColumns[EmailManagerTraceTableMap::DISABLE_HISTORY] = true;
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
     * @return   \TheliaEmailManager\Model\EmailManagerTrace The current object (for fluent API support)
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
            $this->modifiedColumns[EmailManagerTraceTableMap::DISABLE_SENDING] = true;
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
     * @return   \TheliaEmailManager\Model\EmailManagerTrace The current object (for fluent API support)
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
            $this->modifiedColumns[EmailManagerTraceTableMap::FORCE_SAME_CUSTOMER_DISABLE] = true;
        }


        return $this;
    } // setForceSameCustomerDisable()

    /**
     * Set the value of [number_of_catch] column.
     *
     * @param      int $v new value
     * @return   \TheliaEmailManager\Model\EmailManagerTrace The current object (for fluent API support)
     */
    public function setNumberOfCatch($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->number_of_catch !== $v) {
            $this->number_of_catch = $v;
            $this->modifiedColumns[EmailManagerTraceTableMap::NUMBER_OF_CATCH] = true;
        }


        return $this;
    } // setNumberOfCatch()

    /**
     * Set the value of [email_bcc] column.
     *
     * @param      array $v new value
     * @return   \TheliaEmailManager\Model\EmailManagerTrace The current object (for fluent API support)
     */
    public function setEmailBcc($v)
    {
        if ($this->email_bcc_unserialized !== $v) {
            $this->email_bcc_unserialized = $v;
            $this->email_bcc = '| ' . implode(' | ', $v) . ' |';
            $this->modifiedColumns[EmailManagerTraceTableMap::EMAIL_BCC] = true;
        }


        return $this;
    } // setEmailBcc()

    /**
     * Set the value of [email_redirect] column.
     *
     * @param      array $v new value
     * @return   \TheliaEmailManager\Model\EmailManagerTrace The current object (for fluent API support)
     */
    public function setEmailRedirect($v)
    {
        if ($this->email_redirect_unserialized !== $v) {
            $this->email_redirect_unserialized = $v;
            $this->email_redirect = '| ' . implode(' | ', $v) . ' |';
            $this->modifiedColumns[EmailManagerTraceTableMap::EMAIL_REDIRECT] = true;
        }


        return $this;
    } // setEmailRedirect()

    /**
     * Set the value of [detail] column.
     *
     * @param      string $v new value
     * @return   \TheliaEmailManager\Model\EmailManagerTrace The current object (for fluent API support)
     */
    public function setDetail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->detail !== $v) {
            $this->detail = $v;
            $this->modifiedColumns[EmailManagerTraceTableMap::DETAIL] = true;
        }


        return $this;
    } // setDetail()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param      mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return   \TheliaEmailManager\Model\EmailManagerTrace The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($dt !== $this->created_at) {
                $this->created_at = $dt;
                $this->modifiedColumns[EmailManagerTraceTableMap::CREATED_AT] = true;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param      mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return   \TheliaEmailManager\Model\EmailManagerTrace The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($dt !== $this->updated_at) {
                $this->updated_at = $dt;
                $this->modifiedColumns[EmailManagerTraceTableMap::UPDATED_AT] = true;
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


            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : EmailManagerTraceTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : EmailManagerTraceTableMap::translateFieldName('ParentId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->parent_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : EmailManagerTraceTableMap::translateFieldName('Hash', TableMap::TYPE_PHPNAME, $indexType)];
            $this->hash = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : EmailManagerTraceTableMap::translateFieldName('DisableHistory', TableMap::TYPE_PHPNAME, $indexType)];
            $this->disable_history = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : EmailManagerTraceTableMap::translateFieldName('DisableSending', TableMap::TYPE_PHPNAME, $indexType)];
            $this->disable_sending = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : EmailManagerTraceTableMap::translateFieldName('ForceSameCustomerDisable', TableMap::TYPE_PHPNAME, $indexType)];
            $this->force_same_customer_disable = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : EmailManagerTraceTableMap::translateFieldName('NumberOfCatch', TableMap::TYPE_PHPNAME, $indexType)];
            $this->number_of_catch = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : EmailManagerTraceTableMap::translateFieldName('EmailBcc', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email_bcc = $col;
            $this->email_bcc_unserialized = null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : EmailManagerTraceTableMap::translateFieldName('EmailRedirect', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email_redirect = $col;
            $this->email_redirect_unserialized = null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : EmailManagerTraceTableMap::translateFieldName('Detail', TableMap::TYPE_PHPNAME, $indexType)];
            $this->detail = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : EmailManagerTraceTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : EmailManagerTraceTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 12; // 12 = EmailManagerTraceTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating \TheliaEmailManager\Model\EmailManagerTrace object", 0, $e);
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
        if ($this->aEmailManagerTraceRelatedByParentId !== null && $this->parent_id !== $this->aEmailManagerTraceRelatedByParentId->getId()) {
            $this->aEmailManagerTraceRelatedByParentId = null;
        }
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
            $con = Propel::getServiceContainer()->getReadConnection(EmailManagerTraceTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildEmailManagerTraceQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aEmailManagerTraceRelatedByParentId = null;
            $this->collEmailManagerTracesRelatedById = null;

            $this->collEmailManagerHistories = null;

            $this->collEmailManagerTraceI18ns = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see EmailManagerTrace::setDeleted()
     * @see EmailManagerTrace::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(EmailManagerTraceTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ChildEmailManagerTraceQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(EmailManagerTraceTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(EmailManagerTraceTableMap::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(EmailManagerTraceTableMap::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(EmailManagerTraceTableMap::UPDATED_AT)) {
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
                EmailManagerTraceTableMap::addInstanceToPool($this);
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

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aEmailManagerTraceRelatedByParentId !== null) {
                if ($this->aEmailManagerTraceRelatedByParentId->isModified() || $this->aEmailManagerTraceRelatedByParentId->isNew()) {
                    $affectedRows += $this->aEmailManagerTraceRelatedByParentId->save($con);
                }
                $this->setEmailManagerTraceRelatedByParentId($this->aEmailManagerTraceRelatedByParentId);
            }

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

            if ($this->emailManagerTracesRelatedByIdScheduledForDeletion !== null) {
                if (!$this->emailManagerTracesRelatedByIdScheduledForDeletion->isEmpty()) {
                    \TheliaEmailManager\Model\EmailManagerTraceQuery::create()
                        ->filterByPrimaryKeys($this->emailManagerTracesRelatedByIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->emailManagerTracesRelatedByIdScheduledForDeletion = null;
                }
            }

                if ($this->collEmailManagerTracesRelatedById !== null) {
            foreach ($this->collEmailManagerTracesRelatedById as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->emailManagerHistoriesScheduledForDeletion !== null) {
                if (!$this->emailManagerHistoriesScheduledForDeletion->isEmpty()) {
                    \TheliaEmailManager\Model\EmailManagerHistoryQuery::create()
                        ->filterByPrimaryKeys($this->emailManagerHistoriesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->emailManagerHistoriesScheduledForDeletion = null;
                }
            }

                if ($this->collEmailManagerHistories !== null) {
            foreach ($this->collEmailManagerHistories as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->emailManagerTraceI18nsScheduledForDeletion !== null) {
                if (!$this->emailManagerTraceI18nsScheduledForDeletion->isEmpty()) {
                    \TheliaEmailManager\Model\EmailManagerTraceI18nQuery::create()
                        ->filterByPrimaryKeys($this->emailManagerTraceI18nsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->emailManagerTraceI18nsScheduledForDeletion = null;
                }
            }

                if ($this->collEmailManagerTraceI18ns !== null) {
            foreach ($this->collEmailManagerTraceI18ns as $referrerFK) {
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

        $this->modifiedColumns[EmailManagerTraceTableMap::ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . EmailManagerTraceTableMap::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(EmailManagerTraceTableMap::ID)) {
            $modifiedColumns[':p' . $index++]  = 'ID';
        }
        if ($this->isColumnModified(EmailManagerTraceTableMap::PARENT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'PARENT_ID';
        }
        if ($this->isColumnModified(EmailManagerTraceTableMap::HASH)) {
            $modifiedColumns[':p' . $index++]  = 'HASH';
        }
        if ($this->isColumnModified(EmailManagerTraceTableMap::DISABLE_HISTORY)) {
            $modifiedColumns[':p' . $index++]  = 'DISABLE_HISTORY';
        }
        if ($this->isColumnModified(EmailManagerTraceTableMap::DISABLE_SENDING)) {
            $modifiedColumns[':p' . $index++]  = 'DISABLE_SENDING';
        }
        if ($this->isColumnModified(EmailManagerTraceTableMap::FORCE_SAME_CUSTOMER_DISABLE)) {
            $modifiedColumns[':p' . $index++]  = 'FORCE_SAME_CUSTOMER_DISABLE';
        }
        if ($this->isColumnModified(EmailManagerTraceTableMap::NUMBER_OF_CATCH)) {
            $modifiedColumns[':p' . $index++]  = 'NUMBER_OF_CATCH';
        }
        if ($this->isColumnModified(EmailManagerTraceTableMap::EMAIL_BCC)) {
            $modifiedColumns[':p' . $index++]  = 'EMAIL_BCC';
        }
        if ($this->isColumnModified(EmailManagerTraceTableMap::EMAIL_REDIRECT)) {
            $modifiedColumns[':p' . $index++]  = 'EMAIL_REDIRECT';
        }
        if ($this->isColumnModified(EmailManagerTraceTableMap::DETAIL)) {
            $modifiedColumns[':p' . $index++]  = 'DETAIL';
        }
        if ($this->isColumnModified(EmailManagerTraceTableMap::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'CREATED_AT';
        }
        if ($this->isColumnModified(EmailManagerTraceTableMap::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'UPDATED_AT';
        }

        $sql = sprintf(
            'INSERT INTO email_manager_trace (%s) VALUES (%s)',
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
                    case 'PARENT_ID':
                        $stmt->bindValue($identifier, $this->parent_id, PDO::PARAM_INT);
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
        $pos = EmailManagerTraceTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getParentId();
                break;
            case 2:
                return $this->getHash();
                break;
            case 3:
                return $this->getDisableHistory();
                break;
            case 4:
                return $this->getDisableSending();
                break;
            case 5:
                return $this->getForceSameCustomerDisable();
                break;
            case 6:
                return $this->getNumberOfCatch();
                break;
            case 7:
                return $this->getEmailBcc();
                break;
            case 8:
                return $this->getEmailRedirect();
                break;
            case 9:
                return $this->getDetail();
                break;
            case 10:
                return $this->getCreatedAt();
                break;
            case 11:
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
        if (isset($alreadyDumpedObjects['EmailManagerTrace'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['EmailManagerTrace'][$this->getPrimaryKey()] = true;
        $keys = EmailManagerTraceTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getParentId(),
            $keys[2] => $this->getHash(),
            $keys[3] => $this->getDisableHistory(),
            $keys[4] => $this->getDisableSending(),
            $keys[5] => $this->getForceSameCustomerDisable(),
            $keys[6] => $this->getNumberOfCatch(),
            $keys[7] => $this->getEmailBcc(),
            $keys[8] => $this->getEmailRedirect(),
            $keys[9] => $this->getDetail(),
            $keys[10] => $this->getCreatedAt(),
            $keys[11] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aEmailManagerTraceRelatedByParentId) {
                $result['EmailManagerTraceRelatedByParentId'] = $this->aEmailManagerTraceRelatedByParentId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collEmailManagerTracesRelatedById) {
                $result['EmailManagerTracesRelatedById'] = $this->collEmailManagerTracesRelatedById->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEmailManagerHistories) {
                $result['EmailManagerHistories'] = $this->collEmailManagerHistories->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEmailManagerTraceI18ns) {
                $result['EmailManagerTraceI18ns'] = $this->collEmailManagerTraceI18ns->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = EmailManagerTraceTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setParentId($value);
                break;
            case 2:
                $this->setHash($value);
                break;
            case 3:
                $this->setDisableHistory($value);
                break;
            case 4:
                $this->setDisableSending($value);
                break;
            case 5:
                $this->setForceSameCustomerDisable($value);
                break;
            case 6:
                $this->setNumberOfCatch($value);
                break;
            case 7:
                if (!is_array($value)) {
                    $v = trim(substr($value, 2, -2));
                    $value = $v ? explode(' | ', $v) : array();
                }
                $this->setEmailBcc($value);
                break;
            case 8:
                if (!is_array($value)) {
                    $v = trim(substr($value, 2, -2));
                    $value = $v ? explode(' | ', $v) : array();
                }
                $this->setEmailRedirect($value);
                break;
            case 9:
                $this->setDetail($value);
                break;
            case 10:
                $this->setCreatedAt($value);
                break;
            case 11:
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
        $keys = EmailManagerTraceTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setParentId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setHash($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setDisableHistory($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setDisableSending($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setForceSameCustomerDisable($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setNumberOfCatch($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setEmailBcc($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setEmailRedirect($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setDetail($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setCreatedAt($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setUpdatedAt($arr[$keys[11]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(EmailManagerTraceTableMap::DATABASE_NAME);

        if ($this->isColumnModified(EmailManagerTraceTableMap::ID)) $criteria->add(EmailManagerTraceTableMap::ID, $this->id);
        if ($this->isColumnModified(EmailManagerTraceTableMap::PARENT_ID)) $criteria->add(EmailManagerTraceTableMap::PARENT_ID, $this->parent_id);
        if ($this->isColumnModified(EmailManagerTraceTableMap::HASH)) $criteria->add(EmailManagerTraceTableMap::HASH, $this->hash);
        if ($this->isColumnModified(EmailManagerTraceTableMap::DISABLE_HISTORY)) $criteria->add(EmailManagerTraceTableMap::DISABLE_HISTORY, $this->disable_history);
        if ($this->isColumnModified(EmailManagerTraceTableMap::DISABLE_SENDING)) $criteria->add(EmailManagerTraceTableMap::DISABLE_SENDING, $this->disable_sending);
        if ($this->isColumnModified(EmailManagerTraceTableMap::FORCE_SAME_CUSTOMER_DISABLE)) $criteria->add(EmailManagerTraceTableMap::FORCE_SAME_CUSTOMER_DISABLE, $this->force_same_customer_disable);
        if ($this->isColumnModified(EmailManagerTraceTableMap::NUMBER_OF_CATCH)) $criteria->add(EmailManagerTraceTableMap::NUMBER_OF_CATCH, $this->number_of_catch);
        if ($this->isColumnModified(EmailManagerTraceTableMap::EMAIL_BCC)) $criteria->add(EmailManagerTraceTableMap::EMAIL_BCC, $this->email_bcc);
        if ($this->isColumnModified(EmailManagerTraceTableMap::EMAIL_REDIRECT)) $criteria->add(EmailManagerTraceTableMap::EMAIL_REDIRECT, $this->email_redirect);
        if ($this->isColumnModified(EmailManagerTraceTableMap::DETAIL)) $criteria->add(EmailManagerTraceTableMap::DETAIL, $this->detail);
        if ($this->isColumnModified(EmailManagerTraceTableMap::CREATED_AT)) $criteria->add(EmailManagerTraceTableMap::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(EmailManagerTraceTableMap::UPDATED_AT)) $criteria->add(EmailManagerTraceTableMap::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(EmailManagerTraceTableMap::DATABASE_NAME);
        $criteria->add(EmailManagerTraceTableMap::ID, $this->id);

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
     * @param      object $copyObj An object of \TheliaEmailManager\Model\EmailManagerTrace (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setParentId($this->getParentId());
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

            foreach ($this->getEmailManagerTracesRelatedById() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEmailManagerTraceRelatedById($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEmailManagerHistories() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEmailManagerHistory($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEmailManagerTraceI18ns() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEmailManagerTraceI18n($relObj->copy($deepCopy));
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
     * @return                 \TheliaEmailManager\Model\EmailManagerTrace Clone of current object.
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
     * Declares an association between this object and a ChildEmailManagerTrace object.
     *
     * @param                  ChildEmailManagerTrace $v
     * @return                 \TheliaEmailManager\Model\EmailManagerTrace The current object (for fluent API support)
     * @throws PropelException
     */
    public function setEmailManagerTraceRelatedByParentId(ChildEmailManagerTrace $v = null)
    {
        if ($v === null) {
            $this->setParentId(NULL);
        } else {
            $this->setParentId($v->getId());
        }

        $this->aEmailManagerTraceRelatedByParentId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildEmailManagerTrace object, it will not be re-added.
        if ($v !== null) {
            $v->addEmailManagerTraceRelatedById($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildEmailManagerTrace object
     *
     * @param      ConnectionInterface $con Optional Connection object.
     * @return                 ChildEmailManagerTrace The associated ChildEmailManagerTrace object.
     * @throws PropelException
     */
    public function getEmailManagerTraceRelatedByParentId(ConnectionInterface $con = null)
    {
        if ($this->aEmailManagerTraceRelatedByParentId === null && ($this->parent_id !== null)) {
            $this->aEmailManagerTraceRelatedByParentId = ChildEmailManagerTraceQuery::create()->findPk($this->parent_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aEmailManagerTraceRelatedByParentId->addEmailManagerTracesRelatedById($this);
             */
        }

        return $this->aEmailManagerTraceRelatedByParentId;
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
        if ('EmailManagerTraceRelatedById' == $relationName) {
            return $this->initEmailManagerTracesRelatedById();
        }
        if ('EmailManagerHistory' == $relationName) {
            return $this->initEmailManagerHistories();
        }
        if ('EmailManagerTraceI18n' == $relationName) {
            return $this->initEmailManagerTraceI18ns();
        }
    }

    /**
     * Clears out the collEmailManagerTracesRelatedById collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEmailManagerTracesRelatedById()
     */
    public function clearEmailManagerTracesRelatedById()
    {
        $this->collEmailManagerTracesRelatedById = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collEmailManagerTracesRelatedById collection loaded partially.
     */
    public function resetPartialEmailManagerTracesRelatedById($v = true)
    {
        $this->collEmailManagerTracesRelatedByIdPartial = $v;
    }

    /**
     * Initializes the collEmailManagerTracesRelatedById collection.
     *
     * By default this just sets the collEmailManagerTracesRelatedById collection to an empty array (like clearcollEmailManagerTracesRelatedById());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEmailManagerTracesRelatedById($overrideExisting = true)
    {
        if (null !== $this->collEmailManagerTracesRelatedById && !$overrideExisting) {
            return;
        }
        $this->collEmailManagerTracesRelatedById = new ObjectCollection();
        $this->collEmailManagerTracesRelatedById->setModel('\TheliaEmailManager\Model\EmailManagerTrace');
    }

    /**
     * Gets an array of ChildEmailManagerTrace objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEmailManagerTrace is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildEmailManagerTrace[] List of ChildEmailManagerTrace objects
     * @throws PropelException
     */
    public function getEmailManagerTracesRelatedById($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collEmailManagerTracesRelatedByIdPartial && !$this->isNew();
        if (null === $this->collEmailManagerTracesRelatedById || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEmailManagerTracesRelatedById) {
                // return empty collection
                $this->initEmailManagerTracesRelatedById();
            } else {
                $collEmailManagerTracesRelatedById = ChildEmailManagerTraceQuery::create(null, $criteria)
                    ->filterByEmailManagerTraceRelatedByParentId($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collEmailManagerTracesRelatedByIdPartial && count($collEmailManagerTracesRelatedById)) {
                        $this->initEmailManagerTracesRelatedById(false);

                        foreach ($collEmailManagerTracesRelatedById as $obj) {
                            if (false == $this->collEmailManagerTracesRelatedById->contains($obj)) {
                                $this->collEmailManagerTracesRelatedById->append($obj);
                            }
                        }

                        $this->collEmailManagerTracesRelatedByIdPartial = true;
                    }

                    reset($collEmailManagerTracesRelatedById);

                    return $collEmailManagerTracesRelatedById;
                }

                if ($partial && $this->collEmailManagerTracesRelatedById) {
                    foreach ($this->collEmailManagerTracesRelatedById as $obj) {
                        if ($obj->isNew()) {
                            $collEmailManagerTracesRelatedById[] = $obj;
                        }
                    }
                }

                $this->collEmailManagerTracesRelatedById = $collEmailManagerTracesRelatedById;
                $this->collEmailManagerTracesRelatedByIdPartial = false;
            }
        }

        return $this->collEmailManagerTracesRelatedById;
    }

    /**
     * Sets a collection of EmailManagerTraceRelatedById objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $emailManagerTracesRelatedById A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildEmailManagerTrace The current object (for fluent API support)
     */
    public function setEmailManagerTracesRelatedById(Collection $emailManagerTracesRelatedById, ConnectionInterface $con = null)
    {
        $emailManagerTracesRelatedByIdToDelete = $this->getEmailManagerTracesRelatedById(new Criteria(), $con)->diff($emailManagerTracesRelatedById);


        $this->emailManagerTracesRelatedByIdScheduledForDeletion = $emailManagerTracesRelatedByIdToDelete;

        foreach ($emailManagerTracesRelatedByIdToDelete as $emailManagerTraceRelatedByIdRemoved) {
            $emailManagerTraceRelatedByIdRemoved->setEmailManagerTraceRelatedByParentId(null);
        }

        $this->collEmailManagerTracesRelatedById = null;
        foreach ($emailManagerTracesRelatedById as $emailManagerTraceRelatedById) {
            $this->addEmailManagerTraceRelatedById($emailManagerTraceRelatedById);
        }

        $this->collEmailManagerTracesRelatedById = $emailManagerTracesRelatedById;
        $this->collEmailManagerTracesRelatedByIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related EmailManagerTrace objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related EmailManagerTrace objects.
     * @throws PropelException
     */
    public function countEmailManagerTracesRelatedById(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collEmailManagerTracesRelatedByIdPartial && !$this->isNew();
        if (null === $this->collEmailManagerTracesRelatedById || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEmailManagerTracesRelatedById) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEmailManagerTracesRelatedById());
            }

            $query = ChildEmailManagerTraceQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEmailManagerTraceRelatedByParentId($this)
                ->count($con);
        }

        return count($this->collEmailManagerTracesRelatedById);
    }

    /**
     * Method called to associate a ChildEmailManagerTrace object to this object
     * through the ChildEmailManagerTrace foreign key attribute.
     *
     * @param    ChildEmailManagerTrace $l ChildEmailManagerTrace
     * @return   \TheliaEmailManager\Model\EmailManagerTrace The current object (for fluent API support)
     */
    public function addEmailManagerTraceRelatedById(ChildEmailManagerTrace $l)
    {
        if ($this->collEmailManagerTracesRelatedById === null) {
            $this->initEmailManagerTracesRelatedById();
            $this->collEmailManagerTracesRelatedByIdPartial = true;
        }

        if (!in_array($l, $this->collEmailManagerTracesRelatedById->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddEmailManagerTraceRelatedById($l);
        }

        return $this;
    }

    /**
     * @param EmailManagerTraceRelatedById $emailManagerTraceRelatedById The emailManagerTraceRelatedById object to add.
     */
    protected function doAddEmailManagerTraceRelatedById($emailManagerTraceRelatedById)
    {
        $this->collEmailManagerTracesRelatedById[]= $emailManagerTraceRelatedById;
        $emailManagerTraceRelatedById->setEmailManagerTraceRelatedByParentId($this);
    }

    /**
     * @param  EmailManagerTraceRelatedById $emailManagerTraceRelatedById The emailManagerTraceRelatedById object to remove.
     * @return ChildEmailManagerTrace The current object (for fluent API support)
     */
    public function removeEmailManagerTraceRelatedById($emailManagerTraceRelatedById)
    {
        if ($this->getEmailManagerTracesRelatedById()->contains($emailManagerTraceRelatedById)) {
            $this->collEmailManagerTracesRelatedById->remove($this->collEmailManagerTracesRelatedById->search($emailManagerTraceRelatedById));
            if (null === $this->emailManagerTracesRelatedByIdScheduledForDeletion) {
                $this->emailManagerTracesRelatedByIdScheduledForDeletion = clone $this->collEmailManagerTracesRelatedById;
                $this->emailManagerTracesRelatedByIdScheduledForDeletion->clear();
            }
            $this->emailManagerTracesRelatedByIdScheduledForDeletion[]= $emailManagerTraceRelatedById;
            $emailManagerTraceRelatedById->setEmailManagerTraceRelatedByParentId(null);
        }

        return $this;
    }

    /**
     * Clears out the collEmailManagerHistories collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEmailManagerHistories()
     */
    public function clearEmailManagerHistories()
    {
        $this->collEmailManagerHistories = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collEmailManagerHistories collection loaded partially.
     */
    public function resetPartialEmailManagerHistories($v = true)
    {
        $this->collEmailManagerHistoriesPartial = $v;
    }

    /**
     * Initializes the collEmailManagerHistories collection.
     *
     * By default this just sets the collEmailManagerHistories collection to an empty array (like clearcollEmailManagerHistories());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEmailManagerHistories($overrideExisting = true)
    {
        if (null !== $this->collEmailManagerHistories && !$overrideExisting) {
            return;
        }
        $this->collEmailManagerHistories = new ObjectCollection();
        $this->collEmailManagerHistories->setModel('\TheliaEmailManager\Model\EmailManagerHistory');
    }

    /**
     * Gets an array of ChildEmailManagerHistory objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEmailManagerTrace is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildEmailManagerHistory[] List of ChildEmailManagerHistory objects
     * @throws PropelException
     */
    public function getEmailManagerHistories($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collEmailManagerHistoriesPartial && !$this->isNew();
        if (null === $this->collEmailManagerHistories || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEmailManagerHistories) {
                // return empty collection
                $this->initEmailManagerHistories();
            } else {
                $collEmailManagerHistories = ChildEmailManagerHistoryQuery::create(null, $criteria)
                    ->filterByEmailManagerTrace($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collEmailManagerHistoriesPartial && count($collEmailManagerHistories)) {
                        $this->initEmailManagerHistories(false);

                        foreach ($collEmailManagerHistories as $obj) {
                            if (false == $this->collEmailManagerHistories->contains($obj)) {
                                $this->collEmailManagerHistories->append($obj);
                            }
                        }

                        $this->collEmailManagerHistoriesPartial = true;
                    }

                    reset($collEmailManagerHistories);

                    return $collEmailManagerHistories;
                }

                if ($partial && $this->collEmailManagerHistories) {
                    foreach ($this->collEmailManagerHistories as $obj) {
                        if ($obj->isNew()) {
                            $collEmailManagerHistories[] = $obj;
                        }
                    }
                }

                $this->collEmailManagerHistories = $collEmailManagerHistories;
                $this->collEmailManagerHistoriesPartial = false;
            }
        }

        return $this->collEmailManagerHistories;
    }

    /**
     * Sets a collection of EmailManagerHistory objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $emailManagerHistories A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildEmailManagerTrace The current object (for fluent API support)
     */
    public function setEmailManagerHistories(Collection $emailManagerHistories, ConnectionInterface $con = null)
    {
        $emailManagerHistoriesToDelete = $this->getEmailManagerHistories(new Criteria(), $con)->diff($emailManagerHistories);


        $this->emailManagerHistoriesScheduledForDeletion = $emailManagerHistoriesToDelete;

        foreach ($emailManagerHistoriesToDelete as $emailManagerHistoryRemoved) {
            $emailManagerHistoryRemoved->setEmailManagerTrace(null);
        }

        $this->collEmailManagerHistories = null;
        foreach ($emailManagerHistories as $emailManagerHistory) {
            $this->addEmailManagerHistory($emailManagerHistory);
        }

        $this->collEmailManagerHistories = $emailManagerHistories;
        $this->collEmailManagerHistoriesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related EmailManagerHistory objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related EmailManagerHistory objects.
     * @throws PropelException
     */
    public function countEmailManagerHistories(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collEmailManagerHistoriesPartial && !$this->isNew();
        if (null === $this->collEmailManagerHistories || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEmailManagerHistories) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEmailManagerHistories());
            }

            $query = ChildEmailManagerHistoryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEmailManagerTrace($this)
                ->count($con);
        }

        return count($this->collEmailManagerHistories);
    }

    /**
     * Method called to associate a ChildEmailManagerHistory object to this object
     * through the ChildEmailManagerHistory foreign key attribute.
     *
     * @param    ChildEmailManagerHistory $l ChildEmailManagerHistory
     * @return   \TheliaEmailManager\Model\EmailManagerTrace The current object (for fluent API support)
     */
    public function addEmailManagerHistory(ChildEmailManagerHistory $l)
    {
        if ($this->collEmailManagerHistories === null) {
            $this->initEmailManagerHistories();
            $this->collEmailManagerHistoriesPartial = true;
        }

        if (!in_array($l, $this->collEmailManagerHistories->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddEmailManagerHistory($l);
        }

        return $this;
    }

    /**
     * @param EmailManagerHistory $emailManagerHistory The emailManagerHistory object to add.
     */
    protected function doAddEmailManagerHistory($emailManagerHistory)
    {
        $this->collEmailManagerHistories[]= $emailManagerHistory;
        $emailManagerHistory->setEmailManagerTrace($this);
    }

    /**
     * @param  EmailManagerHistory $emailManagerHistory The emailManagerHistory object to remove.
     * @return ChildEmailManagerTrace The current object (for fluent API support)
     */
    public function removeEmailManagerHistory($emailManagerHistory)
    {
        if ($this->getEmailManagerHistories()->contains($emailManagerHistory)) {
            $this->collEmailManagerHistories->remove($this->collEmailManagerHistories->search($emailManagerHistory));
            if (null === $this->emailManagerHistoriesScheduledForDeletion) {
                $this->emailManagerHistoriesScheduledForDeletion = clone $this->collEmailManagerHistories;
                $this->emailManagerHistoriesScheduledForDeletion->clear();
            }
            $this->emailManagerHistoriesScheduledForDeletion[]= clone $emailManagerHistory;
            $emailManagerHistory->setEmailManagerTrace(null);
        }

        return $this;
    }

    /**
     * Clears out the collEmailManagerTraceI18ns collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEmailManagerTraceI18ns()
     */
    public function clearEmailManagerTraceI18ns()
    {
        $this->collEmailManagerTraceI18ns = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collEmailManagerTraceI18ns collection loaded partially.
     */
    public function resetPartialEmailManagerTraceI18ns($v = true)
    {
        $this->collEmailManagerTraceI18nsPartial = $v;
    }

    /**
     * Initializes the collEmailManagerTraceI18ns collection.
     *
     * By default this just sets the collEmailManagerTraceI18ns collection to an empty array (like clearcollEmailManagerTraceI18ns());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEmailManagerTraceI18ns($overrideExisting = true)
    {
        if (null !== $this->collEmailManagerTraceI18ns && !$overrideExisting) {
            return;
        }
        $this->collEmailManagerTraceI18ns = new ObjectCollection();
        $this->collEmailManagerTraceI18ns->setModel('\TheliaEmailManager\Model\EmailManagerTraceI18n');
    }

    /**
     * Gets an array of ChildEmailManagerTraceI18n objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEmailManagerTrace is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildEmailManagerTraceI18n[] List of ChildEmailManagerTraceI18n objects
     * @throws PropelException
     */
    public function getEmailManagerTraceI18ns($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collEmailManagerTraceI18nsPartial && !$this->isNew();
        if (null === $this->collEmailManagerTraceI18ns || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEmailManagerTraceI18ns) {
                // return empty collection
                $this->initEmailManagerTraceI18ns();
            } else {
                $collEmailManagerTraceI18ns = ChildEmailManagerTraceI18nQuery::create(null, $criteria)
                    ->filterByEmailManagerTrace($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collEmailManagerTraceI18nsPartial && count($collEmailManagerTraceI18ns)) {
                        $this->initEmailManagerTraceI18ns(false);

                        foreach ($collEmailManagerTraceI18ns as $obj) {
                            if (false == $this->collEmailManagerTraceI18ns->contains($obj)) {
                                $this->collEmailManagerTraceI18ns->append($obj);
                            }
                        }

                        $this->collEmailManagerTraceI18nsPartial = true;
                    }

                    reset($collEmailManagerTraceI18ns);

                    return $collEmailManagerTraceI18ns;
                }

                if ($partial && $this->collEmailManagerTraceI18ns) {
                    foreach ($this->collEmailManagerTraceI18ns as $obj) {
                        if ($obj->isNew()) {
                            $collEmailManagerTraceI18ns[] = $obj;
                        }
                    }
                }

                $this->collEmailManagerTraceI18ns = $collEmailManagerTraceI18ns;
                $this->collEmailManagerTraceI18nsPartial = false;
            }
        }

        return $this->collEmailManagerTraceI18ns;
    }

    /**
     * Sets a collection of EmailManagerTraceI18n objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $emailManagerTraceI18ns A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildEmailManagerTrace The current object (for fluent API support)
     */
    public function setEmailManagerTraceI18ns(Collection $emailManagerTraceI18ns, ConnectionInterface $con = null)
    {
        $emailManagerTraceI18nsToDelete = $this->getEmailManagerTraceI18ns(new Criteria(), $con)->diff($emailManagerTraceI18ns);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->emailManagerTraceI18nsScheduledForDeletion = clone $emailManagerTraceI18nsToDelete;

        foreach ($emailManagerTraceI18nsToDelete as $emailManagerTraceI18nRemoved) {
            $emailManagerTraceI18nRemoved->setEmailManagerTrace(null);
        }

        $this->collEmailManagerTraceI18ns = null;
        foreach ($emailManagerTraceI18ns as $emailManagerTraceI18n) {
            $this->addEmailManagerTraceI18n($emailManagerTraceI18n);
        }

        $this->collEmailManagerTraceI18ns = $emailManagerTraceI18ns;
        $this->collEmailManagerTraceI18nsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related EmailManagerTraceI18n objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related EmailManagerTraceI18n objects.
     * @throws PropelException
     */
    public function countEmailManagerTraceI18ns(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collEmailManagerTraceI18nsPartial && !$this->isNew();
        if (null === $this->collEmailManagerTraceI18ns || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEmailManagerTraceI18ns) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEmailManagerTraceI18ns());
            }

            $query = ChildEmailManagerTraceI18nQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEmailManagerTrace($this)
                ->count($con);
        }

        return count($this->collEmailManagerTraceI18ns);
    }

    /**
     * Method called to associate a ChildEmailManagerTraceI18n object to this object
     * through the ChildEmailManagerTraceI18n foreign key attribute.
     *
     * @param    ChildEmailManagerTraceI18n $l ChildEmailManagerTraceI18n
     * @return   \TheliaEmailManager\Model\EmailManagerTrace The current object (for fluent API support)
     */
    public function addEmailManagerTraceI18n(ChildEmailManagerTraceI18n $l)
    {
        if ($l && $locale = $l->getLocale()) {
            $this->setLocale($locale);
            $this->currentTranslations[$locale] = $l;
        }
        if ($this->collEmailManagerTraceI18ns === null) {
            $this->initEmailManagerTraceI18ns();
            $this->collEmailManagerTraceI18nsPartial = true;
        }

        if (!in_array($l, $this->collEmailManagerTraceI18ns->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddEmailManagerTraceI18n($l);
        }

        return $this;
    }

    /**
     * @param EmailManagerTraceI18n $emailManagerTraceI18n The emailManagerTraceI18n object to add.
     */
    protected function doAddEmailManagerTraceI18n($emailManagerTraceI18n)
    {
        $this->collEmailManagerTraceI18ns[]= $emailManagerTraceI18n;
        $emailManagerTraceI18n->setEmailManagerTrace($this);
    }

    /**
     * @param  EmailManagerTraceI18n $emailManagerTraceI18n The emailManagerTraceI18n object to remove.
     * @return ChildEmailManagerTrace The current object (for fluent API support)
     */
    public function removeEmailManagerTraceI18n($emailManagerTraceI18n)
    {
        if ($this->getEmailManagerTraceI18ns()->contains($emailManagerTraceI18n)) {
            $this->collEmailManagerTraceI18ns->remove($this->collEmailManagerTraceI18ns->search($emailManagerTraceI18n));
            if (null === $this->emailManagerTraceI18nsScheduledForDeletion) {
                $this->emailManagerTraceI18nsScheduledForDeletion = clone $this->collEmailManagerTraceI18ns;
                $this->emailManagerTraceI18nsScheduledForDeletion->clear();
            }
            $this->emailManagerTraceI18nsScheduledForDeletion[]= clone $emailManagerTraceI18n;
            $emailManagerTraceI18n->setEmailManagerTrace(null);
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->parent_id = null;
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
            if ($this->collEmailManagerTracesRelatedById) {
                foreach ($this->collEmailManagerTracesRelatedById as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEmailManagerHistories) {
                foreach ($this->collEmailManagerHistories as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEmailManagerTraceI18ns) {
                foreach ($this->collEmailManagerTraceI18ns as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        // i18n behavior
        $this->currentLocale = 'en_US';
        $this->currentTranslations = null;

        $this->collEmailManagerTracesRelatedById = null;
        $this->collEmailManagerHistories = null;
        $this->collEmailManagerTraceI18ns = null;
        $this->aEmailManagerTraceRelatedByParentId = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(EmailManagerTraceTableMap::DEFAULT_STRING_FORMAT);
    }

    // i18n behavior

    /**
     * Sets the locale for translations
     *
     * @param     string $locale Locale to use for the translation, e.g. 'fr_FR'
     *
     * @return    ChildEmailManagerTrace The current object (for fluent API support)
     */
    public function setLocale($locale = 'en_US')
    {
        $this->currentLocale = $locale;

        return $this;
    }

    /**
     * Gets the locale for translations
     *
     * @return    string $locale Locale to use for the translation, e.g. 'fr_FR'
     */
    public function getLocale()
    {
        return $this->currentLocale;
    }

    /**
     * Returns the current translation for a given locale
     *
     * @param     string $locale Locale to use for the translation, e.g. 'fr_FR'
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ChildEmailManagerTraceI18n */
    public function getTranslation($locale = 'en_US', ConnectionInterface $con = null)
    {
        if (!isset($this->currentTranslations[$locale])) {
            if (null !== $this->collEmailManagerTraceI18ns) {
                foreach ($this->collEmailManagerTraceI18ns as $translation) {
                    if ($translation->getLocale() == $locale) {
                        $this->currentTranslations[$locale] = $translation;

                        return $translation;
                    }
                }
            }
            if ($this->isNew()) {
                $translation = new ChildEmailManagerTraceI18n();
                $translation->setLocale($locale);
            } else {
                $translation = ChildEmailManagerTraceI18nQuery::create()
                    ->filterByPrimaryKey(array($this->getPrimaryKey(), $locale))
                    ->findOneOrCreate($con);
                $this->currentTranslations[$locale] = $translation;
            }
            $this->addEmailManagerTraceI18n($translation);
        }

        return $this->currentTranslations[$locale];
    }

    /**
     * Remove the translation for a given locale
     *
     * @param     string $locale Locale to use for the translation, e.g. 'fr_FR'
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return    ChildEmailManagerTrace The current object (for fluent API support)
     */
    public function removeTranslation($locale = 'en_US', ConnectionInterface $con = null)
    {
        if (!$this->isNew()) {
            ChildEmailManagerTraceI18nQuery::create()
                ->filterByPrimaryKey(array($this->getPrimaryKey(), $locale))
                ->delete($con);
        }
        if (isset($this->currentTranslations[$locale])) {
            unset($this->currentTranslations[$locale]);
        }
        foreach ($this->collEmailManagerTraceI18ns as $key => $translation) {
            if ($translation->getLocale() == $locale) {
                unset($this->collEmailManagerTraceI18ns[$key]);
                break;
            }
        }

        return $this;
    }

    /**
     * Returns the current translation
     *
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ChildEmailManagerTraceI18n */
    public function getCurrentTranslation(ConnectionInterface $con = null)
    {
        return $this->getTranslation($this->getLocale(), $con);
    }


        /**
         * Get the [title] column value.
         *
         * @return   string
         */
        public function getTitle()
        {
        return $this->getCurrentTranslation()->getTitle();
    }


        /**
         * Set the value of [title] column.
         *
         * @param      string $v new value
         * @return   \TheliaEmailManager\Model\EmailManagerTraceI18n The current object (for fluent API support)
         */
        public function setTitle($v)
        {    $this->getCurrentTranslation()->setTitle($v);

        return $this;
    }


        /**
         * Get the [description] column value.
         *
         * @return   string
         */
        public function getDescription()
        {
        return $this->getCurrentTranslation()->getDescription();
    }


        /**
         * Set the value of [description] column.
         *
         * @param      string $v new value
         * @return   \TheliaEmailManager\Model\EmailManagerTraceI18n The current object (for fluent API support)
         */
        public function setDescription($v)
        {    $this->getCurrentTranslation()->setDescription($v);

        return $this;
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     ChildEmailManagerTrace The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[EmailManagerTraceTableMap::UPDATED_AT] = true;

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
