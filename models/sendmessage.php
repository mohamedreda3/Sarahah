<?php

class SendMessage
{
    // o0bknJ5EJz1Oq0ugESEkM0kXrJMMrxSco0D9CD==

    private $conn;
    private $classResponse;
    public function __construct(Connectiontodb $db)
    {
        $this->conn = $db->connect();
        $this->classResponse = new Response;
    }
    public function sendMessage($data, $token)
    {
        if ($token != '') {
            if (!isset($data['message']) || $data['message'] == null) {
                echo $this->classResponse->getResponse(0, 'Enter Message', null);
            } else {
                if (str_starts_with($token, 'ftre')) {
                    $token = substr($token, 4, strlen($token));
                }
                $email = base64_decode(str_rot13(base64_decode(str_rot13($token))));
                $message = $data['message'];
                // echo $email;
                $checkEmailExists = mysqli_query($this->conn, "SELECT * FROM users WHERE email = '$email'");
                if ((mysqli_num_rows($checkEmailExists) > 0)) {
                    $insertQuery = mysqli_query($this->conn, "INSERT INTO messages(useremail, messagecontent, sent_at) VALUES('$email', '$message', Now())");
                    if ($insertQuery) {
                        echo $this->classResponse->getResponse(1, 'success');
                    } else {
                        echo $this->classResponse->getResponse(0, mysqli_error($this->conn), null);
                    }
                } else {
                    echo $this->classResponse->getResponse(0, 'Email does not exist', null);
                }
            }
        } else {
            echo $this->classResponse->getResponse(0, 'Invalid Token');
        }
    }
}
