<?php
    class status {
        const CONST_TRANSFERRED = 'T';
        const CONST_ACCEPTED = 'A';
        const CONST_ERROR = 'E';

        private $p_taskOut;

        public function __construct ($taskOut) {
            $this->p_taskOut = $taskOut;
        }

        public function switchStatus ($toStatus, $result) {
            $conn = new dataConnect();

            $proc = 'IF_' . $this->p_taskOut['inputParams']['header']['interface'] . '_SWITCH_STATUS';
            $param = '{"taskId":' . $this->p_taskOut['taskId'] . ', "toStatus": "' . $toStatus . '", "taskResult": "' . preg_replace('~[\\\\/:*?"<>|]~', ' ', $result) . '"}';

            $conn->set_sp($proc, $param);
            $out = $conn->exec();
            
            if(!isset($out)){
                echo 'Could not switch status code.';
                die;
            }

            return $out;
        }
    }
?>