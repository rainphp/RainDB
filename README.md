#Rain DB component

Rain DB is a component of the RainFramework 3 that works as wrapper for the PDO class.

## Usage

This class use Static methods to run the queries. Example:
``` php
use Rain\DB;

// include the autoloader
require "library/Rain/autoload.php";

// configure the class
$config = array("config_dir"=>"config/");
DB::configure( $config );

// init the connection
DB::init();

// simple query
DB::getRow("SELECT * FROM user LIMIT 1");

// query with prepared statement to filter the input
DB::getField("SELECT firstname FROM user WHERE user_id=:user_id", array(":user_id"=>$user_id) );
```

### Installation

You can copy this library in your project using composer.

After that you need to configure your connection

### Configure connection

This class accepts `mysql`, `pgsql`, `sqlite`, `oracle` and `odbc` drivers.

The one that the class uses is defined in the configuration file which by default is `config/db.php`, before using the class you need to edit this file and store your database user, password, etc.


#### Loading and initializate the library.

To load the class file you can use composer or a PSR autoloader.

After configurating the class you can initializate the library using the init method

```php
use Rain\DB;
require "library/Rain/autload.php";
DB::init();
```
