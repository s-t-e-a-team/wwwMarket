<?php
namespace Session;
final class DB {
	public $data = array();
	public $expire = array();

	public function __construct($registry) {
		$this->db = $registry->get('db');

		register_shutdown_function('session_write_close');

		$this->expire = ini_get('session.gc_maxlifetime');
	}

	public function open() {
		if ($this->db){
			return true;
		} else {
			return false;
		}
	}

	public function close() {
		return true;
	}

	public function read($session_id) {
		$query = $this->db->query("SELECT `data`
																FROM session
																WHERE session_id = '" . $this->db->escape($session_id) . "' AND expire > " . (int)time());

		if ($query->num_rows) {
			return $query->row['data'];
		} else {
			return false;
		}
	}

	public function write($session_id, $data) {
		$this->db->query("REPLACE INTO SET `data` = '" . $this->db->escape($data)
																	. "', expire = '" . $this->db->escape(date('Y-m-d H:i:s', time() + $this->expire))
																	. "' FROM session WHERE session_id = '" . $this->db->escape($session_id) . "' AND expire > " . (int)time());

		return true;
	}

	public function destroy($session_id) {
		$this->db->query("DELETE FROM session WHERE session_id = '" . $this->db->escape($session_id) . "'");

		return true;
	}

	public function gc($expire) {
		$this->db->query("DELETE FROM session WHERE expire < " . ((int)time() + $expire));

		return true;
	}
}
