<?php
    class dataConnect {
        private $p_connectionInfo;
        private $p_params;
        private $p_sql;
        private $p_hasRow;

        public function __construct() {
            $this->p_connectionInfo = array( 'Database'=>DATABASE, 'UID'=>USER_NAME, 'PWD'=>PASSWORD );
        }

        public function set_sp( $procedureName, $inputJSON ) {
            if( json_decode( $inputJSON ) == null) {
				new errorMsg(DATACONNECT_INVALID_PARAMETER, 'dataConnect input parameter is not valid JSON (' . $inputJSON . ')', '');
            }

            $this->p_sql = '{call '.$procedureName.' (?)}';
            $this->p_params = array(
                                 array( &$inputJSON, SQLSRV_PARAM_IN )
            );
        }

        public function exec() {
            $conn = sqlsrv_connect( SERVER, $this->p_connectionInfo );
            if($conn == false) {
                new errorMsg( CONNECTION_ERROR, print_r( sqlsrv_errors(), true ) );
            }

            $stmt = sqlsrv_query( $conn, $this->p_sql, $this->p_params );
    
            if( $stmt == false ){
                new errorMsg( EXECUTION_ERROR, print_r( sqlsrv_errors(), true ), '' );
            }

            if( sqlsrv_has_rows( $stmt ) == false ) {
                $this->p_hasRow = false;
                return;
            }

            $out='';
            while( sqlsrv_fetch ( $stmt ) ) {
                $out .= sqlsrv_get_field($stmt, 0, SQLSRV_PHPTYPE_STRING('UTF-8'));
            }

            sqlsrv_free_stmt( $stmt );
            sqlsrv_close( $conn );
    
            if( json_decode( $out ) == null ) {
				new errorMsg( DATACONNECT_INVALID_PARAMETER, 'dataConnect output parameter is not valid JSON (' . $out . ')', '' );
            }

            return json_decode( $out, true );
        }
    }
?>