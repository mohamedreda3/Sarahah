<?php
class UpdateMessage
{
    private $conn;
    private $classResponse;
    public function __construct(Connectiontodb $bd)
    {
        $this->conn = $bd->connect();
        $this->classResponse = new Response;
    }

    public function update(?array $data, ?string $token)
    {
        // echo "SET";
        $processes = ['SET_PRIVATE', 'SET_PUBLIC'];
        if (!isset($data['id']) || $data['id'] == '') {
            echo $this->classResponse->getResponse(0, 'Invalid Id');
        } else if (!isset($data['process']) || $data['process'] == '' || !in_array(strtoupper($data['process']), $processes)) {
            echo $this->classResponse->getResponse(0, 'Invalid Process, process should be [SET_PRIVATE, SET_PUBLIC]');
        } else {
            if ($token != '') {
                $decodeToken = new GetToken;
                $payload = $decodeToken->decodeAccessToken($token);
                $expTime = $payload['exp'];
                $timeNow = time();
                $email = base64_decode($payload['data']);
                // echo $email;
                if ($timeNow < $expTime) {
                    $id = $data['id'];
                    $checkEmailQuery = mysqli_query($this->conn, "SELECT * FROM messages WHERE useremail = '$email' AND id = '$id'");
                    if (mysqli_num_rows($checkEmailQuery) > 0) {
                        if (strtoupper($data['process']) == 'SET_PUBLIC') {
                            $updateQuery = mysqli_query($this->conn, "UPDATE messages SET is_public = true WHERE useremail = '$email' AND id = '$id'");
                        } else {
                            $updateQuery = mysqli_query($this->conn, "UPDATE messages SET is_public = false WHERE useremail = '$email' AND id = '$id'");
                        }
                        if ($updateQuery) {
                            echo $this->classResponse->getResponse(1, 'success');
                        } else {
                            echo $this->classResponse->getResponse(0, mysqli_error($this->conn), null);
                        }
                    } else {
                        echo $this->classResponse->getResponse(0, 'Do not have this message', null);
                    }
                } else {
                    echo $this->classResponse->getResponse(0, 'Expired Token');
                }
            } else {
                echo $this->classResponse->getResponse(0, 'Invalid Token');
            }
        }
    }
}
