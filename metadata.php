<?php
/**
 * @desc Metadata/help page. This will automatically list each controller, along with a table containing the URI, Verb and Description
 * of each method based on the @uri, @verb and @desc comments within the controller file 
 * @author Paul Doelle
 */

$controllers = array();
foreach (glob("controllers/*.php") as $filename) {
    if ($filename != "controllers/ApiController.php") {
		$controller = new stdClass();
		$controller->file = $filename;
		$controller->name = substr($filename, 12, strpos($filename, 'Controller') - 12);
		$controller->methods = array(); 
		array_push($controllers, $controller);
		unset($controller);
    }
}

for ($ctr = 0; $ctr < count($controllers); $ctr++) {
	$source = file_get_contents($controllers[$ctr]->file);
	$comment_tokens = array();
	
	$tokens = token_get_all($source);
	foreach($tokens as $token) {
		if($token[0] == 372) //PHP 5.4 needs 370
			array_push($comment_tokens, $token);
	}
	
	foreach($comment_tokens as $token) {
		if (preg_match("/@uri.*@verb.*@desc/is", $token[1])) {
			preg_match("/@uri\s*(.*)\s/i", $token[1], $uri);
			preg_match("/@verb\s*(.*)\s/i", $token[1], $verb);
			preg_match("/@desc\s*(.*)\s/i", $token[1], $desc);
			$method = new StdClass();
			$method->uri = $uri[1];
			$method->verb = $verb[1];
			$method->desc = $desc[1];
			array_push($controllers[$ctr]->methods, $method);
			unset($method);
		}
	}
}

//var_dump($controllers);

// PHP <5.5 does not include $_SERVER['REQUEST_SCHEME']
//$base_url = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/') + 1);

?>
<html>
<head>
	<title>API Metadata</title>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<script type='text/javascript' src='http://code.jquery.com/jquery-2.0.3.js'></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
	<h1>API Metadata Page</h1>
	<br/>
	<?php foreach ($controllers as $ctr): ?>
	<div class="col-sm-6">
		<h3><?php echo $ctr->name; ?> Controller</h3>
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th>URI</th>
					<th>Verb</hth>
					<th>Description</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($ctr->methods as $method): ?>
				<tr>
					<td><?php echo $method->uri; ?></td>
					<td><?php echo $method->verb; ?></td>
					<td><?php echo $method->desc; ?></td>
				</tr>
				<?php endforeach; ?>	
			</tbody>
		</table>
	</div>
	<?php endforeach; ?>
</div>

</body>
</html>














