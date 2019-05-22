<?php
class ControllerCommonHome extends Controller {
  public function index() {
    $this->load->model('common/customer');
    $customer_info = $this->model_common_customer->customerInfo(1);

    echo $customer_info['firstname'] . " " . $customer_info['lastname'];
  }
}
