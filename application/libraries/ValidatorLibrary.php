<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class ValidatorLibrary {



    public function validate(&$input_array, $schema, &$CI){

        foreach($schema as $arg => $rules){
            foreach($rules as $rule_name => $rule_value){
                $method = "validate".ucfirst($rule_name);
                $valid = $this->$method($arg, $rule_value, $input_array);

                if($valid === false) {
                    $response = array("status" => "error", "message" => "Please specify a valid value for $arg");
                    $CI->responselibrary->processResponse(400, $response);

                }
            }

        }
    }


    private function validateDefault($arg, $default_value, &$input_array){
        if(empty($input_array[$arg]))
        {
            $input_array[$arg] = $default_value;
        }
    }



    private function validateRequired($arg, $flag, &$input_array){
        if($flag == true)
        {
            //we make sure that
            if(empty($input_array[$arg])) return false;
        }
    }


    private function validateMinLength($arg, $minLenght, &$input_array){
        if(strlen($input_array[$arg]) < $minLenght) return false;
    }



    private function validateMaxLength($arg, $maxLenght, &$input_array){
        if(strlen($input_array[$arg]) > $maxLenght) return false;
    }


    private function validateType($arg, $allowedType, &$input_array){

        switch ($allowedType) {
            case "int":
                if ( strval($input_array[$arg]) !== strval(intval($input_array[$arg]))) return false;
                break;
            case "num":
                if(!is_numeric($input_array[$arg])) return false;
                break;

        }

        return true;

    }

}