<?php 
   include "Redis.php";
   //Connecting to Redis server on localhost 
   $conn = Redis_Lib::connect(); 
   echo "Connection to server sucessfully"; 
   //set the data in redis string 
   $conn->redis->set("tutorial-name", "Redis tutorial"); 
   // Get the stored data and print it 
   echo "<br/>Stored string in redis:: " .$conn->redis->get("tutorial-name"); 
   print_r($conn->redis->keys('*'));
   $conn = Redis_Lib::connect(); 
   $conn = Redis_Lib::connect();
   
	function getData($query, $table = 'table1', $identifier = '123'){
		$response = false;
		if(REDIS_ENABLED){
			$key = "_rs_".$table."_".$identifier;
			Redis_Lib::connect();
			if($conn->redis->exists($key)){
				$response = $conn->redis->get($key);
			}else{
				$response = mysql_query($query);
				$response = $conn->redis->set($key, $response);
			}
		}else{
			$response = mysql_query($query);
		}
		return $response;
	}
?>
