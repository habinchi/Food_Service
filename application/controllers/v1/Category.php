<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('CategoryModel');
        $this->load->library('ResponseLibrary');
        $this->load->library('ValidatorLibrary');
    }



    public function get_category(){

        $schema['category_id'] = array("required" => true, "type" => "int");
        $this->validatorlibrary->validate($_GET, $schema, $this);

        $category_id = $this->input->get('category_id');
        $model_response1 = $this->CategoryModel->getCategory($category_id);

        $response = array("status" => "error", "message" => "the specified category id does not exists");
        if(empty($model_response1)) $this->responselibrary->processResponse(400, $response);

        $payload["category_id"] = $model_response1->category_id;
        $payload["name"] = $model_response1->name;
        $payload["description"] = $model_response1->description;
        $payload["store_id"] = $model_response1->store_id;
        $payload["sort"] = $model_response1->sort;


        $response = array("status" => "success", "payload" => $payload);


        $this->responselibrary->processResponse(200, $response);




	}


	public function add_category(){

        $schema['name'] = array("required" => true, "minLength" => 5, "maxLength" => 50);
        $schema['description'] = array("required" => true);
        $schema['store_id'] = array("required" => true, "type" => "int");
        $schema['sort'] = array("default" => 1, "type" => "int");  //optional

        $this->validatorlibrary->validate($_POST, $schema, $this);

        //we input into the database...
        $name = $_POST['name'];
        $description = $_POST['description'];
        $store_id = $_POST['store_id'];
        $sort = $_POST['sort'];


        $model_response1 = $this->CategoryModel->addCategory($name, $description, $store_id, $sort);

        if($model_response1 == null){
            $response = array("status" => "error", "message" => "Category was not created. Something went wrong");
            $this->responselibrary->processResponse(500, $response);
        }

        $payload["category_id"] = $model_response1;
        $response = array("status" => "success", "payload" => $payload);
        $this->responselibrary->processResponse(200, $response);

    }

    public function remove_category(){
        $schema['category_id'] = array("required" => true, "type" => "int");
        $this->validatorlibrary->validate($_GET, $schema, $this);

        $category_id = $this->input->get('category_id');
        $model_response1 = $this->CategoryModel->removeCategory($category_id);

        $response = array("status" => "error", "message" => "Category was not removed. Something went wrong");
        if($model_response1 === false) $this->responselibrary->processResponse(500, $response);

        $payload = array();
        $response = array("status" => "success", "payload" => $payload);
        $this->responselibrary->processResponse(200, $response);
    }

    public function update_category(){
        $schema['category_id'] = array("required" => true, "type" => "int");
        $schema['name'] = array("required" => true, "minLength" => 5, "maxLength" => 50);
        $schema['description'] = array("required" => true);
        $schema['store_id'] = array("required" => true, "type" => "int");
        $schema['sort'] = array("default" => 1, "type" => "int");  //optional
        $this->validatorlibrary->validate($_POST, $schema, $this);

        //we input into the database...
        $category_id = $_POST['category_id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $store_id = $_POST['store_id'];
        $sort = $_POST['sort'];


        $model_response1 = $model_response1 = $this->CategoryModel->updateCategory($category_id, $name, $description, $store_id, $sort);

        if($model_response1 === false){
            $response = array("status" => "error", "message" => "Category was not updated. Something went wrong");
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
    public function get_category_list(){
        $schema['start'] = array("required" => true);
        $schema['rows'] = array("required" => true, "type" => "int");
        $this->validatorlibrary->validate($_GET, $schema, $this);

        $start = $this->input->get('start');
        $rows = $this->input->get('rows');

        $model_response1 = $this->CategoryModel->getCategoryList($start, $rows);

        if($model_response1 ===  false){
            $response = array("status" => "error", "message" => "Something went wrong");
            $this->responselibrary->processResponse(500, $response);
        }

        $payload = array();
        foreach($model_response1 as $category){
            $payload[] = array('category_id' => $category->category_id,
                'name' => $category->name,
                'description' => $category->description,
                'store_id' => $category->store_id,
                'sort' => $category->sort);
        }

        $response = array("status" => "success", "payload" => $payload);
        $this->responselibrary->processResponse(200, $response);
    }



    public function get_restaurant_category_list(){
        $schema['store_id'] = array("required" => true, "type" => "int");
        $schema['start'] = array("required" => true);
        $schema['rows'] = array("required" => true, "type" => "int");
        $this->validatorlibrary->validate($_GET, $schema, $this);

        $store_id = $this->input->get('store_id');
        $start = $this->input->get('start');
        $rows = $this->input->get('rows');

        $model_response1 = $this->CategoryModel->getRestaurantCategoryList($store_id, $start, $rows);

        if($model_response1 ===  false){
            $response = array("status" => "error", "message" => "Something went wrong");
            $this->responselibrary->processResponse(500, $response);
        }

        $payload = array();
        foreach($model_response1 as $category){
            $payload[] = array('category_id' => $category->category_id,
                'name' => $category->name,
                'description' => $category->description,
                'store_id' => $category->store_id,
                'sort' => $category->sort);
        }

        $response = array("status" => "success", "payload" => $payload);
        $this->responselibrary->processResponse(200, $response);
    }







}

