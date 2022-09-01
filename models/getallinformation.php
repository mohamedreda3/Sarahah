<?php
class GetAllInformation
{
    private $conn;
    private $res;
    public function __construct(Connectiontodb $bd)
    {
        $this->conn = $bd->connect();
        $this->res = new Response;
    }

    public function exportInformation(?string $token = '')
    {
        if ($token != '') {
            $decodeToken = new GetToken;
            $payload = $decodeToken->decodeAccessToken($token);
            $expTime = $payload['exp'];
            $timeNow = time();
            if ($timeNow < $expTime) {
                $email = base64_decode($payload['data']);
                $checkEmailQuery = '';
                $checkEmailQuery = mysqli_query($this->conn, "SELECT * FROM users WHERE email = '$email'");

                if (mysqli_num_rows($checkEmailQuery) > 0) {
                    $dataAssocc = mysqli_fetch_assoc($checkEmailQuery);
                    $data = array(
                        'User-Id' => 'ftre' . str_rot13(base64_encode(str_rot13(base64_encode($email)))),
                        'User-Name' => $dataAssocc['username'],
                        'Phone-Number' => $dataAssocc['phone'],
                        'Location' => $dataAssocc['loc'],
                        'About_u' => $dataAssocc['about_u'],
                        'Sex' => $dataAssocc['sex'],
                    );
                    echo $this->res->getResponse(1, 'Success', null, $data);
                } else {
                    echo $this->res->getResponse(0, 'Email does not exist, you can create account if you do not have one', null);
                }
            } else {
                echo $this->res->getResponse(0, 'Invalid Token');
            }
        } else {
            echo $this->res->getResponse(0, 'Invalid Token');
        }
    }
}
