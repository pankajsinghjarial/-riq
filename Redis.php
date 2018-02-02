<?php
class Redis_Lib {
     
    private $host = null, $port = null;
    private static $instance = null;
     
    private function __construct() {
         
        // Please note that this is Private Constructor
         
        $this->host = '127.0.0.1';
        $this->port = 6379;
 
        // Your Code here to connect to database //
        $this->redis = new Redis(); 
        $this->redis->connect($this->host, $this->port);
    }
     
    public static function connect() {
         
        // Check if instance is already exists      
        if(self::$instance == null) {
            self::$instance = new Redis_Lib();
        }

        return self::$instance;
         
    }
	
}
