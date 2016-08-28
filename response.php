<?php
	include("connection.php");
	$db = new dbObj();
	$connString =  $db->getConnstring();

	$params = $_REQUEST;
	$action = isset($params['action']) != '' ? $params['action'] : '';
	$empCls = new Employee($connString);

	switch($action) {
	 case 'add':
		$empCls->insertEmployee($params);
	 break;
	 case 'edit':
		$empCls->updateEmployee($params);
	 break;
	 case 'delete':
		$empCls->deleteEmployee($params);
	 break;
	 default:
	 $empCls->getEmployees($params);
	 return;
	}
	class Employee {
	protected $conn;
	protected $data = array();
	function __construct($connString) {
		$this->conn = $connString;
	}
	
	public function getEmployees($params) {
		$this->data = $this->getRecords($params);
		
		echo json_encode($this->data);
	}
	function insertEmployee($params) {
		$data = array();;
		$sql = "INSERT INTO `employee` (employee_name, employee_salary, employee_age) VALUES('" . $params["name"] . "', '" . $params["salary"] . "','" . $params["age"] . "');  ";
		
		echo $result = mysqli_query($this->conn, $sql) or die("error to insert employee data");
		
	}
	
	function getRecords() {
		$page = isset($_POST['page']) ? $_POST['page'] : 1;
		$rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
		$sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'name';
		$sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
		$query = isset($_POST['query']) ? $_POST['query'] : false;
		$qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
		
		$sql = "SELECT * FROM `employee` order by ".$sortname." ".$sortorder." LIMIT ". $page*$rp .",".$rp;
		$sqlTot = "SELECT * FROM `employee`";
		$qtot = mysqli_query($this->conn, $sqlTot) or die("error to fetch tot employees data");
		$queryRecords = mysqli_query($this->conn, $sql) or die("error to fetch employees data");
		
		while( $row = mysqli_fetch_assoc($queryRecords) ) { 
			$data[] = $row;
			//echo "<pre>";print_R($data);die;
		}
		$json_data = array(
			"page"            => $page,   
			"total"    => intval($qtot->num_rows),
			"rows"            => $data   // total data array
		);
		
		return $json_data;
	}
	function updateEmployee($params) {
		$data = array();
		//print_R($_POST);die;
		$sql = "Update `employee` set employee_name = '" . $params["edit_name"] . "', employee_salary='" . $params["edit_salary"]."', employee_age='" . $params["edit_age"] . "' WHERE id='".$_POST["edit_id"]."'";
		
		echo $result = mysqli_query($this->conn, $sql) or die("error to update employee data");
	}
	
	function deleteEmployee($params) {
		$data = array();
		//print_R($_POST);die;
		$sql = "delete from `employee` WHERE id='".$params["id"]."'";
		
		echo $result = mysqli_query($this->conn, $sql) or die("error to delete employee data");
	}
}
?>
	