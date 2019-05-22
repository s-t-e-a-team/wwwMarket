<?php
class ControllerStartupSeoUrl extends Controller {
	public function index() {
		$this->url->addRewrite($this);

		if (isset($this->request->get['_route_'])) {
			$parts = explode('/', $this->request->get['_route_']);

			if (utf8_strlen(end($parts)) == 0) {
				array_pop($parts);
			}
			foreach ($parts as $part) {
				$query = $this->db->query("SELECT *
																		FROM url_alias
																		WHERE keyword = '" . $this->db->escape($part) . "'");
				if ($query->num_rows) {
					$url = explode('=', $query->row['query']);

					if ($query->row['query']) {
						$this->request->get['route'] = $query->row['query'];
					}
				} else {
					$this->request->get['route'] = 'error/not_found';

					break;
				}
			}
		}
	}

	public function rewrite($link) {
		$url_info = parse_url(str_replace('&amp;', '&', $link));
		$url = '';
		$data = array();
		parse_str($url_info['query'], $data);
		foreach ($data as $key => $value) {
			if (isset($data['route'])) {
        if ($value == 'common/home') {
          $query = $this->db->query("SELECT * FROM url_alias WHERE `query` = '" . $this->db->escape($value) . "'");
          if ($query->num_rows && $query->row['keyword']) {
              $url .= '/' . $query->row['keyword'];
              unset($data[$key]);
          } elseif ($query->num_rows) {
              $url .= "void";
          }
				}
			}
		}

		if ($url) {
      if ($url == "void") {
          return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '');
      } else {
				unset($data['route']);
				$query = '';
				if ($data) {
					foreach ($data as $key => $value) {
						$query .= '&' . rawurlencode((string)$key) . '=' . rawurlencode((is_array($value) ? http_build_query($value) : (string)$value));
					}
					if ($query) {
						$query = '?' . str_replace('&', '&amp;', trim($query, '&'));
					}
				}
				return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
      }
		} else {
			return $link;
		}
	}
}
