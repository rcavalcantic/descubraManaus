<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*') // * para APIs publicas
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

//GET All estabelecimentos
$app->get('/api/estabelecimentos', function(Request $request, Response $response){
	$sql = "SELECT * from estabelecimentos";

	try{
		//get database obj
		$db = new db();
		//connect
		$db = $db->connect();

		$stmt = $db->query($sql);
		$estabelecimentos = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		
		//return json_encode($estabelecimentos);
		return $response->withJson($estabelecimentos); 


	}catch(PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}';
	}
});

//GET one estabelecimentos
$app->get('/api/estabelecimento/{id}', function(Request $request, Response $response){
	$id = $request->getAttribute('id'); 

	$sql = "SELECT * from estabelecimentos WHERE id = $id";

	try{
		//get database obj
		$db = new db();
		//connect
		$db = $db->connect();

		$stmt = $db->query($sql);
		$estabelecimento = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		//return json_encode($estabelecimento);
		return $response->withJson($estabelecimento); 

	}catch(PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}';
	}
});

function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}