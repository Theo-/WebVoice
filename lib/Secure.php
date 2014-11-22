<?php
	# \config\lib\AYLib\Secure.php

	class Secure {
		public static $salt = 'Webvoice-x-ari-1t40;[].=';

		public static function password($pass, $algo = 'sha256') {
			return hash($algo, self::$salt . $pass);
		}

		/* alias */
		public static function html($text) {
			return htmlentities($text);
		}

		/* alias */
		public static function slash($text) {
			return addslashes($text);
		}

		/* alias */
		public static function unslash($text) {
			return stripslashes($text);
		}
	}