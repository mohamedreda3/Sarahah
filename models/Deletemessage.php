<?php
class DeleteMessage
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
        if (!isset($data['id']) || $data['id'] == '') {
            echo $this->classResponse->getResponse(0, 'Invalid Id');
        }else {
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
                            $updateQuery = mysqli_query($this->conn, "DELETE FROM messages WHERE useremail = '$email' AND id = '$id'");
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
