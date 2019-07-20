<?php

class FoodModel extends CI_Model
{

    public function __construct(){

        // Call the parent constructor
        parent::__construct();

    }

    public function getFood($food_id)
    {
        try{
            $sql = "SELECT * FROM foods WHERE food_id = ?";

            $query = $this->db->query($sql, array($food_id));

            if($query->num_rows() > 0) return $query->row();
            return  null;


        }
        catch(Exception $e){
            return  null;

        }
    }


    public function addFood($name, $description, $prep_time, $category_id, $store_id, $base_price)
    {
        try{

            $data = array(
                'name' => $name,
                'description' => $description,
                'prep_time' => $prep_time,
                'category_id' => $category_id,
                'store_id' => $store_id,
                'base_price' => $base_price,
                );


            $this->db->insert('foods', $data);
            $food_id = $this->db->insert_id();


            return $food_id;
        }
        catch(Exception $e){
            return null;
        }

    }



    public function removeFood($food_id)
    {
        try{
            $this->db->delete('foods', array('food_id' => $food_id));
            return true;

        }
        catch(Exception $e){
            return false;


        }

    }



    public function updateFood($food_id, $name, $description, $prep_time, $category_id, $store_id, $base_price)
    {
        try{

            $data = array(
                'name' => $name,
                'description' => $description,
                'prep_time' => $prep_time,
                'category_id' => $category_id,
                'store_id' => $store_id,
                'base_price' => $base_price,
            );

            $this->db->where('food_id', $food_id);
            $this->db->update('foods', $data);



        }
        catch(Exception $e){
            return false;
        }

    }



    public function getFoodList($start, $rows)
    {
        try{
            $sql = "SELECT * FROM foods";

            $query = $this->db->query($sql, array(), $start, $rows);

            return $query->result();
        }
        catch(Exception $e){
            return false;

        }
    }



    public function getRestaurantFoodList($store_id, $start, $rows)
    {
        try{
            $sql = "SELECT * FROM foods WHERE store_id = ?";

            $query = $this->db->query($sql, array($store_id), $start, $rows);

            return $query->result();
        }
        catch(Exception $e){
            return false;

        }
    }

}