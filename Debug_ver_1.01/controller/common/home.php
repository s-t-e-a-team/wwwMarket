<?php
class ControllerCommonHome extends Controller {
  public function index() {
    $this->load->model('common/customer');
    $customer_info = $this->model_common_customer->customerInfo(1);

    $data = array();
    $data['firstname'] = $customer_info['firstname'];
    $data['lastname']  = $customer_info['lastname'];

    $this->response->addHeader('Access-Control-Allow-Origin: https://org-exam.ru');
    $this->response->setOutput($this->load->view('common/home', $data));
  }
}
