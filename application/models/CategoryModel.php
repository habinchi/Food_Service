<?php

class CategoryModel extends CI_Model
{

    public function __construct(){

        // Call the parent constructor
        parent::__construct();

    }

    public function getCategory($category_id)
    {
        try{
            $sql = "SELECT * FROM categories WHERE category_id = ?";

            $query = $this->db->query($sql, array($category_id));

            if($query->num_rows() > 0) return $query->row();
            return  null;


        }
        catch(Exception $e){
            return  null;

        }
    }


    public function addCategory($name, $description, $store_id, $sort)
    {
        try{

            $data = array(
                'name' => $name,
                'description' => $description,
                'store_id' => $store_id,
                'sort' => $sort,
                );


            $this->db->insert('categories', $data);
            $category_id = $this->db->insert_id();


            return $category_id;
        }
        catch(Exception $e){
            return null;
        }

    }



    public function removeCategory($category_id)
    {
        try{
            $this->db->delete('categories', array('category_id' => $category_id));
            return true;

        }
        catch(Exception $e){
            return false;


        }

    }



    public function updateCategory($category_id, $name, $description, $store_id, $sort)
    {
        try{

            $data = array(
                'name' => $name,
                'description' => $description,
                'store_id' => $store_id,
                'sort' => $sort,
            );

            $this->db->where('category_id', $category_id);
            $this->db->update('categories', $data);



        }
        catch(Exception $e){
            return false;
        }

    }



    public function getCategoryList($start, $rows)
    {
        try{
            $sql = "SELECT * FROM categories";

            $query = $this->db->query($sql, array(), $start, $rows);

            return $query->result();
        }
        catch(Exception $e){
            return false;

        }
    }



    public function getRestaurantCategoryList($store_id, $start, $rows)
    {
        try{
            $sql = "SELECT * FROM categories WHERE store_id = ?";

            $query = $this->db->query($sql, array($store_id), $start, $rows);

            return $query->result();
        }
        catch(Exception $e){
            return false;

        }
    }

}