<?php
    class subscriber {
        private $p_params;

        public function __construct($param) {
            $this->p_params = $param;
        }

        public function addSubscriber() {
            if( $this->validateAddSubscriber() == false ) {
                new errorMsg( SUBSCRIBER_ADD_ERROR, 'You can not add subscriber.', $this->p_params );
            }

            if( $this->subscriberExists() == true ) {
                new errorMsg( SUBSCRIBER_ADD_ERROR, 'This subscriber has already been added.', $this->p_params );
            }

            $keyGen = new keyGen( KEY_TYPE_MASTERKEY, 5 );
            $outKey = $keyGen->getKey();

            $inputJSON = [
                'masterKeys' => $outKey,
                'body' => $this->p_params['inputParams']['body']
            ];

            $conn = new dataConnect();
            $conn->set_sp( 'IF_' . $this->p_params['inputParams']['header']['interface'] . '_ADD_SUBSCRIBER', json_encode( $inputJSON ) );
            $out = $conn->exec();

            if($out['result'] == 'NOK') {
                new errorMsg( SUBSCRIBER_ADD_ERROR, 'Add subcriber error. Error msg: ' . $out['errorMessage'] == 'NOK' . '.', $this->p_params );
            }

            return $out['result'];
        }

        public function subscriberExists() {
            $conn = new dataConnect();
            $conn->set_sp( 'IF_' . $this->p_params['inputParams']['header']['interface'] . '_SUBSCRIBER_EXISTS', json_encode( $this->p_params['inputParams']['body'] ) );
            $out = $conn->exec();

            return $out['result'];
        }

        public function validateAddSubscriber() {
            $conn = new dataConnect();
            $conn->set_sp( 'IF_' . $this->p_params['inputParams']['header']['interface'] . '_VALIDATE_ADD_SUBSCRIBER', json_encode( $this->p_params ) );
            $out = $conn->exec();

            return $out['result'];
        }
    }
?>