<?php
    class user {
        private $p_params;

        public function __construct($param) {
            $this->p_params = $param;
        }

        public function addUser() {
            if($this->validateAddUser() == false) {
                new errorMsg(USER_ADD_ERROR, 'You can not add user.', $this->p_params);
            }

            if($this->userExists() == true) {
                new errorMsg(USER_ADD_ERROR, 'This user has already been added.', $this->p_params);
            }

            $keyGen = new keyGen(KEY_TYPE_MASTERKEY, 5);
            $outKey1 = $keyGen->getKey();

            $inputJSON = [
                'masterKeys' => $outKey1,
                'body' => $this->p_params['inputParams']['body']
            ];

            $conn = new dataConnect();
            $conn->set_sp('IF_' . $this->p_params['inputParams']['header']['interface'] . '_ADD_USER', json_encode($inputJSON));
            $out = $conn->exec();

            if($out[0]['result'] == 'NOK') {
                new errorMsg(SUBSCRIBER_ADD_ERROR, 'Add subcriber error. Error msg: ' . $out[0]['errorMessage'] == 'NOK' . '.', $this->p_params);
            }

            return $out[0]['result'];
        }

        public function userExists() {
            $conn = new dataConnect();
            $conn->set_sp('IF_' . $this->p_params['inputParams']['header']['interface'] . '_USER_EXISTS', json_encode($this->p_params['inputParams']['body']));
            $out = $conn->exec();

            return $out[0]['result'];
        }

        public function validateAddUser() {
            $conn = new dataConnect();
            $conn->set_sp('IF_' . $this->p_params['inputParams']['header']['interface'] . '_VALIDATE_ADD_USER', json_encode($this->p_params['inputParams']['body']));
            $out = $conn->exec();

            return $out[0]['result'];
        }

        public function registration() {
            $keyGen = new keyGen(KEY_TYPE_REGISTRATIONKEY, 5);
            $outKey2 = $keyGen->getKey();

            $inputJSON = [
                'registrationKeys' => $outKey2,
                'body' => $this->p_params['inputParams']['body']
            ];

            $conn = new dataConnect();
            $conn->set_sp('IF_' . $this->p_params['inputParams']['header']['interface'] . '_USER_REGISTRATION', json_encode($inputJSON));
            $out = $conn->exec();

            return $out[0];
        }

        public function registrationEnd($registrationKey = '') {
            if(empty($registrationKey) == false) {
                $this->p_params['inputParams']['body']['registrationKey'] = $registrationKey;
            }

            $conn = new dataConnect();
            $conn->set_sp('IF_' . $this->p_params['inputParams']['header']['interface'] . '_USER_REGISTRATION_END', json_encode($this->p_params['inputParams']['body']));
            $out = $conn->exec();

            return $out[0];
        }
    }
?>