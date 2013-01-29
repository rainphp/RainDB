<?php

use Rain\DB;

class DBTest extends PHPUnit_Framework_TestCase
{
    
    // count the total query executed
    protected static $nquery = 0;



    function setup(){
        $this->config();
    }
    

    /**
     * Test the DB::init
     */
    function testInit(){
        $this->assertTrue( true === DB::init() );
    }
    
    
    /** 
     * Test DB::query
     */
    function testQuery(){
        self::$nquery++;
        $this->assertTrue( get_class( DB::query("SHOW DATABASES") ) == "PDOStatement" );
    }


    /**
     * Test DB::getRow
     */
    function testGetField(){
        self::$nquery++;
        $field = DB::getField("SELECT firstname FROM user LIMIT 1");
        $this->assertTrue( is_scalar($field) );
    }


    /**
     * Test DB::getRow
     */
    function testGetRow(){
        self::$nquery++;
        $row = DB::getRow("SHOW DATABASES");
        $this->assertTrue( is_array($row) );
    }

    
    /**
     * Test DB::getAll
     */
    function testGetAll(){
        self::$nquery++;
        $row = DB::getAll("SHOW DATABASES");
        $this->assertTrue( $row instanceof \Iterator );
    }

    
    /**
     * Test DB::getAllArray
     */
    function testGetAllArray(){
        self::$nquery++;
        $row = DB::getAllArray("SHOW DATABASES");
        $this->assertTrue( sizeof($row)>=0 );
    }
    

    /**
     * Test DB::getExecutedQuery
     */
    function testExecutedQuery(){
        $this->assertTrue( self::$nquery == DB::getExecutedQuery() );
    }

    /**
     * Config the database
     */
    function config(){
        $config = array(
            'config_dir' => 'config/',
            'config_file'=> 'db.php',
            'fetch_mode' => \PDO::FETCH_ASSOC // set the fetch_mode as associative array
        );
        DB::configure( $config );
    }
    
    
}
