<?php
/**
 * @desc REST API Framework
 * Simply add/update the Controllers and Models using the appropriate naming conventions
 * Authentication and debugging can be enabled/disabled
 * @author Paul Doelle, 29/03/15
 */

static $debug = 0;
static $authenticate = 0;

// autoload classes rather than needing to manually include them in each file.
// when instantiating a class not found, it will find it through this function by passing the class name as a parameter, e.g. 'UsersController'
try {
	spl_autoload_register(function ($classname)
	{
	    if (preg_match('/[a-zA-Z]+Controller$/', $classname)) {
	        include __DIR__ . '/controllers/' . $classname . '.php';
	    } elseif (preg_match('/[a-zA-Z]+Model$/', $classname)) {
	        include __DIR__ . '/models/' . $classname . '.php';
	    } elseif (preg_match('/[a-zA-Z]+View$/', $classname)) {
	        include __DIR__ . '/views/' . $classname . '.php';
	    } else {
	        include __DIR__ . '/library/' . str_replace('_', DIRECTORY_SEPARATOR, $classname) . '.php';
	    }
	});
	
	$request = new Request();
	
	// create an authentication object from GET parameters otherwise body (can use either in any type of request).
	// Assumes URL parameters &public_key, &public_hash, or in body an 'authentication' object with 'public_key' and 'public_hash' elements
	if ($authenticate) {
		$public_key = $request->parameters['public_key'];
		if (!empty($public_key)) {
			$public_hash = $request->parameters['public_hash'];
			$auth = new Authentication($public_key, $public_hash);
		}
		else {
				$public_key = $request->body->authentication->public_key;
				$public_hash = $request->body->authentication->public_hash;
				$auth = new Authentication($public_key, $public_hash);			
		}
		if (empty($auth) || !$auth->is_valid())
			throw new Exception("Authentication was unsuccessful.", 401);
	}
		
	// route the request to the right place
	// if the second URL parameter maps to a controller, instantiate this and call the function according to the request verb
	// if there is no second URL parameter, redirect to the metadata page
	if ($request->url_elements[2] && !strpos($request->url_elements[2], '.')) {
		$controller_name = ucfirst($request->url_elements[2]) . 'Controller';
		if (class_exists($controller_name)) {
		    $controller = new $controller_name();
		    $action_name = strtolower($request->verb) . 'Action';
		    $result = $controller->$action_name($request);
			
		    if (!$debug) {
			    $view_name = ucfirst($request->output_format) . 'View';
			    if(class_exists($view_name)) {
			        $view = new $view_name();
			        $view->render($result);
			    } else
			    	throw new Exception("The requested format '$request->output_format' is not supported.", 400);
	    	} else { // if debugging is enabled, output the request and response as HTML
	    	
	    		header('Content-Type: text/html; charset=utf8');
	    		echo "<h1>DEBUGGING</h1><br/>";
	    		echo "<h3>REQUEST</h3>";
	    		echo "<b>Request URI: </b>" . $_SERVER['REQUEST_URI'] . "<br/><br/>";
	    		echo "<b>Verb: </b>" . $request->verb . "<br/><br/>";
	    		echo "<b>Request Body (parsed): </b><br/>";
	    		echo var_dump($request->body);
	    		echo "<b>Request (parsed): </b><br/>";
	    		echo var_dump($request) . "<br/><br/>";
	    		echo "<h3>RESPONSE</h3>";
	    		echo "<b>Result (parsed): </b><br/>";
	    		echo var_dump($result);
	    		echo "<b>JSON Result: </b>" . json_encode($result);
	    	}
		} else
			throw new Exception("The requested object '$controller_name' is not supported.", 400);
	} else {
		if (ucwords($request->verb) == "GET" && !$request->url_elements[2]) {
			header("Location: metadata.php");
			die("Location Header was ignored.");
		} else {
			throw new Exception("The 'controller' URL parameter is missing.", 400);
		}
	}
} catch (Exception $e) {
	header('Content-Type: text/html; charset=utf8');
	http_response_code($e->getCode());
	echo "<h1>" . $e->getCode() . " - " . $e->getMessage() . "</h1><br/><br/>";
	echo "Stack Trace: " . $e->getTraceAsString();
	error_log(date('Y-m-d h:i:s a', time()) . " - " . $e->getCode() . " - " . $e->getMessage() . PHP_EOL . "Stack Trace: " . $e->getTraceAsString() . PHP_EOL.PHP_EOL, 3, "log.txt");
}