<?php
session_start();
ini_set('display_errors', 1);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".$password."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
				return 1;
		}else{
			return 3;
		}
	}
	function login2(){
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where username = '".$email."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
				return 1;
		}else{
			return 3;
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function logout2(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		$data .= ", password = '$password' ";
		$data .= ", type = '$type' ";
		if (empty($name) || empty($username) || empty($password) || empty($type)){
			return 0;
		}
		else if(empty($id)){
			$save = $this->db->query("INSERT INTO users set ".$data);
			if($save)
				return 1;
		}else{
			$save = $this->db->query("UPDATE users set ".$data." where id = ".$id);
			if($save)
				return 2;
		}
	}
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	
	function save_loan_type(){
		extract($_POST);
		$data = " type_name = '$type_name' ";
		$data .= " , description = '$description' ";
		if(empty($type_name) || empty($description)) {
			return 0;
		}
		else if(empty($id)){
			$save = $this->db->query("INSERT INTO loan_types set ".$data);
			if($save)
				return 1;
		}else{
			$save = $this->db->query("UPDATE loan_types set ".$data." where id=".$id);
			if($save)
				return 2;
		}
	}
	function delete_loan_type(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM loan_types where id = ".$id);
		if($delete)
			return 1;
	}
	function save_plan(){
		extract($_POST);
		$data = " days = '$days' ";
		$data .= ", interest_percentage = '$interest_percentage' ";
		$data .= ", penalty_rate = '$penalty_rate' ";
		if(empty($days) || empty($interest_percentage)){
			return 0;
		}
		else if(empty($id)){
			$save = $this->db->query("INSERT INTO loan_plan set ".$data);
			if($save)
				return 1;
		}else{
			$save = $this->db->query("UPDATE loan_plan set ".$data." where id=".$id);
			if($save)
				return 2;
		}
		
	}
	function delete_plan(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM loan_plan where id = ".$id);
		if($delete)
			return 1;
	}
	function save_borrower(){
		extract($_POST);
		$data = " firstname = '$firstname' ";
		$data .= ", lastname = '$lastname' ";
		$data .= ", address = '$address' ";
		$data .= ", contact_no = '$contact_no' ";
		$data .= ", email = '$email' ";
		$data .= ", aadhaar = '$aadhaar' ";
		$data .= ", pan = '$pan' ";
		if(empty($firstname) || empty($address) || empty($contact_no) || empty($aadhaar) || empty($pan)){
			return 0;
		}
		else if(empty($id)){
			$save = $this->db->query("INSERT INTO borrowers set ".$data);
			if($save)
				return 1;
		}else{
			$save = $this->db->query("UPDATE borrowers set ".$data." where id=".$id);
			if($save)
				return 2;
		}
	}
	function delete_borrower(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM borrowers where id = ".$id);
		if($delete)
			return 1;
	}

	function save_loan(){
		extract($_POST);
			$data = " borrower_id = $borrower_id ";
			$data .= " , loan_type_id = '$loan_type_id' ";
			$data .= " , plan_id = '$plan_id' ";
			$data .= " , amount = '$amount' ";
			$data .= " , daily_amount = '$daily_amount' ";
			$data .= " , purpose = '$purpose' ";
			if(isset($status)){
				$data .= " , status = '$status' ";
				if($status == 2){
					$plan = $this->db->query("SELECT * FROM loan_plan where id = $plan_id ")->fetch_array();
					for($i= 1; $i <= $plan['days'];$i++){
						$date = date("Y-m-d",strtotime(date("Y-m-d")." +".$i." days"));
					$chk = $this->db->query("SELECT * FROM loan_schedules where loan_id = $id and date(date_due) ='$date'  ");
					if($chk->num_rows > 0){
						$ls_id = $chk->fetch_array()['id'];
						$this->db->query("UPDATE loan_schedules set loan_id = $id, date_due ='$date' where id = $ls_id ");
					}else{
						$this->db->query("INSERT INTO loan_schedules set loan_id = $id, date_due ='$date' ");
						$ls_id = $this->db->insert_id;
					}
					$sid[] = $ls_id;
					}
					$sid = implode(",",$sid);
					$this->db->query("DELETE FROM loan_schedules where loan_id = $id and id not in ($sid) ");
				$data .= " , date_released = '".date("Y-m-d H:i")."' ";

				}else{
					$chk = $this->db->query("SELECT * FROM loan_schedules where loan_id = $id")->num_rows;
					if($chk > 0){
						$thi->db->query("DELETE FROM loan_schedules where loan_id = $id ");
					}

				}
			}
			if(empty($id)){
				$ref_no = mt_rand(1,99999999);
				$i= 1;

				while($i== 1){
					$check = $this->db->query("SELECT * FROM loan_list where ref_no ='$ref_no' ")->num_rows;
					if($check > 0){
					$ref_no = mt_rand(1,99999999);
					}else{
						$i = 0;
					}
				}
				$data .= " , ref_no = '$ref_no' ";
			}
			if(empty($borrower_id) || empty($loan_type_id) || empty($plan_id) || empty($amount) || empty($daily_amount)) {
				return 0;
			}
			else if(empty($id)){
				$save = $this->db->query("INSERT INTO loan_list set ".$data);
				if($save)
					return 1;
			}
			else{
				$save = $this->db->query("UPDATE loan_list set ".$data." where id=".$id);
				if($save)
					return 2;
			}
				
		
	}
	function delete_loan(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM loan_list where id = ".$id);
		if($delete)
			return 1;
	}
	function save_payment(){
		
		extract($_POST);
			$data = " loan_id = $loan_id ";
			$data .= " , payee = '$payee' ";
			$data .= " , amount = '$amount' ";
			$data .= " , penalty_amount = '$penalty_amount' ";
			$data .= " , overdue = '$overdue' ";
			$data .= " , collect_by = '$collect_by' ";
		if(empty($loan_id) || empty($payee) || empty($amount) || empty($collect_by)){
			return 0;
		}
		else if(empty($id)){
			$save = $this->db->query("INSERT INTO payments set ".$data);
			if($save)
				return 1;
		}
		else{
			$save = $this->db->query("UPDATE payments set ".$data." where id = ".$id);
			if($save)
				return 2;
		}

	}
	function delete_payment(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM payments where id = ".$id);
		if($delete)
			return 1;
	}

	function save_file_charge(){
		
		extract($_POST);
			$data = " loan_id = $loan_id ";
			$data .= " , payee = '$payee' ";
			$data .= " , amount = '$amount' ";
			$data .= " , collect_by = '$collect_by' ";
		if(empty($loan_id) || empty($payee) || empty($amount) || empty($collect_by)){
			return 0;
		}
		else if(empty($id)){
			$save = $this->db->query("INSERT INTO file_charges set ".$data);
			if($save)
				return 1;
		}else{
			$save = $this->db->query("UPDATE file_charges set ".$data." where id = ".$id);
			if($save)
				return 2;
		}

	}
	function delete_file_charge(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM file_charges where id = ".$id);
		if($delete)
			return 1;
	}

	function save_expenditure(){
		extract($_POST);
		$data = " user = '$user' ";
		$data .= ", purpose = '$purpose' ";
		$data .= ", amount = '$amount' ";
		if(empty($user) || empty($purpose) || empty($amount)){
			return 0;
		}
		else if(empty($id)){
			$save = $this->db->query("INSERT INTO expenditure set ".$data);
			if($save)
				return 1;
		}else{
			$save = $this->db->query("UPDATE expenditure set ".$data." where id=".$id);
			if($save)
				return 2;
		}
		
	}
	function delete_expenditure(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM expenditure where id = ".$id);
		if($delete)
			return 1;
	}

}