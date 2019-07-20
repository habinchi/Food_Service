<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class ResponseLibrary {


    /**
     * @param $payload
     * @param $response_code
     */
    public function processResponse($response_code, $payload){
        $CI = &get_instance();


        $CI->output->set_status_header($response_code);
        echo json_encode($payload);
        die;

    }

}