<?php
	include_once 'connection.php';
	
	
	function getUserList($searchValue, $row, $rowPerPage, $columnName, $columnSortOrder, $draw, $UserID) {
		try {
			$database = new Connection();
			$db = $database -> openConnectionAfterDatabaseCreated();
			$resultArray = array();
			$searchArray = array();
			$searchQuery = " ";
			if($searchValue != ''){
			   $searchQuery .= " AND (Name LIKE :Name OR 
					Email LIKE :Email OR 
					City LIKE :Sex OR
					Phone LIKE :PHONE ) ";
					
			   $searchArray = array( 
					'Name'=>"%$searchValue%", 
					'Email'=>"%$searchValue%",
					'City'=>"%$searchValue%",
					'PHONE'=>"%$searchValue%"
			   );
			  
			}
			
			if(!empty($UserID)) {
				$searchQuery .= " AND UserID <> :UserID";	
			}
			//echo $searchQuery.$UserID;
			## Total number of records without filtering
			$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM users ");
			$stmt->execute();
			$records = $stmt->fetch();
			$totalRecords = $records['allcount'];
			/*if(!empty($UserID)) {
				$sql = "SELECT COUNT(*) AS allcount FROM musers WHERE 1 ".$searchQuery;
				//echo $sql;
			}*/
			## Total number of records with filtering
			$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM users WHERE 1 ".$searchQuery);
			foreach($searchArray as $key=>$search){
			//echo "count";
			   $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
			}
			if(!empty($UserID)) {
				//echo "hi";
				$stmt->bindValue(':UserID', $UserID, PDO::PARAM_INT);	
			}
			$stmt->execute();
			$records = $stmt->fetch();
			$totalRecordwithFilter = $records['allcount'];
			//echo "hiiii";
			## Fetch records
			$stmt = $db->prepare("SELECT * FROM users WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");
			
			// Bind values
			foreach($searchArray as $key=>$search){
			   $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
			}
			
			if(!empty($UserID)) {
			
				$stmt->bindValue(':UserID', (int)$UserID,PDO::PARAM_INT);	
			}
			
			$stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
			$stmt->bindValue(':offset', (int)$rowPerPage, PDO::PARAM_INT);
			$stmt->execute();
			$userRecords = $stmt->fetchAll();
			
			$data = array();
			
			foreach($userRecords as $row){
			   $data[] = array(
			   	  'UserID' => $row["UserID"],
				  "Name"=>$row['Name'],
				  "Email"=>$row['Email'],
				  "City"=>$row['City'],
				  "Phone"=>$row['Phone']
			   );
			}
			
			## Response
			$response = array(
			   "draw" => intval($draw),
			   "iTotalRecords" => $totalRecords,
			   "iTotalDisplayRecords" => $totalRecordwithFilter,
			   "aaData" => $data
			);
			$database -> closeConnection();
			return json_encode($response);
			
		} catch (PDOException $e) {
    		//echo "There is some problem in connection: " . $e->getMessage();
			$resultArray = ["success" => false, "errors" => false, "message" => $e->getMessage()];	
			return json_encode($resultArray);
		}		
	}
	
		
	function getUserData($userId) {
		try {
			$database = new Connection();
			$db = $database -> openConnectionAfterDatabaseCreated();
			$resultArray = array();
			$userId = $userId;
			$sqlStatement = "select * from users where UserID = :UserID";
			$stm = $db -> prepare($sqlStatement);
			$stm -> bindValue(':UserID', $userId, PDO::PARAM_INT);
			$stm -> execute();
			if($stm -> rowCount() > 0) {
				$result = $stm -> fetch();
				$resultArray = ["success" => true, "errors" => false, "data" => $result];				
			} else {
				$resultArray = ["success" => false, "errors" => false, "data" => ""];	
			}
			$database -> closeConnection();
			return json_encode($resultArray);
			
		} catch (PDOException $e) {
    		//echo "There is some problem in connection: " . $e->getMessage();
			$resultArray = ["success" => false, "errors" => false, "message" => $e->getMessage()];	
			return json_encode($resultArray);
		}		
	}
	
	function getUserDataByCondition($operators = array(), $conditions = array(), $fields = array(), $returnType) {
		try {
			$database = new Connection();
			$db = $database -> openConnectionAfterDatabaseCreated();
			$resultArray = array();
			$x = 0;
			
			$sqlStatement = "select * from users ";
			foreach($fields as $key => $data) {
			//$y = $x - 1;
			
				if(!is_array($data)) {
					$condition = $conditions[$x];
					$operator = $operators[$x];
					if($condition == "AND" && isset($conditions[$x + 1]) && $conditions[$x + 1] == "OR") {
						$sqlStatement .= " ".$condition." (".$key." ".$operator." :".$key;
					} else {
						$sqlStatement .= " ".$condition." ".$key." ".$operator." :".$key;	
					}
					
					
				} else {
					$j = $x;
					$y = 0;
					foreach($data as $datas) {
						$condition = $conditions[$j];
						$operator = $operators[$j];
						$sqlStatement .= " ".$condition." ".$key." ".$operator." :".$key.$y;
						//$sqlStatement .= " ".$conditions[$j]." ".$key." ".$operators[$j]." :".$key;
						$y++;
					}						
				}
				if($condition == "OR" && isset($conditions[$x - 1]) && $conditions[$x - 1] == "AND") {
						$sqlStatement .=")";						
				}
				$x++;				
			}
			//echo $sqlStatement;
			$stm = $db -> prepare($sqlStatement);
			foreach($fields as $key => $data) {
				if(!is_array($data)) {
					$stm -> bindValue(':'.$key, $data);	
				} else {
					$j = 0;
					foreach($data as $datas) {
						$stm -> bindValue(':'.$key.$j, $datas);
						$j++;		
					}		
				}
			}
			$stm -> execute();
			if($stm -> rowCount() > 0) {
				if($returnType == "boolean") {
					return true;
				} else {
					if($returnType == "fetchAll") {
						$result = $stm -> fetchAll();
					} else if($returnType == "fetch") {
						$result = $stm -> fetch();	
					}
					$resultArray = ["success" => true, "errors" => false, "data" => $result];
					$database -> closeConnection();
					return json_encode($resultArray);
				}				
			} else {
				if($returnType == "boolean") {
					return false;
				} else {
					$resultArray = ["success" => false, "errors" => false, "data" => ""];
				}
				$database -> closeConnection();
				return json_encode($resultArray);	
			}
			
		} catch (PDOException $e) {
    		//echo "There is some problem in connection: " . $e -> getMessage();
			$resultArray = ["success" => false, "errors" => false, "message" => $e->getMessage()];	
			return json_encode($resultArray);
		}		
	}
	
	function addUser($Name, $Email, $Phone, $City) {
		try {
			$database = new Connection();
			$db = $database -> openConnectionAfterDatabaseCreated();
			$resultArray = array();
			$Name = $Name;
			$Email = $Email;
			$Phone = $Phone;
			$City = $City;
			$sqlStatement = "insert into users set Name = :Name, Email = :Email, Phone = :Phone, City = :City, CreatedDate = :CreatedDate, ModifiedDate = :ModifiedDate";
			$stm = $db -> prepare($sqlStatement);
			$stm -> bindValue(':Name', $Name, PDO::PARAM_STR);
			$stm -> bindValue(':Email', $Email, PDO::PARAM_STR);
			$stm -> bindValue(':Phone', $Phone, PDO::PARAM_STR);
			$stm -> bindValue(':City', $City, PDO::PARAM_STR);
			
			$stm -> bindValue(':CreatedDate', date("Y-m-d"), PDO::PARAM_STR);
			$stm -> bindValue(':ModifiedDate', date("Y-m-d"), PDO::PARAM_STR);
			$stm -> execute();
			if($stm -> rowCount() > 0) {
				$resultArray = ["success" => true, "errors" => false];
				$database -> closeConnection();
				return json_encode($resultArray);
			} else {
				$resultArray = ["success" => false, "errors" => false, "message" => "Some Error Occured"];	
				$database -> closeConnection();
				return json_encode($resultArray);				
			}	
			
		} catch (PDOException $e) {
    		//echo "There is some problem in connection: " . $e->getMessage();
			$resultArray = ["success" => false, "errors" => false, "message" => $e->getMessage()];	
			return json_encode($resultArray);
		}			
	}
	
	function updateUser($UserID, $Name, $Email, $Phone, $City) {
		try {
			$database = new Connection();
			$db = $database -> openConnectionAfterDatabaseCreated();
			$resultArray = array();
			$Name = $Name;
			$Email = $Email;
			$Phone = $Phone;
			$City = $City;
			$UserID = $UserID;
			$sqlStatement = "update users set Name = :Name, Email = :Email, Phone = :Phone, City = :City, ModifiedDate = :ModifiedDate where UserID = :UserID";
			$stm = $db -> prepare($sqlStatement);
			$stm -> bindValue(':Name', $Name, PDO::PARAM_STR);
			$stm -> bindValue(':Email', $Email, PDO::PARAM_STR);
			$stm -> bindValue(':Phone', $Phone, PDO::PARAM_STR);
			$stm -> bindValue(':City', $City, PDO::PARAM_STR);
			$stm -> bindValue(':ModifiedDate', date("Y-m-d"), PDO::PARAM_STR);
			$stm -> bindValue(':UserID',$UserID, PDO::PARAM_INT);
			$stm -> execute();
			if($stm -> rowCount() > 0) {
				$resultArray = ["success" => true, "errors" => false];
				$database -> closeConnection();
				return json_encode($resultArray);
			} else {
				$resultArray = ["success" => false, "errors" => false, "message" => "No Changes Done"];	
				$database -> closeConnection();
				return json_encode($resultArray);				
			}	
			
		} catch (PDOException $e) {
    		//echo "There is some problem in connection: " . $e->getMessage();
			$resultArray = ["success" => false, "errors" => false, "message" => $e->getMessage()];	
			return json_encode($resultArray);
		}			
	}
	
	function deleteUser($UserID) {
		try {
			$database = new Connection();
			$db = $database -> openConnectionAfterDatabaseCreated();
			$UserID = $UserID;
			$sqlStatement = "delete from users where UserID = :UserID";
			$stm = $db -> prepare($sqlStatement);
			$stm -> bindValue(':UserID', $UserID, PDO::PARAM_INT);
			$stm -> execute();
			if($stm -> rowCount() > 0) {
				return true;
			}
			return false;
		} catch (PDOException $e) {
    		//echo "There is some problem in connection: " . $e->getMessage();
			$resultArray = ["success" => false, "errors" => false, "message" => $e->getMessage()];	
			return json_encode($resultArray);
		}						
	}	
	
?>