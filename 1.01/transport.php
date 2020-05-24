<?php
    class transport {
        private $p_params;

        public function __construct( $param ) {
            $this->p_params = $param;
        }

        public function getTransportData() {
            $conn = new dataConnect();
            $conn->set_sp( 'IF_' . $this->p_params['inputParams']['header']['interface'] . '_GET_TRANSPORT_DATA', json_encode( $this->p_params ) );
            $out = $conn->exec();

            return $out;
        }
    }
?>