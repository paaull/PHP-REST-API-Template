<?php
/**
 * @desc Parse the request data
 * @author Paul Doelle
 */

class Request {
    public $url_elements;	// URL elements in array delimited by '/' excluding parameters
    public $verb;			// HTTP verb
    public $parameters;		// URL parameters. reserved variables = format, public_key, public_hash
	public $body;			// Body - parsed complex object request
	public $request_format; // Content-Type of the request
	public $output_format;	// Output requested Content-Type. Based on &format URL parameter, defaults based on request format, else defaults to 'json'

    public function __construct() {
        $this->verb = $_SERVER['REQUEST_METHOD'];
        $this->url_elements = explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]);
		$this->output_format = 'json';
		$this->parseURLParams();
        $this->parseBody();
        if(!empty($this->parameters['format'])) {
            $this->output_format = $this->parameters['format'];
        }
        return true;
    }

    private function parseURLParams() {
        $parameters = array();
        if (!empty($_SERVER['QUERY_STRING'])) {
            parse_str( $_SERVER['QUERY_STRING'], $this->parameters);
        }
	}
	
	private function parseBody() {
        $body = file_get_contents("php://input");
        $this->request_format = false;
        if(!empty($_SERVER['CONTENT_TYPE']) && strlen($body) > 0) {
            $this->request_format = $_SERVER['CONTENT_TYPE'];
			switch($this->request_format) {
				case "application/json":
					$body = json_decode($body);
					if($body)
						$this->body = $body;
					else
						throw new Exception("The request content was invalid and could not be parsed successfully as JSON.", 400);
					$this->output_format = "json";
					break;
				case "application/xml":
					$body = simplexml_load_string($body);
					if($body)
						$this->body = $body;
					else
						throw new Exception("The request body was invalid and could not be parsed successfully as XML.", 400);
					$this->output_format = "xml";
					break;
				default:
					throw new Exception("Unsupported request body Content-Type of '".$_SERVER['CONTENT_TYPE']."'.", 400);
					break;
			}
		} elseif (strlen($body) > 0) {
			throw new Exception("There was no Content-Type set in the request.", 400);
		}
    }
}
