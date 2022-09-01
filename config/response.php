<?php

class Response
{
   private $response = [];
    public function getResponse(?int $success, ?string $message, ?string $token = null, ?array $data = [])
    {
        if (($token == null) && (empty($data))) {
            return json_encode($this->response = array('Response' => array('success' => $success, 'message' => $message)));
        } elseif (!empty($data) && ($token == null)) {
            return json_encode($this->response = array('Response' => array('success' => $success, 'message' => $message, 'data' => $data)));
        } else {
            return json_encode($this->response = array('Response' => array('success' => $success, 'message' => $message, 'Token' => $token)));
        }
    }

}
