<?php
class ControllerStartupRouter extends Controller {
	public function index() {
		if (isset($this->request->get['route']) && $this->request->get['route'] != 'startup/router') {
			$route = $this->request->get['route'];
		} else {
			$route = 'common/home';
		}

		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
		$result = $this->event->trigger('controller/' . $route . '/before', array(&$route, &$data));

		if (!is_null($result)) {
			return $result;
		}

		$action = new Action($route);
		$output = $action->execute($this->registry);

		$result = $this->event->trigger('controller/' . $route . '/after', array(&$route, &$data, &$output));

		if (!is_null($result)) {
			return $result;
		}

		return $output;
	}
}
