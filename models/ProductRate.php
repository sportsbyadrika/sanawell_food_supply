<?php

class ProductRate extends BaseModel
{
    public function setRate($product_id,$category_id,$rate)
    {
        return $this->db->query(
            "INSERT INTO product_rates 
             (product_id,category_id,rate)
             VALUES (?,?,?)",
            [$product_id,$category_id,$rate]
        );
    }

    public function getRatesByProduct($product_id)
    {
        return $this->db->query(
            "SELECT pr.*, c.name as category_name
             FROM product_rates pr
             JOIN customer_categories c 
             ON pr.category_id = c.id
             WHERE pr.product_id=?",
            [$product_id]
        )->fetchAll();
    }
}