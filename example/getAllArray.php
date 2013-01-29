<?php

    // base folder
    define("BASE_DIR", dirname(__DIR__));



    // Autoloader
    // ----------
    // 
    // All Rain class are loaded with the autoloader.
    // If you install RainForm with composer you want
    // to include the autoloader of composer which 
    // usually is "vendor/autoload.php"
    //
    require BASE_DIR . "/library/Rain/autoload.php";


    // use the namespace Rain
    use Rain\DB;


    // 
    // set the configuration
    //
    $config = array(
        "config_dir" => BASE_DIR . "/config/", // set the configuration folder
        "fetch_mode" => \PDO::FETCH_OBJ          // set the fetch mode as object
    );

    DB::configure($config);


    // init the database class
    DB::init();


    //
    // DB::getAllArray
    // ----------
    // It execute a query and return the result as an Array,
    // this function is really handy to assign the value to a template.
    // getAllArray retrieve the entire result all together, if you need to get
    // only a few rows, getAll is probably better in term of performance.
    //
    $query = "SELECT CONCAT( u.firstname, ' ', u.lastname ) AS username, g.name AS `group`
                FROM user u
                JOIN user_in_group ug ON u.user_id=ug.user_id
                JOIN `group` g ON g.group_id=ug.group_id
                ORDER BY g.group_id
                LIMIT 10";

    $list = DB::getAllArray($query);

    echo "<pre>-----------------------
Iterator result
-----------------------</pre>";
    var_dump($list);