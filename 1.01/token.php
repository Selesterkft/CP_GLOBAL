<?php
    require_once( VERSION_PATH . '/extapi/jwt/BeforeValidException.php' );
    require_once( VERSION_PATH . '/extapi/jwt/ExpiredException.php' );
    require_once( VERSION_PATH . '/extapi/jwt/JWT.php' );
    require_once( VERSION_PATH . '/extapi/jwt/SignatureInvalidException.php' );
    use \Firebase\JWT\JWT;

    class token {
        private $p_params;
        private $p_token;

        public $p_userName;
        public $p_registrationKey;
        public $p_acitve;

        public function __construct ( $params ) {
            $this->p_params = $params;
        }

        public function getToken( $Renewal = true ) {
            if( $Renewal == false ) {
                $inputParams = [
                    'userName' => $this->p_params['body']['userName'],
                    'registrationKey' => $this->p_params['body']['registrationKey'],
                    'masterKey' => $this->p_params['body']['masterKey']
                ];
                $conn = new dataConnect();
                $conn->set_sp( 'IF_' . $this->p_params['header']['interface'] . '_VALIDATE_USER', json_encode( $inputParams ) );
                $out = json_decode( $conn->exec()['result'] );

                if( $out[0]->masterKey == '' || $out[0]->registrationKey == '' ) {
                    new errorMsg( TOKEN_VALIDATE_ERROR, 'Token validate error.', $this->p_params );
                }

                $this->p_acitve = $out[0]->active;

                if( $this->p_acitve == false ) {
                    new errorMsg( USER_NOT_ACTIVE, 'This user may be decactived. Please contact to admin.', $this->p_params );
                }
            }

            $tokenKey = $this->getTokenKey();

            $payload = [
                'userName' => $this->p_userName,
                'registrationKey' => $this->p_registrationKey,
                'iat' => time(),
                'exp' => time() + ( TOKEN_EXP ),
            ];

            return ['token' =>JWT::encode( $payload, $tokenKey, 'HS256' )];
        }

        public function getTokenKey () {
            $conn = new dataConnect();
            $conn->set_sp( 'IF_' . $this->p_params['header']['interface'] . '_GET_PARAM', '{"ParamKey":"TOKEN_KEY"}' );
            $out = $conn->exec();

            if(!isset( $out )){
                new errorMsg( TOKEN_MISSING_KEY, 'Could not find token key.', $this->p_params );
            }

            return hash( 'sha512', $out[0]['ParamValueString'] );
        }

        public function validateToken() {
            if( $this->p_params['header']['taskType'] == TASK_TYPE_TOKEN ) {
                $this->p_userName = $this->p_params['body']['userName'];
                $this->p_registrationKey = $this->p_params['body']['registrationKey'];
                $this->p_token = $this->getToken( false );
            } else {
                    try {
                        if( isset( $this->p_params['token'] ) == false ) {
                            new errorMsg( MISSING_TOKEN, 'Could not find token.', $this->p_params );
                        }

                        $this->p_token = $this->p_params['token'];
                        $payload = JWT::decode( $this->p_token, $this->getTokenKey(), ['HS256'] );

                        $this->p_userName = $payload->userName;
                        $this->p_registrationKey = $payload->registrationKey;
                        $this->p_token = $this->getToken();

                    } catch ( Exception $e ) {
                        new errorMsg( ACCESS_TOKEN_ERRORS, $e->getMessage(), $this->p_params );
                    }
            }

            $out = [
                'token' => $this->p_token,
                'userName' => $this->p_userName,
                'registrationKey' => $this->p_registrationKey
            ];

            return $out;
		}
    }
?>