<?php

namespace Rain;   

/**
 * DB - PDO Wrapper library to manage Database
 * 
 * @package Rain
 * @version 3
 * @author Federico Ulfo
 * @link http://rainframework.com
 */

/**
 * DB is the main class of the library
 */
class DB {

    // internal variables
    protected static $db,          // databases list
                     $link,        // database connection object
                     $link_list,   // database connection object list
                     $statement;   // PDO statement
    
    // report variables
    protected static $last_query,  // keep the last query
                     $nquery = 0;

    // configurations
    protected static $conf = array(

        "config_dir"              => "config/",
        "config_file"             => "db.php",
        "default_connection_name" => "dev",
        "fetch_mode"              => \PDO::FETCH_ASSOC,
        
        // Iterator Mode
        // -------------
        // true: getAll will return an Iterator (suggested)
        // false: getAll will return an Array
        //
        "iterator_mode"           => true

    );


    /**
     * Initialize the connection
     * @param string $name Name of the connection
     */
    public static function init($name = null)
    {

        // set the database list
        if (!self::$db) {

            // load the variables
            require_once self::$conf['config_dir'] . self::$conf['config_file'];

            // set the $db list
            self::$db = $db;

        }

        // db account info
        if( !$name ){
            $name = self::$conf['default_connection_name'];
        }

        $driver      = self::$db[$name]['driver'];
        $hostname    = self::$db[$name]['hostname'];
        $database    = self::$db[$name]['database'];
        $username    = self::$db[$name]['username'];
        $password    = self::$db[$name]['password'];
        $pdo_options = isset(self::$db[$name]['pdo_options'])?self::$db[$name]['pdo_options']:array();

        if (!in_array($driver, \PDO::getAvailableDrivers())) {
            die("Error!: could not find a <a href=\"http://php.net/pdo.drivers.php\" target=\"_blank\">" . $driver . "</a> driver<br/>");
        }

        switch ($driver) {
            case 'mysql':
                $string = "mysql:host=$hostname;dbname=$database";
                $pdo_options = array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
                break;
            case 'pgsql':
                $string = "pqsql:host=$hostname;dbname=$database";
                break;
            case 'sqlite':
                $string = "sqlite:$database_path";
                break;
            case 'oracle':
                $string = "OCI:";
                break;
            case 'odbc':
                // $database path
                $string = "odbc:Driver={Microsoft Access Driver (*.mdb)};Dbq=$database;Uid=$username";
                break;
            default:
                die("Error!: Driver $driver not recognized in DB class");
        }

        // connect
        return self::setup($string, $username, $password, $name, $pdo_options);

    }


    /**
    * Execute a query
    *
    * @param string $query
    * @param array $field if you use PDO prepared query here you going to write the field
    */
    public static function query($query = null, $field = array())
    {
        try {
            self::$last_query = $query;
            self::$nquery++;
            
            // if field is declared use the Prepare Statement
            // to clean the variables
            if( $field ){
                self::$statement = self::$link->prepare($query);
                self::$statement->execute($field);
            }
            // if there aren't fields use PDO::query which is faster
            else{
                self::$statement = self::$link->query($query);
            }
            return self::$statement;
        } catch (\PDOException $e) {
            error_reporting("Error!: " . $e->getMessage() . "<br/>", E_USER_ERROR);
        }
    }
    


    /**
    * Get one field
    *
    * @param string $query
    * @param array $field
    * @return string
    */
    public static function getField($query = null, $field = array())
    {
        if( $query )
            self::query($query, $field);
        return self::$statement->fetchColumn(0);
    }



    /**
    * Get one row
    *
    * @param string $query
    * @param array $field
    * @return array
    */
    public static function getRow($query = null, $field = array())
    {
        if( $query )
            self::query($query, $field);
        return self::$statement->fetch(self::$conf['fetch_mode']);
    }
    
    

    /**
    * Get the result of a query in an Iterator, example:
    *
    * db::get_all("SELECT * FROM user")  => array(array('id'=>23,'name'=>'tim'),array('id'=>43,'name'=>'max') ... )
    * db::get_all("SELECT * FROM user","id")  => array(23=>array('id'=>23,'name'=>'tim'),42=>array('id'=>43,'name'=>'max') ... )
    * db::get_all("SELECT * FROM user","id","name")  => array(23=>'tim'),42=>'max' ...)
    *
    * @param string $query
    * @param string $key
    * @param string $value
    * @param array $field
    * @return array of array
    */
    public static function getAll( $query = null, $field = array(), $key = null, $value = null ) {
        $rows = array();
        if( $query )
            self::query($query, $field);

        // return the iterator with the values
        return new \Rain\DB\StatementIterator( self::$statement, self::$conf['fetch_mode'], $key, $value );
    }
    



    /**
    * Get the result of a query in an Array, example:
    *
    * db::get_all("SELECT * FROM user")  => array(array('id'=>23,'name'=>'tim'),array('id'=>43,'name'=>'max') ... )
    * db::get_all("SELECT * FROM user","id")  => array(23=>array('id'=>23,'name'=>'tim'),42=>array('id'=>43,'name'=>'max') ... )
    * db::get_all("SELECT * FROM user","id","name")  => array(23=>'tim'),42=>'max' ...)
    *
    * @param string $query
    * @param string $key
    * @param string $value
    * @param array $field
    * @return array of array
    */
    public static function getAllArray( $query = null, $field = array(), $key = null, $value = null ) {
        $rows = array();
        if( $query )
            self::query($query, $field);

        // If not it will return the entire results as an Array
        if ($result = self::$statement->fetchALL(self::$conf['fetch_mode'])) {
            // return the array
            if (!$key){
                return $result;
            } elseif (!$value){
                foreach ($result as $row){
                    $rows[$row[$key]] = $row;
                }
            } else {
                
                // if the result is an array
                if( \PDO::FETCH_ASSOC == self::$conf['fetch_mode'] ){
                    foreach ($result as $row){
                        $rows[$row[$key]] = $row[$value];
                    }
                }
                // fetch mode object
                elseif( \PDO::FETCH_OBJ == self::$conf['fetch_mode'] ){
                    foreach ($result as $row){
                        $rows[$row->$key] = $row->$value;
                    }
                }
                else{
                    // Fetch Mode not recognized exception
                    throw new \Rain\DB\Exception\FetchModeException("Fetch Mode not supported by getAll");
                }
                
            }
        }

        return $rows;
    }
    
    

    /**
     * Get the last inserted id of an insert query
     */
    public static function getLastId()
    {
        return self::$link->lastInsertId();
    }

    
    
    /**
     * Return the last query executed
     */
    public static function getLastQuery()
    {
        return self::$last_query;
    }
    
    /**
     * Return the number of executed query
     */
    public static function getExecutedQuery()
    {
        return self::$nquery;
    }



    /**
     * Connect to the database
     */
    public static function setup($string, $username, $password, $name)
    {
        try {
            self::$link = new \PDO($string, $username, $password);
            self::$link->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
            self::$link_list[$name] = self::$link;  
            return true;
        } catch (\PDOException $e) {
            die("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    
    
    /**
     * Configure the settings
     */
    public static function configure($setting, $value = null)
    {
        if (is_array($setting)){
            foreach ($setting as $key => $value)
                static::configure($key, $value);
        } else if (isset(static::$conf[$setting])) {
            static::$conf[$setting] = $value;
        }
    }

    /**
     * Close PDO connection
     * execute this method to close the connection with the selected database
     * and bear in mind that PHP Garbage Collector will close the connection anyway when the script end
     * 
     */
    public static function disconnect() {
        unset(self::$link);
    }
}
