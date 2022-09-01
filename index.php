<?php


//  ------------------------------------------------------

//  ------------------------------------------------------
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
//  ------------------------------------------------------


//  ------------------------------------------------------
require_once './config/includes.php';
//  ------------------------------------------------------

//  ------------------------------------------------------
$request = explode('/', $_SERVER['REQUEST_URI']);
$controlRequest = $request[2];
//  ------------------------------------------------------

//  ------------------------------------------------------
$connect = new Connectiontodb;
$response = new Response;
//  ------------------------------------------------------

//  ------------------------------------------------------
$conroller = new Controller($connect);
//  ------------------------------------------------------

//  ------------------------------------------------------

$data = '';

if (empty($_POST)) {
    $data = file_get_contents("php://input");
    $data = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data), true);
} else {
    $data = $_POST;
}

// print_r($data);
//  ------------------------------------------------------
//signup
//  ------------------------------------------------------
if ($controlRequest == 'signup') {
    $conroller->processRequest($controlRequest, $data);
}
//  ------------------------------------------------------
//Confirm-Email
//  ------------------------------------------------------
else if ($controlRequest == 'confirmemail') {
    $conroller->processRequest($controlRequest, $data);
}
//  ------------------------------------------------------
//Log-in
//  ------------------------------------------------------
else if ($controlRequest == 'login') {
    $conroller->processRequest($controlRequest, $data);
}
//  ------------------------------------------------------
//Reset Password
//  ------------------------------------------------------
else if ($controlRequest == 'resetpassword') {
    $conroller->processRequest($controlRequest, $data);
}
//  ------------------------------------------------------
//Confirm Code
//  ------------------------------------------------------
else if ($controlRequest == 'confirmcode') {
    $conroller->processRequest($controlRequest, $data);
}
//changepassword
//  ------------------------------------------------------
else if ($controlRequest == 'changepassword') {
    $conroller->processRequest($controlRequest, $data);
}
//getinformation
//  ------------------------------------------------------
else if ($controlRequest == 'getallinformation') {
    if (!isset(getallheaders()['Authorization'])) {
        echo $response->getResponse(0, 'Invalid Token');
    } else {
        $bearerToken = (trim(explode('Bearer', getallheaders()['Authorization'])[1]));
        if ($bearerToken != '') {
            $conroller->processRequest($controlRequest, [], $bearerToken);
        } else {
            echo $response->getResponse(0, 'Invalid Token');
        }
    }
}
//Update Data
//  ------------------------------------------------------
else if ($controlRequest == 'updatedata') {
    if (!isset(getallheaders()['Authorization'])) {
        echo $response->getResponse(0, 'Invalid Token');
    } else {
        $bearerToken = (trim(explode('Bearer', getallheaders()['Authorization'])[1]));
        if ($bearerToken != '') {
            $conroller->processRequest($controlRequest, $data, $bearerToken);
        } else {
            echo $response->getResponse(0, 'Invalid Token');
        }
    }
}
//Send Message
//  ------------------------------------------------------
else if ($controlRequest == 'sendmessage') {
    if (!isset(getallheaders()['Authorization'])) {
        echo $response->getResponse(0, 'Invalid Token');
    } else {
        $bearerToken = (trim(explode('Bearer', getallheaders()['Authorization'])[1]));
        if ($bearerToken != '') {
            $conroller->processRequest($controlRequest, $data, $bearerToken);
        } else {
            echo $response->getResponse(0, 'Invalid Token');
        }
    }
}
//getmessages
//  ------------------------------------------------------
else if ($controlRequest == 'getmessages') {
    if (!isset(getallheaders()['Authorization'])) {
        echo $response->getResponse(0, 'Invalid Token');
    } else {
        $bearerToken = (trim(explode('Bearer', getallheaders()['Authorization'])[1]));
        if ($bearerToken != '') {
            $conroller->processRequest($controlRequest, [], $bearerToken);
        } else {
            echo $response->getResponse(0, 'Invalid Token');
        }
    }
}
//Update Messages
//  ------------------------------------------------------
else if ($controlRequest == 'updatemessage') {
    if (!isset(getallheaders()['Authorization'])) {
        echo $response->getResponse(0, 'Invalid Token');
    } else {
        $bearerToken = (trim(explode('Bearer', getallheaders()['Authorization'])[1]));
        if ($bearerToken != '') {
            $conroller->processRequest($controlRequest, $data, $bearerToken);
        } else {
            echo $response->getResponse(0, 'Invalid Token');
        }
    }
}
//Delete Messages
//  ------------------------------------------------------
else if ($controlRequest == 'deletemessage') {
    if (!isset(getallheaders()['Authorization'])) {
        echo $response->getResponse(0, 'Invalid Token');
    } else {
        $bearerToken = (trim(explode('Bearer', getallheaders()['Authorization'])[1]));
        if ($bearerToken != '') {
            $conroller->processRequest($controlRequest, $data, $bearerToken);
        } else {
            echo $response->getResponse(0, 'Invalid Token');
        }
    }
}
//Send reply on message
//  ------------------------------------------------------
else if ($controlRequest == 'replyonmessage') {
    $bearerToken = '';
    if (isset(getallheaders()['Authorization'])) {
        $bearerToken = (trim(explode('Bearer', getallheaders()['Authorization'])[1]));
    }
    $conroller->processRequest($controlRequest, $data, $bearerToken);
}
//Send reply on reply
//  ------------------------------------------------------
else if ($controlRequest == 'replyonreply') {
    $bearerToken = '';
    if (isset(getallheaders()['Authorization'])) {
        $bearerToken = (trim(explode('Bearer', getallheaders()['Authorization'])[1]));
    }
    $conroller->processRequest($controlRequest, $data, $bearerToken);
}
