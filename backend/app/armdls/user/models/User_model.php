<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model{
// Read Query
public function read($id){
	// check if $id param is null
	if($id===NULL){
		$replace = "" ;
	}
	else{
		$replace = "=$id";
	}
	// query get data $id data from database
	$query = $this->db->query("select * from user where u_id".$replace);
	// return $query as array
	return $query->result_array();
}
// Insert/Create Query
public function insert($data){
	// query inserting new data to database
	$this->db->insert('user', $data);
	return TRUE;
}
// Delete Query
public function delete($id){
	// query delete $id data from database
	$query = $this->db->query("delete from user where u_id=$id");
	return TRUE;
}
// Update Query
public function update($data){
	// set $id from $data array
	$id= $data['u_id'];
	// query update $id data from database
	$this->db->where('u_id',$id);
	$query = $this->db->update('user',$data);
	// check if query return true
	if ($query) {
		return TRUE;
	}
	else{
		return FALSE;
	}
}
// check existing username
public function check_username($username){
	if($username===NULL){
		$replace = "" ;
	}
	else{
		$replace = "='$username'";
	}
	// query get $username from database
	$query = $this->db->query("select * from user where u_username".$replace);
	// if query return more than 0 rows
	if ($query->num_rows() > 0) {
		return FALSE;
	}
	else{
		return TRUE;
	}
}
// check username and password
public function check_password($username,$password){
	// query get data user
	$query = $this->db->query("select * from user where u_username='".$username."' AND u_password='".$password."'");
	// if query return != 0
	if ($query->num_rows() != 1) {
		return FALSE;
	}
	else{
		// returning in array
		return $query->result_array();
	}
}
}
