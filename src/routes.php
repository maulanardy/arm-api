<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->post('/login', function (Request $request, Response $response, array $args) {
 
    $input = $request->getParsedBody();
    // $sql = "SELECT * FROM users WHERE email= :email";
    // $sth = $this->db->prepare($sql);
    // $sth->bindParam("email", $input['email']);
    // $sth->execute();
    // $user = $sth->fetchObject();

    if($input['email'] == 'arjunphp@gmail.com'){
        $user = new stdClass();
        $user->password = "$2y$10$2N74YBkxYXPtEtFTxynuxeEn9OH9BZ.wI4ldZr00n1FX5q09/llbO";
    }
 
    // verify email address.
    if(!$user) {
        return $this->response->withJson(['error' => true, 'message' => 'These credentials do not match our records.']);  
    }
 
    // verify password.
    if (!password_verify($input['password'],$user->password)) {
        return $this->response->withJson(['error' => true, 'message' => 'These credentials do not match our records.']);  
    }
 
    $settings = $this->get('settings'); // get settings array.
    
    $token = JWT::encode(['id' => $user->id, 'email' => $user->email], $settings['jwt']['secret'], "HS256");
 
    return $this->response->withJson(['token' => $token]);
 
});

$app->group('/api', function(\Slim\App $app) {
 
    $app->get('/user',function(Request $request, Response $response, array $args) {
        print_r($request->getAttribute('decoded_token_data'));
 
        /*output 
        stdClass Object
            (
                [id] => 2
                [email] => arjunphp@gmail.com
            )
                    
        */
    });
   
});
