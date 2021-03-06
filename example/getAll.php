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
    // DB::getAll
    // ----------
    // It execute a query and return an Iterator to get each rows of the result.
    // This method is really handy to be used together with a template engine,
    // for example you can get the entire list of users and print them in a template.
    // Because it return an Iterator the performance of DB::getAll are basically the 
    // same as executing a query and fetch each row in a loop.
    //
    $query = "SELECT CONCAT( u.firstname, ' ', u.lastname ) AS username, g.name AS `group`
                FROM user u
                JOIN user_in_group ug ON u.user_id=ug.user_id
                JOIN `group` g ON g.group_id=ug.group_id
                ORDER BY g.group_id
                LIMIT 10";

    $iterator = DB::getAll($query);

    echo "<pre>-----------------------
Iterator result
-----------------------</pre>";
    foreach ($iterator as $row) {
        var_dump($row);
    }