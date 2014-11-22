<?php
	# \config\lib\AYLib\Form.php

	class Form {
		protected $rules  = null;
		protected $name   = null;
		protected $errors = null;

		public function __construct($name, $rules = array()) {
			$this->name  = $name;
			$this->rules = $rules;
		}

		/* todo Code */
		public function isValid() {
			$valid = false;

			if (isset($_POST[$this->name])) {
				foreach ($this->rules as $k => $v) {
					foreach ($v as $kv => $vv) {
						$error = false;
						$post = self::getPost($k);

						switch ($kv) {
							case 'required':
								if (empty($post)) { $error = true; } break;
							case 'equal':
								if ($post != $vv[0]) { $error = true; } break;
							case 'minLength':
								if (strlen($post) < $vv[0]) { $error = true; } break;
							case 'maxLength':
								if (strlen(utf8_decode($post)) > $vv[0]) { $error = true; } break;
							case 'minValue':
								if ($post < $vv[0]) { $error = true; } break;
							case 'maxValue':
								if ($post > $vv[0]) { $error = true; } break;
							case 'inList':
								if (in_array($post, $vv[0])) { $error = true; } break;
							case 'notInList':
								if (!in_array($post, $vv[0])) { $error = true; } break;
							case 'regex':
								if (!preg_match($vv[0], $post)) { $error = true; } break;
							case 'filter':
								if (!filter_var($post, $vv[0])) { $error = true; } break;
							case 'checked':
								if ($post == false) { $error = true; } break;
							case 'email':
								if (!filter_var($post, FILTER_VALIDATE_EMAIL)) { $error = true; } break;
							case 'url':
								if (!filter_var($post, FILTER_VALIDATE_URL)) { $error = true; } break;
							case 'ip':
								if (!filter_var($post, FILTER_VALIDATE_IP)) { $error = true; } break;
							case 'ipv4':
								if (!filter_var($post, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) { $error = true; } break;
							case 'ipv6':
								if (!filter_var($post, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) { $error = true; } break;
						}

						if ($error) { $this->errors[] = $vv[1]; }
					}
				}

				if ($this->errors == null) { $valid = true; }
			}

			return $valid;
		}

		public function addError($message) {
			$this->errors[] = $message;

			return $this;
		}

		public function getErrors() {
			return isset($this->errors) ? $this->errors : false;
		}

		public function getName() {
			return $this->name;
		}

		public function setName($value) {
			return $this->name = $value;
		}

		public function getRules() {
			return $this->rules;
		}

		public function setRules($rules = array()) {
			return $this->rules = $rules;
		}

		public function listErrors() {
			$return = '';

			if ($this->getErrors()) {
				$return = '<ul id="errors" class="unstyled">';

				foreach ($this->getErrors() as $error) {
					$return .= '<li>' . $error . '</li>';
				}

				$return .= '</ul>';
			}

			return $return;
		}

		public static function getPost($post) {
			return isset($_POST[$post]) ? $_POST[$post] : false;
		}

		public static function getPosts() {
			$posts = func_get_args();

			foreach ($posts as $post) {
				if (!self::getPost($post)) {
					return false;
				}
			}

			return true;
		}
	}
