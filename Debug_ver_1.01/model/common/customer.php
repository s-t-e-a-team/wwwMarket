<?php
class ModelCommonCustomer extends Model {
  public function customerInfo($customer_id) {
    $result = $this->db->query("SELECT *
                                FROM customer
                                WHERE customer_id = " . (int)$customer_id);
    return $result->row;
  }
}
