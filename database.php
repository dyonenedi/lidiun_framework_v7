<?php
	/**********************************************************
	* Lidiun PHP Framework 6.0 - (http://www.lidiun.com)
	*
	* @Created in 26/08/2013
	* @Author  Dyon Enedi <dyonenedi@hotmail.com>
	* @By Dyon Enedi <dyonenedi@hotmail.com>
	* @Contributor Gabriela A. Ayres Garcia <gabriela.ayres.garcia@gmail.com>
	* @License: free
	*
	**********************************************************/
	
	namespace Lidiun;
	
	Class Database
	{
		// Plubic properties
		public static $_error         = false;		
		
		// Internal properties
		private static $_errorMessage = false;
		private static $_insertId     = false;
		private static $_data         = false;
		private static $_db          = false;
		private static $_con         = false;
		private static $_sql         = false;
		private static $_result      = false;
		private static $_autoCommit  = true;

		/**
		* Connect with Database using mySqli object
		*
		*/
		static public function connect($con=false) {
			if (empty(self::$_con) || !empty($con)) {
				if (!empty($con)) {
					self::$_db['host']     = $con['host_name'];
					self::$_db['database'] = $con['db_name'];
					self::$_db['user']     = $con['user_name'];
					self::$_db['passwd']   = $con['password'];
				} else {
					if (!empty(Conf::$_conf['database'][Conf::$_conf['preset']['server']])) {
						self::$_db['host']     = Conf::$_conf['database'][Conf::$_conf['preset']['server']]['host_name'];
						self::$_db['database'] = Conf::$_conf['database'][Conf::$_conf['preset']['server']]['db_name'];
						self::$_db['user']     = Conf::$_conf['database'][Conf::$_conf['preset']['server']]['user_name'];
						self::$_db['passwd']   = Conf::$_conf['database'][Conf::$_conf['preset']['server']]['password'];
					}
				}

				self::$_con = mysqli_connect(self::$_db['host'], self::$_db['user'], self::$_db['passwd'], self::$_db['database']);
				if (!self::$_con->connect_errno) {
					self::$_con->query('SET NAMES "utf8"');
					self::$_con->query('SET character_set_con=utf8');
					self::$_con->query('SET character_set_client=utf8');
					self::$_con->query('SET character_set_results=utf8');

					return true;
				} else {
					self::$_error = true;
					self::$_errorMessage = self::$_con->connect_error;

					return false;
				}
			} else {
				return true;
			}	
		}
		
		/**
		* Execute query
		*
		*/
		static public function query($_sql, $return='boolean') {
			if (empty(self::$_con)) {
				self::connect();
			}

			self::$_sql = $_sql;
			self::$_result = self::$_con->query(self::$_sql);
			
			if (self::$_result) {
				self::$_insertId = (!empty(self::$_con->insert_id)) ? self::$_con->insert_id : false;
				
				if (self::$_autoCommit) {
					self::$_con->commit();	
				}
				
				$return = strtolower($return);
				
				if ($return == 'boolean') {
					return true;
				}

				if ($return == 'num_rows') {
					if (self::$_result->num_rows) {
						return self::$_result->num_rows;
					} else {
						return 0;
					}
				}
				
				if ($return == 'array') {
					self::$_data = array();
					$j = 0;
					while ($row = self::$_result->fetch_assoc()) {
						self::$_data[] = $row;
					}
					return self::$_data;
				} 

				if ($return == 'object') {
					while($row = self::$_result->fetch_object()) {
						self::$_data[] = $row;
					}

					return self::$_data;
				}
			} else {
				self::$_error = true;
				self::$_errorMessage = self::$_con->error.'<br>'.self::$_sql;
				
				if (self::$_autoCommit) {
					self::$_con->rollback();
				}
				
				return false;
			}
		}

		/**
		* autocommit
		*
		*/
		static public function autocommit($autocommit) {
			if ($autocommit) {
				self::$_autoCommit = false;
				self::$_con->autocommit(true);
			} else {
				self::$_autoCommit = false;
				self::$_con->autocommit(false);
			}
		}

		/**
		* If _autoCommit configuration is false, you need commit manually with this method
		*
		*/
		static public function commit() {
			if (!self::$_autoCommit) {
				self::$_con->commit();
				self::close();
			}
		}

		/**
		* If _autoCommit configuration is false, you need rollback manually with this method
		*
		*/
		static public function rollback() {
			if (!self::$_autoCommit) {
				self::$_con->rollback();
				self::close();
			}
		}

		/**
		* Return insert id in DB
		*
		*/
		static public function getInsertId() {
			return self::$_insertId;
		}

		/**
		* Return error message DB
		*
		*/
		static public function getErrorMessage() {
			return self::$_errorMessage;
		}
		/**
		* Close connection with database wath is very important
		*
		*/

		static public function close() {
			if (!empty(self::$_con)) {
				self::$_autoCommit = true;
				self::$_con->close();
				self::$_con = null;
			}
		}
	}