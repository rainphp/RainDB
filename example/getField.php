<?php

    // base folder
    define("BASE_DIR", dirname(__DIR__) );


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
                     "config_dir"=> BASE_DIR . "/config/",  // set the configuration folder
                     "fetch_mode"=>\PDO::FETCH_OBJ          // set the fetch mode as object
                   );

    DB::configure( $config );
    
    
    // init the database class
    DB::init();
    

    // getField
    $field = DB::getField("SELECT firstname FROM user WHERE user_id=:user_id", array(":user_id"=>1) );
    var_dump( $field );