<?php
include_once 'include/functions.php';
$getPrayerGroupList = "";
$cmd = $_REQUEST['cmd'];
if($cmd == "List") {
	try {
		$draw = $_REQUEST['draw'];
		$row = $_REQUEST['start'];
		$rowPerPage = $_POST['length']; // Rows display per page
		$columnIndex = $_POST['order'][0]['column']; // Column index
		$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
		$searchValue = $_POST['search']['value']; // Search value	
		$UserID = $_POST['UserID'];
		$getUserList = getUserList($searchValue, $row, $rowPerPage, $columnName, $columnSortOrder, $draw, $UserID);
		echo $getUserList;	
		
	} catch (PDOException $e) {
		echo "There is some problem in connection: " . $e->getMessage();
	}
} else if($cmd == "Add") {
	$errorArray = array();
	$Name = $_REQUEST['Name'];
	$Email = $_REQUEST['Email'];
	$Phone = $_REQUEST['Phone'];
	$City = $_REQUEST['City'];
	
	$alphachracters = '/^[A-z a-z.]+$/';
	if(empty($Name)) {
	 	$errorArray += ["name" => "Please enter user name"];
	}
	if(!empty($Name) && !preg_match($alphachracters, $Name)) {
	 	$errorArray += ["name" => "Please enter user name in characters only"];
	}
	if(empty($Email)) {
		$errorArray += ["email" => "Please enter email"];
	}
	if(!empty($Email) && !filter_var($Email, FILTER_VALIDATE_EMAIL)) {
		$errorArray += ["email" => "Please enter email in email format"];
	}
	if(empty($City)) {
		$errorArray += ["dob" => "Please enter city"];
	}
        if(!empty($City) && !preg_match($alphachracters, $City)) {
	 	$errorArray += ["name" => "Please enter city name in characters only"];
	}
	if(empty($Phone)) {
		$errorArray += ["phone" => "Please enter contact number"];
	}
	if(!empty($Phone) && !is_numeric($Phone)) {
		$errorArray += ["phone" => "Please enter contact number in numbers only"];
	}
	if(!empty($Phone) && strlen($Phone) !== 10) {
		$errorArray += ["phone" => "Please enter contact number in 10 digits only"];
	}
	if(count($errorArray) != 0) {
	 $resultArray = array("success" => false, "errors" => true, "errorData" => $errorArray);
	 echo json_encode($resultArray);
	 exit();
	}
	$checkDuplicate = getUserDataByCondition(array("=", "="), array("where", "OR"), array("Email" => $Email, "Phone" => $Phone), "boolean");
	if($checkDuplicate) {
		$errorArray += ["name" => "Entered data is already exists"];
		$resultArray = array("success" => false, "errors" => true, "errorData" => $errorArray);
		echo json_encode($resultArray);
		exit();	
	} else {
		$addData = addUser($Name, $Email, $Phone, $City);	
		echo $addData;
	}
} else if($cmd == "Edit") {
	$errorArray = array();
	$userId = $_REQUEST['userId'];
	$editData = getUserData($userId);	
	echo $editData;
	
} else if($cmd == "Update") {
	$errorArray = array();
	$UserID = $_REQUEST['UserID'];
	$Name = $_REQUEST['Name'];
	$Email = $_REQUEST['Email'];
	$Phone = $_REQUEST['Phone'];
	$City = $_REQUEST['City'];
	$alphachracters = '/^[A-z a-z.]+$/';
	if(empty($Name)) {
	 	//array_push($errorArray, ["booking_user_name" => "Please enter user name"]);
	 	$errorArray += ["name" => "Please enter user name"];
	}
	if(!empty($Name) && !preg_match($alphachracters, $Name)) {
	 	//array_push($errorArray, ["booking_user_name" => "Please enter user name"]);
	 	$errorArray += ["name" => "Please enter user name in characters only"];
	}
	if(empty($Email)) {
	//array_push($errorArray, ["booking_user_password" => "Please enter password"]);
		$errorArray += ["email" => "Please enter email"];
	}
	if(!empty($Email) && !filter_var($Email, FILTER_VALIDATE_EMAIL)) {
	//array_push($errorArray, ["booking_user_password" => "Please enter password"]);
		$errorArray += ["email" => "Please enter email in email format"];
	}
	if(empty($City)) {
		$errorArray += ["dob" => "Please enter city"];
	}
        if(!empty($City) && !preg_match($alphachracters, $City)) {
	 	$errorArray += ["name" => "Please enter city name in characters only"];
	}
	if(empty($Phone)) {
	//array_push($errorArray, ["booking_user_password" => "Please enter password"]);
		$errorArray += ["phone" => "Please enter contact number"];
	}
	if(!empty($Phone) && !is_numeric($Phone)) {
	//array_push($errorArray, ["booking_user_password" => "Please enter password"]);
		$errorArray += ["phone" => "Please enter contact number in numbers only"];
	}
	if(!empty($Phone) && strlen($Phone) !== 10) {
	//array_push($errorArray, ["booking_user_password" => "Please enter password"]);
		$errorArray += ["phone" => "Please enter contact number in 10 digits only"];
	}
	if(count($errorArray) != 0) {
	 //$errorDataArray = 
	 $resultArray = array("success" => false, "errors" => true, "errorData" => $errorArray);
	 echo json_encode($resultArray);
	 exit();
	}
	$checkDuplicate = getUserDataByCondition(array("<>","=", "="), array("where", "AND", "OR"), array("UserID" => $UserID, "Email" => $Email, "Phone" => $Phone), "boolean");
	if(!empty($checkDuplicate -> data)) {
		$errorArray += ["name" => "Entered data is already exists"];
		$resultArray = array("success" => false, "errors" => true, "errorData" => $errorArray);
		echo json_encode($resultArray);
		exit();	
	} else {
		$updateData = updateUser($UserID, $Name, $Email, $Phone, $City);	
		echo $updateData;
	}
} else if($cmd == "Delete") {
	$resultArray = array();
	$flag = false;
	$UserID = $_REQUEST['userId'];
	if(!deleteUser($UserID)) {
		$flag = true;			
	}
	if($flag == true) {
		$resultArray["message"] = "Some error oocured";
		$resultArray["errors"] = true;
	} else {
		$resultArray["message"] = "Record deleted successfully";
		$resultArray["errors"] = false;
	}
	echo json_encode($resultArray);
}

?>