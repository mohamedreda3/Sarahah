<?php
class UpdateData
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
        if ($token != '') {
            $decodeToken = new GetToken;
            $payload = $decodeToken->decodeAccessToken($token);
            $expTime = $payload['exp'];
            $timeNow = time();
            $email = base64_decode($payload['data']);
            if ($timeNow < $expTime) {
                $checkEmailQuery = mysqli_query($this->conn, "SELECT * FROM users WHERE email = '$email'");
                if (mysqli_num_rows($checkEmailQuery) > 0) {
                    $uData = mysqli_fetch_assoc($checkEmailQuery);
                    $username = '';
                    $phone = '';
                    $sex = '';
                    $about_u = '';
                    $userSex = ['MALE', 'FEMALE'];
                    
                    if (isset($data['sex']) && !empty($data['sex'])) {
                        if (in_array(strtoupper($data['sex']), $userSex)) {
                            $sex = $data['sex'];
                        } else {
                            echo $this->classResponse->getResponse(0, 'Enter user Sex (Male, Female)', null);
                        }
                    } else {
                        $sex = $uData['sex'];
                    }

                    if (isset($data['phone']) && !empty($data['phone'])) {
                        if (filter_var(trim(intval($data['phone'])), FILTER_VALIDATE_INT)) {
                            $phone = $data['phone'];
                        } else {
                            echo $this->classResponse->getResponse(0, 'Enter validate Phone number', null);
                        }
                    } else {
                        $phone = $uData['phone'];
                    }

                    if (isset($data['name']) && !empty($data['name'])) {
                        $username = $data['name'];
                    } else {
                        $username = $uData['username'];
                    }

                    if (isset($data['about_u']) && !empty($data['about_u'])) {
                        $about_u = $data['about_u'];
                    } else {
                        $about_u = $uData['about_u'];
                    }
                    
                    $updateQuery = mysqli_query($this->conn, "UPDATE users SET username = '$username', phone = '$phone', about_u = '$about_u', sex = '$sex' WHERE email = '$email'");
                    if ($updateQuery) {
                        echo $this->classResponse->getResponse(1, 'success');
                    } else {
                        echo $this->classResponse->getResponse(0, mysqli_error($this->conn), null);
                    }
                } else {
                    echo $this->classResponse->getResponse(0, 'Email does not exist, you can create account if you do not have one', null);
                }
            } else {
                echo $this->classResponse->getResponse(0, 'Expired Token');
            }
        } else {
            echo $this->classResponse->getResponse(0, 'Invalid Token');
        }
    }
}
