<?php
/**
 * @desc Users model
 * @author Paul Doelle
 */

class UsersModel extends ApiModel
{
	public $id;
	public $first_name;
	public $last_name;
	public $email;

	public function getUsers() {
		$pdo = DB::get()->prepare("SELECT * FROM user");
		$pdo->execute();
		$result = $pdo->fetchAll(PDO::FETCH_CLASS, 'UsersModel');
		
		$users = array();
		if (count($result)) {
			foreach($result as $row)
				array_push($users, $row);
			return $users;
		} else
			throw new Exception("No users found.", 204);
	}
	
	public function getUser($user_id) {
		$pdo = DB::get()->prepare("SELECT * FROM user WHERE id = :id");
		$pdo->execute(array('id' => $user_id));
		$result = $pdo->fetchAll(PDO::FETCH_CLASS, 'UsersModel');

		if (count($result) == 1)
			return $result;
		else
			throw new Exception("No user found.", 204);
	}
	
	public function createUser() {
		$pdo = DB::get()->prepare("INSERT INTO user (first_name, last_name, email) VALUES (:first_name, :last_name, :email)");
		$pdo->execute(array(
				':first_name' 	=> $this->first_name,
				':last_name' 	=> $this->last_name,
				':email'		=> $this->email
		));

		if ($pdo->rowCount() > 0) {
			$this->id = DB::lastInsertId('id');
			return $this;
		}
		else
			throw new Exception("No user was created.", 500);
	}
	
	public function updateUser() {
		$pdo = DB::get()->prepare("UPDATE user SET first_name = :first_name, last_name = :last_name, email = :email WHERE id = :id");
		$pdo->execute(array(
				':id'			=> $this->id,
				':first_name' 	=> $this->first_name,
				':last_name' 	=> $this->last_name,
				':email'		=> $this->email
		));
	
		if ($pdo->rowCount() > 0) {
			return $this;
		}
		else
			throw new Exception("No user was updated.", 200);
	}
	
	public function deleteUser($user_id) {
		$pdo = DB::get()->prepare("DELETE FROM user WHERE id = :id");
		$pdo->execute(array(
				':id'			=> $user_id
		));
	
		if ($pdo->rowCount() > 0) {
			$this->id = $user_id;
			return $this;
		}
		else
			throw new Exception("No user was deleted.", 400);
	}
}