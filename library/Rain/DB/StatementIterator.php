<?php

// set the namespace
namespace Rain\DB;

/**
 * StatementIterator class iterates the result of the query to save memory
 */
class StatementIterator implements \Iterator
{
    protected $statement, $fetch_mode, $key = 0, $value;
    protected $select_key, $select_value;

    public function __construct(\PDOStatement $statement, $fetch_mode, $key = null, $value = null)
    {
        $this->statement    = $statement;
        $this->select_key   = $key;
        $this->select_value = $value;
        $this->fetch_mode   = $fetch_mode;
    }

    /**
     * Alias for PDOStatement::bindColumn
     *
     * @link http://php.net/manual/en/pdostatement.bindcolumn.php Docs
     */
    public function bindColumn(
        $column,
        &$param,
        $type = \PDO::PARAM_STR
    ) {
        return $this->statement->bindColumn($column, $param, $type);
    }

    /**
     * Alias for PDOStatement::bindParam
     *
     * @link http://php.net/manual/en/pdostatement.bindparam.php Docs
     */
    public function bindParam(
        $parameter,
        &$variable,
        $data_type = \PDO::PARAM_STR
    ) {
        return $this->statement->bindParam($parameter, $variable, $data_type);
    }

    /**
     * Alias for PDOStatement:: bindValue
     *
     * @link http://php.net/manual/en/pdostatement.bindvalue.php Docs
     */
    public function bindValue(
        $parameter,
        $variable,
        $data_type = \PDO::PARAM_STR
    ) {
        return $this->statement->bindValue($parameter, $variable, $data_type);
    }

    /**
     * Alias for PDOStatement::columnCount
     *
     * @link http://php.net/manual/en/pdostatement.columncount.php Docs
     */
    public function columnCount()
    {
        return $this->statement->columnCount();
    }

    /**
     * Implementation for Iterator::rewind
     *
     * @link http://php.net/manual/en/iterator.rewind.php Docs
     */
    public function rewind()
    {
        if ($this->statement->execute()) {
            $this->iterate();
        } else {
            $this->value = false;
        }
    }

    /**
     * Implementation for Iterator::valid
     *
     * @link http://php.net/manual/en/iterator.valid.php Docs
     */
    public function valid()
    {
        return (bool) $this->value;
    }

    /**
     * Implementation for Iterator::current
     *
     * @link http://php.net/manual/en/iterator.current.php Docs
     */
    public function current()
    {
        return $this->value;
    }

    /**
     * Implementation for Iterator::next
     *
     * @link http://php.net/manual/en/iterator.next.php Docs
     */
    public function next()
    {
        $this->iterate();
    }

    /**
     * Implementation for Iterator::key
     *
     * @link http://php.net/manual/en/iterator.key.php Docs
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Function that does the real iteration
     */
    protected function iterate()
    {
        // The value of the iterator is a selected field of the query
        if ($this->select_value) {
            // save the result field in $this->value
            $field = $this->statement->fetchColumn(0);
            $this->value = $field;

        } else {
            // save the result row in $this->value
            $row = $this->statement->fetch($this->fetch_mode);
            $this->value = $row;
        }

        $this->key = $this->select_key
            ? $this->key = $row[$this->select_key]
            ? $this->key + 1;
    }
}
