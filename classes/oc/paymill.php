<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Paymill helper class
 *
 * @package    OC
 * @category   Payment
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 */

class OC_Paymill {
    

    /**
     * formats an amount to the correct format for paymill. 2.50 == 250
     * @param  float $amount 
     * @return string         
     */
    public static function money_format($amount)
    {
        return $amount*100;
    }

    //
    //
    //Functions from https://github.com/paymill/paybutton-examples
    //
    //

    /**
     * Perform HTTP request to REST endpoint
     *
     * @param string $action
     * @param array  $params
     * @param string $privateApiKey
     *
     * @return array
     */
    public static function requestApi( $action = '', $params = array(), $privateApiKey )
    {
        $curlOpts = array(
            CURLOPT_URL            => "https://api.paymill.com/v2/" . $action,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_USERAGENT      => 'Paymill-php/0.0.2',
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_CAINFO         =>  COMMONPATH.'config/paymill.crt',
        );

        $curlOpts[ CURLOPT_POSTFIELDS ] = http_build_query( $params, null, '&' );
        $curlOpts[ CURLOPT_USERPWD ] = $privateApiKey . ':';

        $curl = curl_init();
        curl_setopt_array( $curl, $curlOpts );
        $responseBody = curl_exec( $curl );
        $responseInfo = curl_getinfo( $curl );
        if ( $responseBody === false ) {
            $responseBody = array( 'error' => curl_error( $curl ) );
        }
        curl_close( $curl );

        if ( 'application/json' === $responseInfo[ 'content_type' ] ) {
            $responseBody = json_decode( $responseBody, true );
        }

        return array(
            'header' => array(
                'status' => $responseInfo[ 'http_code' ],
                'reason' => null,
            ),
            'body'   => $responseBody
        );
    }

    /**
     * Perform API and handle exceptions
     *
     * @param        $action
     * @param array  $params
     * @param string $privateApiKey
     *
     * @return mixed
     */
    public static function request( $action, $params = array(), $privateApiKey )
    {
        if ( !is_array( $params ) ) {
            $params = array();
        }

        $responseArray = self::requestApi( $action, $params, $privateApiKey );
        $httpStatusCode = $responseArray[ 'header' ][ 'status' ];
        if ( $httpStatusCode != 200 ) {
            $errorMessage = 'Client returned HTTP status code ' . $httpStatusCode;
            if ( isset( $responseArray[ 'body' ][ 'error' ] ) ) {
                $errorMessage = $responseArray[ 'body' ][ 'error' ];
            }
            $responseCode = '';
            if ( isset( $responseArray[ 'body' ][ 'response_code' ] ) ) {
                $responseCode = $responseArray[ 'body' ][ 'response_code' ];
            }

            return array( "data" => array(
                "error"            => $errorMessage,
                "response_code"    => $responseCode,
                "http_status_code" => $httpStatusCode
            ) );
        }

        return $responseArray[ 'body' ][ 'data' ];
    }

}