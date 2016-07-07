#PHP REST API Template

This sample code can be used to create a REST API in PHP for educational purposes, not production use. The file structure and architecture is already provided, including the key features needed for any API. Add the controller and model files and you should be ready to go.


Some of the key features:
<ul><li>MVC Framework</li>
<li>REST architectural style</li>
<li>Request handler</li>
<li>Selectable response structure - JSON or XML, using &format URL parameter</li>
<li>Enable/disable authentication, using public-private key encryption</li>
<li>Debugging mode for request and response</li>
<li>Exception handling - displaying end logging errors</li>
<li>Automatic metadata/help page</li>
<li>Sample Users controller with CRUD database operations</li>
<li>Automatic class loading for Models/Views/Controllers</li></ul>

The automatic metadata page is available when hitting the API's base URL, for example http://localhost:7080/REST_API_Framework 

SAMPLE REQUESTS
------------------------------


GET USERS
------------------------------
http://localhost:7080/REST_API_Framework/users?format=json&public_key=abc&public_hash=a5d6ae8ac2d017771c075f887064d38e81169f8021be8ba473babcebb7459376

<root><authentication><public_key>abc</public_key><public_hash>a5d6ae8ac2d017771c075f887064d38e81169f8021be8ba473babcebb7459376</public_hash></authentication></root>

{"authentication":{"public_key":"abc","public_hash":"a5d6ae8ac2d017771c075f887064d38e81169f8021be8ba473babcebb7459376"}}



GET USER
------------------------------
http://localhost:7080/REST_API_Framework/users/1?format=json


INSERT USER
------------------------------
{"user":{"first_name":"Adrian","last_name":"Smith", "email":"adrian@test.com"}}
