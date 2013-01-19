<?php

namespace Rain\DB

class StatementIterator implements \Iterator{
    protected $statement, $key = 0, $value;

    public function __construct(\PDOStatement $statement){
        $this->statement = $statement;
    }

    public function rewind(){
        $this->key = 0;
        if ($this->statement->execute()) {
            $this->value = $this->statement->fetch();
        } else {
            $this->value = false;
        }
    }

    public function valid(){
        return (bool) $this->value;
    }

    public function current(){
        return $this->value;
    }

    public function next(){
        $this->key++;
        $this->value = $this->statement->fetch();
    }
}
