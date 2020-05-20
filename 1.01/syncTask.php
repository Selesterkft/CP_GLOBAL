<?php
    class synctask extends rest {
        private $p_taskId;

        public function __construct() {
            parent::__construct();
        }

        public function addTask() {
			//validate token
            $token = new token($this->p_data);
            $tknOut = $token->validateToken();

            //validate rest json
            $this->validateParameter('header/taskType', STRING);
            $this->validateParameter('header/interface', STRING);
            $this->validateParameter('header/sender', STRING);
            $this->validateParameter('header/recepient', STRING);
            $this->validateParameter('body', BLOCK);

            $inputJSON = [
                'token' => $tknOut,
                'data' => $this->p_data
            ];

            $conn = new dataConnect();
            $conn->set_sp('IF_' . $this->p_result['interface'] . '_ADD_TASK', json_encode($inputJSON));
            $this->p_taskId = $conn->exec()[0]['taskId'];

            $out = [
                'token' => $tknOut['token'],
                'taskId' => $this->p_taskId,
                'inputParams' => $this->p_data
            ];

            return $out;
        }

        public function switchTaskStatus() {
            $conn = new dataConnect();
            $conn->set_sp('IF_' . $this->p_result['interface'] . '_ADD_TASK', '{"taskId":' . $this->p_taskId . '}');
            $conn->exec();
        }
    }
?>