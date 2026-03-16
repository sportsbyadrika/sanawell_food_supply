<?php

class BillModel extends BaseModel
{

public function createBill($data)
{

$sql="INSERT INTO bills
(customer_id,route_id,bill_from_date,bill_to_date,total_amount,status)

VALUES(?,?,?,?,?,?)";

$this->db->query($sql,[

$data['customer_id'],
$data['route_id'],
$data['bill_from_date'],
$data['bill_to_date'],
$data['total_amount'],
$data['status']

]);

return $this->db->lastInsertId();

}

public function addBillItem($data)
{

$sql="INSERT INTO bill_items
(bill_id,product_id,qty,rate,amount)

VALUES(?,?,?,?,?)";

$this->db->query($sql,[

$data['bill_id'],
$data['product_id'],
$data['qty'],
$data['rate'],
$data['amount']

]);

}

}