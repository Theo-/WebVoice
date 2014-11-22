<?php
	# \config\lib\AYLib\Db.php

	class DB {
		const ARR = false;
		const OBJ = true;

		private $db;

		public function __construct($host, $dbname, $user, $pass) {
			try {
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$pdo_options[PDO::ATTR_EMULATE_PREPARES] = false;

				$this->db = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $user, $pass, $pdo_options);
			} catch (Exception $e) {
				exit('<strong>Problem with the database:</strong><br />' . $e->getMessage());
			}
		}

		public function insert($table, $values = array()) {
			$req = $this->db->prepare('INSERT INTO ' . $table . '(' . implode(', ', array_keys($values)) . ') VALUES(:' . implode(', :', array_keys($values)) . ');');
			$req->execute($values);

			return $this->db->lastInsertId();
		}

		public function update($query, $values = array()) {
			return $this->db->prepare('UPDATE ' . $query)->execute($values);
		}

		public function delete($query, $values = array()) {
			$this->db->prepare('DELETE FROM ' . $query)->execute($values);
		}

		public function select($query, $values = array()) {
			return new DBStatement($query, $values, $this->db);
		}

		public function lastInsertId() {
			return $this->db->lastInsertId();
		}

		/* todo Name */
		public function begin() {
			return $this->db->beginTransaction();
		}

		/* todo Name */
		public function send() {
			return $this->db->commit();
		}

		/* todo Name */
		public function back() {
			return $this->db->rollBack();
		}
	}

	class DBStatement {
		private $query;
		private $values;
		private $db;

		public function __construct($query, $values, $db) {
			$this->query  = $query;
			$this->values = $values;
			$this->db     = $db;

			return $this;
		}

		public function count() {
			return $this->select()->rowCount();
		}

		public function one($obj = DB::OBJ) {
			return $this->select()->fetch(($obj) ? PDO::FETCH_OBJ : PDO::FETCH_BOTH);
		}

		public function all($obj = DB::OBJ) {
			return $this->select()->fetchAll(($obj) ? PDO::FETCH_CLASS : PDO::FETCH_BOTH);
		}

		public function add($query, $values) {
			$this->query .= ' ' . $query;
			$this->values = array_merge($this->values, $values);

			return $this;
		}

		public function paginate($pagination) {
			$this->add('LIMIT :begin, :size', array(
				':begin' => ($pagination->actual - 1) * $pagination->nbByPage,
				':size'  => $pagination->nbByPage
			));

			return $this;
		}

		private function select() {
			$return = $this->db->prepare('SELECT ' . $this->query);
			$return->execute($this->values);

			return $return;
		}
	}
