<?php
    class errorMsg {
        private $p_code;
        private $p_message;
        private $p_httpResponseCode;
        private $p_taskOut;

        public function __construct($code, $message, $taskOut, $httpResponseCode = 400) {
            $this->p_code = $code;
            $this->p_message = $message;
            $this->p_httpResponseCode = $httpResponseCode;
            $this->p_taskOut = $taskOut;
            $this->throwError();
        }

        private function throwError() {
            http_response_code($this->p_httpResponseCode);
            header('content-type: application/json');
			$errorMsg = json_encode(['error' => ['status' => $this->p_code, 'message' => $this->p_message]]);
            echo $errorMsg;

            if(is_array($this->p_taskOut) == true && isset($this->p_taskOut['header']) == false) {
                $status = new status($this->p_taskOut);
                $status->switchStatus(status::CONST_ERROR, $errorMsg);
            }

            die();
        }
    }
?>