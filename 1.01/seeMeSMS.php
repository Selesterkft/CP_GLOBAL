<?php
    require_once(VERSION_PATH . '/extapi/seeme/seeme-gateway-class.php');

    class seeMeSMS {
        private $p_apiKey;
        private $p_params;
        private $p_messageText;

        public function __construct($params, $messageText) {
            $this->p_params = $params;
            $this->p_messageText = $messageText;

            if($this->p_params['inputParams']['body']['apiKey'] == '-1') {
                $conn = new dataConnect();
                $conn->set_sp('IF_' . $this->p_params['inputParams']['header']['interface'] . '_GET_PARAM', '{"ParamKey":"SEEME_KEY"}');
                $out = $conn->exec();

                if(!isset($out)){
                    new errorMsg(SMS_MISSING_KEY, 'Could not find SMS key.', $this->p_params);
                }

                $this->p_apiKey = $out[0]['ParamValueString'];
            }else{
                $this->p_apiKey = $this->p_params['inputParams']['body']['apiKey'];
            }
        }

        public function send() {
            // connect to seeme gateway
            $seeMe = new SeeMeGateway($this->p_apiKey);

            try {
                //send sms
                $seeMe->sendSMS (
                                $this->p_params['inputParams']['body']['phoneNumber'],  // destination
                                $this->p_messageText,                                   // message
                                $this->p_params['inputParams']['body']['sender']        // optional sender
                );
            
            $out = $seeMe->getResult();
            
            } catch ( Exception $e ) {
                new errorMsg(SMS_SEND_ERROR, 'Could not send sms: ' . print_r( $seeMe->getResult(), true ), $this->p_params);
            }

            return $out;
        }
    }
?>