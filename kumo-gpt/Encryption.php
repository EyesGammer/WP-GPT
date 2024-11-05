<?php

namespace Kumo;

class Encryption {

    private string $key;
    private string $salt;

    public function __construct() {
        $this->key = $this->get_default_key();
        $this->salt = $this->get_default_salt();
    }

    /**
     * Encrypt input value using key and salt
     *
     * @param string $value
     * @return string
     */
    public function encrypt( string $value ) : string {
        if( ! extension_loaded( 'openssl' ) ) return $value;
        $method = 'aes-256-ctr';
        $ivlen = openssl_cipher_iv_length( $method );
        $iv = openssl_random_pseudo_bytes( $ivlen );
        $raw_value = openssl_encrypt( $value . $this->salt, $method, $this->key, 0, $iv );
        if( ! $raw_value ) return $value;
        return base64_encode( $iv . $raw_value );
    }

    /**
     * Decrypt input value using key and salt
     *
     * @param string $raw_value
     * @return string
     */
    public function decrypt( string $raw_value ) : string {
        if( ! extension_loaded( 'openssl' ) ) return $raw_value;
        $raw_value = base64_decode( $raw_value, true );
        $method = 'aes-256-ctr';
        $ivlen = openssl_cipher_iv_length( $method );
        $iv = substr( $raw_value, 0, $ivlen );
        $raw_value = substr( $raw_value, $ivlen );
        $value = openssl_decrypt( $raw_value, $method, $this->key, 0, $iv );
        if( ! $value || substr( $value, - strlen( $this->salt ) ) !== $this->salt ) return $raw_value;
        return substr( $value, 0, - strlen( $this->salt ) );
    }

    /**
     * Get key if set
     *
     * @return string
     */
    private function get_default_key() : string {
        if( defined( 'KUMO_LOGGED_IN_KEY' ) && KUMO_LOGGED_IN_KEY !== '' ) return KUMO_LOGGED_IN_KEY;
        return 'sc8aqh4MQAuVPHtQP8chgmXgwABUtuEFmKHB2fU8ax2e9YvVHR'; // Random base key if not set
    }

    /**
     * Get salt if set
     *
     * @return string
     */
    private function get_default_salt() : string {
        if( defined( 'KUMO_LOGGED_IN_SALT' ) && KUMO_LOGGED_IN_SALT !== '' ) return KUMO_LOGGED_IN_SALT;
        return 'M6EX4Gb0Psu76NXjYpaUGwWAgHkuhF39OZBmJtWmuvHmGE7enh'; // Random base salt if not set
    }

}