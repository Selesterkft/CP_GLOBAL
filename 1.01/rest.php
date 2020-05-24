<?php
    require_once( 'constants.php' );

    class rest {
		private $p_request;
		
		public $p_data;
		public $p_result;

        public function __construct() {
            if( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
				new errorMsg( REQUEST_METHOD_NOT_VALID, 'Request Method is not valid.', '' );
            }
            
            $handler = fopen( 'php://input', 'r' );
            $this->p_request = stream_get_contents( $handler );
			$this->validateRequest();
        }

        public function validateRequest() {
			if( array_key_exists( 'CONTENT_TYPE', $_SERVER ) == false ) {
				new errorMsg( REQUEST_CONTENTTYPE_NOT_VALID, 'Request content type is missing', '' );
			}

			if( $_SERVER['CONTENT_TYPE'] !== 'application/json; charset=utf-8' && $_SERVER['CONTENT_TYPE'] !== 'application/json' ) {
				new errorMsg( REQUEST_CONTENTTYPE_NOT_VALID, 'Request content type is not valid'.$_SERVER['CONTENT_TYPE'], '' );
			}

            if( json_decode( $this->p_request ) == null ) {
				new errorMsg( REQUEST_CONTENTTYPE_NOT_VALID, 'Request content type is not valid JSON', '' );
            }
			$this->p_data = json_decode( $this->p_request, true );
        }

        public function validateParameter( $fieldNameWithPath, $dataType, $required = true, $acceptedValues = '' ) {
			$node = explode( '/', $fieldNameWithPath );
			$arr = $this->p_data;

			foreach ( $node as $item ) {
				if( !isset( $arr[$item] ) ){
					if( $required == true ) {
						new errorMsg( VALIDATE_PARAMETER_REQUIRED, 'Missing parameter: ' . $item, '' );
					}

					break;
				}
				$arr = $arr[$item];
				$tag = $item;
			}

			if( is_array( $arr ) == false ) {
				$value = $arr;

				if( $required == true && isset( $value ) == false ) {
					new errorMsg( VALIDATE_PARAMETER_REQUIRED, $fieldNameWithPath . ' parameter is required.', '' );
				}
			}else {
				if( $required == true && empty( $arr ) == true ) {
					new errorMsg( VALIDATE_PARAMETER_REQUIRED, $fieldNameWithPath . ' parameter is required.', '' );
				}
			}

			switch ( $dataType ) {
				case BOOLEAN:
					if( !is_bool( $value ) ) {
						new errorMsg( VALIDATE_PARAMETER_DATATYPE, 'Datatype is not valid for ' . $fieldNameWithPath . '. It should be boolean.', '' );
					}
					break;
				case INTEGER:
					if( !is_numeric( $value ) ) {
						new errorMsg( VALIDATE_PARAMETER_DATATYPE, 'Datatype is not valid for ' . $fieldNameWithPath . '. It should be numeric.', '' );
					}
					break;

				case STRING:
					if( !is_string( $value ) ) {
						new errorMsg( VALIDATE_PARAMETER_DATATYPE, 'Datatype is not valid for ' . $fieldNameWithPath . '. It should be string.', '' );
					}
					break;

				case BLOCK:
					//nothing to do
					break;

                default:
    				new errorMsg( VALIDATE_PARAMETER_DATATYPE, 'Datatype is not valid for ' . $fieldNameWithPath, '' );
			}

			if( $acceptedValues != '' ) {
				$values = explode( ',', $acceptedValues );
				if( in_array( $value, $values ) == false ) {
    				new errorMsg( VALIDATE_PARAMETER_NOT_ACCEPTED_VALUE, 'Not accepted value. Accepted values: ' . $acceptedValues, '' );
				}
			}

			$this->p_result[$tag] = ( empty( $value ) == true ? $arr:$value );
		}
	}
?>