<?php
Class Connection {
		private  $server = "mysql:host=localhost";
		private  $user = "root1";
		private  $pass = "root12345";
		private $database = "userinfo";
		private  $serverWithDatabase = "mysql:host=localhost;dbname=userinfo";
		private $options  = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,);
		protected $con;
		 
		public function openConnection() {
			try {
			  $this->con = new PDO($this->server, $this->user,$this->pass,$this->options);
			  return $this->con;
			} catch (PDOException $e) {
				echo "There is some problem in connection: " . $e->getMessage();
			}
		}
		
		public function openConnectionAfterDatabaseCreated() {
			try {
			  $this->con = new PDO($this->serverWithDatabase, $this->user,$this->pass,$this->options);
			  return $this->con;
			} catch (PDOException $e) {
				echo "There is some problem in connection: " . $e->getMessage();
			}
		}
		
		public function closeConnection() {
			 $this->con = null;
		}
		
		public function createDatabase() {
			try {
				$conn = $this -> openConnection();
				$createDatabase = $conn -> exec("CREATE DATABASE ".$this -> database.";");
				if($createDatabase) {
					echo "Database Created Successfully";
				} else {
					echo "Some problem is coming in creating database";
				}
			} catch (PDOException $e) {
				echo "There is some problem in connection: " . $e->getMessage();
			}
			
		}
		
		public function createTable() {
			try {
				$conn = $this -> openConnectionAfterDatabaseCreated();
				$sqlStatement = "CREATE table users (UserID INT(10) AUTO_INCREMENT PRIMARY KEY, Name VARCHAR(50), Email VARCHAR(100), Phone BIGINT(10), City VARCHAR(50), CreatedDate Date, ModifiedDate Date);";
				//echo $sqlStatement;
				$createTable = $conn -> query($sqlStatement);
				if($createTable) {
					echo "Table Created Successfully";
				} else {
					echo "Some problem is coming in creating table";
				}
			} catch (PDOException $e) {
				echo "There is some problem in connection: " . $e->getMessage();
			}
			
		}
		
    }

?>