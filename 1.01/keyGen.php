<?php
    class keyGen {
        private $p_keyType;
        private $p_noOfKeys;

        public function __construct($keyType, $noOfKeys) {
            $this->p_keyType = $keyType;
            $this->p_noOfKeys = $noOfKeys;
        }

        public function getKey() {
            $keyLenght = ($this->p_keyType == KEY_TYPE_MASTERKEY ? KEY_LENGHT_MASTERKEY : KEY_LENGHT_REGISTRATIONKEY);
            
            for ($i = 0; $i<$this->p_noOfKeys; $i++) {
                $out['key'. $i] = bin2hex(random_bytes($keyLenght));
            }

            return $out;
        }
    }
?>