<?php
/**
 * @desc Users controller
 * @author Paul Doelle
 */

class UsersController extends ApiController
{
	private $model;
	private $model_name;
	
	public function __construct() {
		preg_match("/(.+)Controller$/", get_class($this), $match);
		$this->model_name = $match[1] . "Model";
		if (class_exists($this->model_name)) {
			$this->model = new $this->model_name();
		} else {
        	throw new Exception("Model does not exist.", 500);
        }
	}
	
	/*
	 @uri	/Users
	 @verb	GET
	 @desc	Get a list of users
	 */
	/*
	 @uri	/Users/{id}
	 @verb	GET
	 @desc	Get one user
	 */
    public function getAction($request) {
        if (!empty($request->url_elements[3])) {
            $user_id = (int)$request->url_elements[3];
            if ($user_id)
            	// API/users/{id}
            	return $this->model->getUser($user_id);
            else
            	throw new Exception("Invalid User ID.", 400);
        } else {
        	// API/users
        	return $this->model->getUsers();
        }
    }
	
    /*
     @uri	/Users
     @verb	POST
     @desc	Create one user
     */
    public function postAction($request) {
        // API/users
		$this->model = Helper::cast($request->body->user, $this->model_name);
		if ($this->model->first_name && $this->model->last_name && $this->model->email)
    		return $this->model->createUser();
		else
			throw new Exception("Invalid or missing user object in request.", 400);
    }
    
    /*
     @uri	/Users
     @verb	PUT
     @desc	Update one user
     */
    public function putAction($request) {
    	// API/users
    	$this->model = Helper::cast($request->body->user, $this->model_name);
    	if ($this->model->id)
    		return $this->model->updateUser();
    	else
    		throw new Exception("Invalid or missing user object in request.", 400);
    }
    
    /*
     @uri	/Users/{id}
     @verb	DELETE
     @desc	Delete one user
     */
    public function deleteAction($request) {
    	// API/users/{id}
    	if (($request->url_elements[3])) {
            $user_id = (int)$request->url_elements[3];
            if ($user_id)
    			return $this->model->deleteUser($user_id);
	    	else
            	throw new Exception("Invalid User ID.", 400);
        } else {
        	throw new Exception("Missing User ID.", 400);
        }
    }
}