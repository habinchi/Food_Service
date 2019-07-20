<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Food extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('FoodModel');
        $this->load->library('ResponseLibrary');
        $this->load->library('ValidatorLibrary');
    }



    public function get_food(){

        $schema['food_id'] = array("required" => true, "type" => "int");
        $this->validatorlibrary->validate($_GET, $schema, $this);

        $food_id = $this->input->get('food_id');
        $model_response1 = $this->FoodModel->getFood($food_id);

        $response = array("status" => "error", "message" => "the specified food id does not exists");
        if(empty($model_response1)) $this->responselibrary->processResponse(400, $response);

        $payload["product_id"] = $model_response1->food_id;
        $payload["name"] = $model_response1->name;
        $payload["description"] = $model_response1->description;
        $payload["base_price"] = $model_response1->base_price;
        $payload["prep_time"] = $model_response1->prep_time;


        $response = array("status" => "success", "payload" => $payload);


        $this->responselibrary->processResponse(200, $response);




	}


	public function add_food(){

        $schema['name'] = array("required" => true, "minLength" => 5, "maxLength" => 50);
        $schema['base_price'] = array("required" => true, "type" => "num");
        $schema['prep_time'] = array("required" => true, "type" => "int");
        $schema['category_id'] = array("required" => true, "type" => "int");
        $schema['store_id'] = array("required" => true, "type" => "int");
        $this->validatorlibrary->validate($_POST, $schema, $this);

        //we input into the database...
        $name = $_POST['name'];
        $description = $_POST['description'];
        $prep_time = $_POST['prep_time'];
        $category_id = $_POST['category_id'];
        $store_id = $_POST['store_id'];
        $base_price = $_POST['base_price'];


        //we have to make sure that the store exists


        //and the category exists and belongs to this store...

        $model_response1 = $this->FoodModel->addFood($name, $description, $prep_time, $category_id, $store_id, $base_price);

        if($model_response1 == null){
            $response = array("status" => "error", "message" => "Food was not created. Something went wrong");
            $this->responselibrary->processResponse(500, $response);
        }

        $payload["product_id"] = $model_response1;
        $response = array("status" => "success", "payload" => $payload);
        $this->responselibrary->processResponse(200, $response);

    }

    public function remove_food(){
        $schema['food_id'] = array("required" => true, "type" => "int");
        $this->validatorlibrary->validate($_GET, $schema, $this);

        $food_id = $this->input->get('food_id');
        $model_response1 = $this->FoodModel->removeFood($food_id);

        $response = array("status" => "error", "message" => "Food was not removed. Something went wrong");
        if($model_response1 === false) $this->responselibrary->processResponse(500, $response);

        $payload = array();
        $response = array("status" => "success", "payload" => $payload);
        $this->responselibrary->processResponse(200, $response);
    }

    public function update_food(){
        $schema['food_id'] = array("required" => true, "type" => "int");
        $schema['name'] = array("required" => true, "minLength" => 5, "maxLength" => 50);
        $schema['base_price'] = array("required" => true, "type" => "num");
        $schema['prep_time'] = array("required" => true, "type" => "int");
        $schema['category_id'] = array("required" => true, "type" => "int");
        $schema['store_id'] = array("required" => true, "type" => "int");
        $this->validatorlibrary->validate($_POST, $schema, $this);

        //we input into the database...
        $food_id = $_POST['food_id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $prep_time = $_POST['prep_time'];
        $category_id = $_POST['category_id'];
        $store_id = $_POST['store_id'];
        $base_price = $_POST['base_price'];


        $model_response1 = $this->FoodModel->updateFood($food_id, $name, $description, $prep_time, $category_id, $store_id, $base_price);

        if($model_response1 === false){
            $response = array("status" => "error", "message" => "Food was not updated. Something went wrong");
            $this->responselibrary->processResponse(500, $response);
        }

        $payload = array();
        $response = array("status" => "success", "payload" => $payload);
        $this->responselibrary->processResponse(200, $response);
    }



    /*
     * @params start
     * @param rows
     */
    public function get_food_list(){
        $schema['start'] = array("required" => true);
        $schema['rows'] = array("required" => true, "type" => "int");
        $this->validatorlibrary->validate($_GET, $schema, $this);

        $start = $this->input->get('start');
        $rows = $this->input->get('rows');

        $model_response1 = $this->FoodModel->getFoodList($start, $rows);

        if($model_response1 ===  false){
            $response = array("status" => "error", "message" => "Something went wrong");
            $this->responselibrary->processResponse(500, $response);
        }

        $payload = array();
        foreach($model_response1 as $food){
            $payload[] = array('food_id' => $food->food_id, 'name' => $food->name, 'description' => $food->description, 'base_price' => $food->base_price, 'prep_time' => $food->prep_time, 'category_id' => $food->category_id, 'store_id' => $food->store_id);
        }

        $response = array("status" => "success", "payload" => $payload);
        $this->responselibrary->processResponse(200, $response);
    }



    public function get_restaurant_food_list(){
        $schema['store_id'] = array("required" => true, "type" => "int");
        $schema['start'] = array("required" => true);
        $schema['rows'] = array("required" => true, "type" => "int");
        $this->validatorlibrary->validate($_GET, $schema, $this);

        $store_id = $this->input->get('store_id');
        $start = $this->input->get('start');
        $rows = $this->input->get('rows');

        $model_response1 = $this->FoodModel->getRestaurantFoodList($store_id, $start, $rows);

        if($model_response1 ===  false){
            $response = array("status" => "error", "message" => "Something went wrong");
            $this->responselibrary->processResponse(500, $response);
        }

        $payload = array();
        foreach($model_response1 as $food){
            $payload[] = array('food_id' => $food->food_id, 'name' => $food->name, 'description' => $food->description, 'base_price' => $food->base_price, 'base_price' => $food->prep_time, 'category_id' => $food->category_id, 'store_id' => $food->store_id);
        }

        $response = array("status" => "success", "payload" => $payload);
        $this->responselibrary->processResponse(200, $response);
    }


    /*
     * Receives the restaurant id and the amount of food to return
     *
    */

    public function get_most_popular_food_list(){


    }




}

