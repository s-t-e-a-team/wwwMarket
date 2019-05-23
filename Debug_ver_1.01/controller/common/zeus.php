<?php
class ControllerCommonZeus extends Controller {
  public function index() {
    $data = array();
    $data['zeus'] = "test xxx";
    
    $this->load->model('common/customer');
    $ak = $this->model_common_customer->customerInfo(1);

    $this->response->addHeader('Access-Control-Allow-Origin: https://org-exam.ru');
    $this->response->addHeader('Access-Control-Allow-Credentials: true');
    $this->response->setOutput($this->load->view('common/zeus', $data));

    file_put_contents(
        "smikler_debug",
        json_encode($_GET)
        . json_encode($ak)
        . json_encode($_SERVER)
    );
  }
}
