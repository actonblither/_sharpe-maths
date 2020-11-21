<?php

class _db{
	private $_sql;
	private $_d;
	private $_f;
	private $_w;
	private $_wf;
	private $_stmt;
	private $_pdo;
	private $_ini_file_pth;
	private $_log_name;
	private $_log_name_prefix;

	public function __construct(){
		$this->_log_name = '_php_debug.log';
		$this->_log_name_prefix = '';
		$this->_pdo = $this->_fetch_db_connection();
	}

	private function _fetch_db_connection(){
		$_pdo = false;
		$ini_array = parse_ini_file(__s_cfg_ini_pth__, true);
		$_host			= $ini_array['database']['host'];
		$_username 	= $ini_array['database']['username'];
		$_password 	= $ini_array['database']['password'];
		$_dbname 		= $ini_array['database']['dbname'];
		$_charset 	= $ini_array['database']['charset'];

		// NB DO NOT ADD SPACES IN THE DSN STRING FOLLOWING. THIS CAUSES AN ERROR!
		$dsn = "mysql:host=$_host; dbname=$_dbname; charset=$_charset";
		$options = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES   => false,
		];
		try {
			$_pdo = new PDO($dsn, $_username, $_password, $options);
		} catch (PDOException $e) {
			if ($_pdo === false){
				$this->_db_logger($e, 'ERROR CONNECTING TO THE DATABASE SERVER');
			}
		}
		return $_pdo;
	}

	public function _insert($_t, $_d, $_f) {
		if ( empty( $_t ) || empty( $_d )) {return false;}
		$_sql = $this->create_insert_sql_from_data($_t, $_d);
		$this->_set_data_globals($_sql, $_d, $_f);
		//_lg($_sql);_lg($_d);_lg($_f);
		$this->_pdo_prepare('PDO PREPARE ERROR in method _insert');
		$this->_pdo_bind_values('PDO BIND VALUE ERROR in method _insert');
		$this->_pdo_execute('PDO EXCECUTE ERROR in method _insert');
		$_ret = $this->_pdo->lastInsertId();
		if ($_ret){
			return $_ret;
		}
		return true;
	}

	public function _insert_sql($_sql, $_d, $_f) {
		if ( empty( $_sql ) || empty( $_d )) {return false;}
		$this->_set_data_globals($_sql, $_d, $_f);
		//_lg($_sql);_lg($_d);_lg($_f);
		$this->_pdo_prepare('PDO PREPARE ERROR in method _insert_sql');
		$this->_pdo_bind_values('PDO BIND VALUE ERROR in method _insert_sql');
		$this->_pdo_execute('PDO EXCECUTE ERROR in method _insert_sql');
		$_ret = $this->_pdo->lastInsertId();
		if ($_ret){
			return $_ret;
		}
		return true;
	}

	public function _update($_t, $_d, $_f, $_w, $_wf) {
		if ( empty( $_t ) || empty( $_d ) ) {return false;}
		$_sql = $this->create_update_sql_from_data($_t, $_d, $_w);
		$_d = array_merge($_d, $_w);
		$_f = array_merge($_f, $_wf);

		$this->_set_data_globals($_sql, $_d, $_f);
		//_lg($_sql);_lg($_d);_lg($_f);
		$this->_pdo_prepare('PDO PREPARE ERROR in method _update');
		$this->_pdo_bind_values('PDO BIND VALUE ERROR in method _update');
		$res = $this->_pdo_execute('PDO EXCECUTE ERROR in method _update');
		return $res;
	}

	public function _update_sql($_sql, $_d, $_f) {
		$this->_set_data_globals($_sql, $_d, $_f);
		//_lg($_sql);_lg($_d);_lg($_f);
		$this->_pdo_prepare('PDO PREPARE ERROR in method _update_sql');
		$this->_pdo_bind_values('PDO BIND VALUE ERROR in method _update_sql');
		$res = $this->_pdo_execute('PDO EXCECUTE ERROR in method _update_sql');
		return $res;
	}

	public function _delete($_t, $_d, $_f) {
		$_sql = $this->create_delete_sql_from_data($_t, $_d);
		$this->_set_data_globals($_sql, $_d, $_f);
		//_lg($_sql);_lg($_d);_lg($_f);
		$this->_pdo_prepare('PDO PREPARE ERROR in method _delete');
		$this->_pdo_bind_values('PDO BIND VALUE ERROR in method _delete');
		$this->_pdo_execute('PDO EXCECUTE ERROR in method _delete');
		return true;
	}

	public function _delete_sql($_sql, $_d, $_f) {
		$this->_set_data_globals($_sql, $_d, $_f);
		//_lg($_sql);_lg($_d);_lg($_f);
		$this->_pdo_prepare('PDO PREPARE ERROR in method _delete');
		$this->_pdo_bind_values('PDO BIND VALUE ERROR in method _delete');
		$this->_pdo_execute('PDO EXCECUTE ERROR in method _delete');
		return true;
	}

	public function _fetch_db_datum_p($_sql, $_d, $_f) {
		$this->_set_data_globals($_sql, $_d, $_f);
		//_lg($this->_sql);_lg($this->_d);_lg($this->_f);
		$this->_pdo_prepare('PDO PREPARE ERROR in method _fetch_db_datum_p');
		$this->_pdo_bind_values('PDO BIND VALUE ERROR in method _fetch_db_datum_p');
		$this->_pdo_execute('PDO EXCECUTE ERROR in method _fetch_db_datum_p');
		$_result = $this->_pdo_fetch_num_array('PDO FETCH ERROR in method _fetch_db_datum_p');
		if (isset($_result[0])){
			return $_result[0];
		}else{
			return false;
		}
	}

	public function _fetch_db_row_p($_sql, $_d, $_f) {
		$this->_set_data_globals($_sql, $_d, $_f);
		$this->_pdo_prepare('PDO PREPARE ERROR in method _fetch_db_row_p');
		$this->_pdo_bind_values('PDO BIND VALUE ERROR in method _fetch_db_row_p');
		$this->_pdo_execute('PDO EXCECUTE ERROR in method _fetch_db_row_p');
		return $this->_pdo_fetch('PDO EXCECUTE ERROR in method _fetch_db_row_p');
	}

	public function _fetch_db_rows_p($_sql, $_d, $_f) {
		$this->_set_data_globals($_sql, $_d, $_f);
		//_lg($_sql, '_fetch_db_rows_p');_lg($_d);
		$this->_pdo_prepare('PDO PREPARE ERROR in method _fetch_db_rows_p');
		$this->_pdo_bind_values('PDO BIND VALUE ERROR in method _fetch_db_rows_p');
		$this->_pdo_execute('PDO EXCECUTE ERROR in method _fetch_db_rows_p');
		return $this->_pdo_fetch_all('PDO FETCH ERROR in method _fetch_db_rows_p');
	}

	public function _fetch_db_datum($_sql){
		if (!empty($_sql)){
			$this->_set_data_globals($_sql);
			$this->_pdo_prepare('PDO PREPARE ERROR in method _fetch_db_datum');
			$this->_pdo_execute('PDO EXCECUTE ERROR in method _fetch_db_datum');
			$_result = $this->_pdo_fetch_num_array('PDO FETCH ERROR in method _fetch_db_datum');
			return $_result[0];
		}else{
			return false;
		}
	}

	public function _fetch_db_row($_sql){
		if (!empty($_sql)){
			$this->_set_data_globals($_sql);
			$this->_pdo_prepare('PDO PREPARE ERROR in method _fetch_db_row');
			$this->_pdo_execute('PDO EXCECUTE ERROR in method _fetch_db_row');
			$_result = $this->_pdo_fetch('PDO FETCH ERROR in method _fetch_db_row');
			return $_result;
		}else{
			return false;
		}
	}

	public function _fetch_db_rows($_sql){
		if (!empty($_sql)){
			$this->_set_data_globals($_sql);
			$this->_pdo_prepare('PDO PREPARE ERROR in method _fetch_db_rows');
			$this->_pdo_execute('PDO EXCECUTE ERROR in method _fetch_db_rows');
			$_result = $this->_pdo_fetch_all('PDO FETCH ERROR in method _fetch_db_rows');
			return $_result;
		}else{
			return false;
		}
	}

	public function _pdo_query($_sql){
		if (!empty($_sql)){
			$this->_set_data_globals($_sql);
			$this->_pdo_prepare('PDO PREPARE ERROR in method _pdo_query');
			$this->_pdo_execute('PDO EXCECUTE ERROR in method _pdo_query');
			$count = $this->_stmt->rowCount();
			return $count;
		}else{
			return false;
		}
	}

	public function _fetch_field_from_tbl_named_id($field, $tbl, $id, $idfield = 'id'){
		if (!empty($field) && !empty($tbl) && !empty($id)){
			$sql = 'select '.$field.' from '.$tbl.' where '.$idfield.' = :'.$idfield;
			$_d = array($idfield => $id);
			$_f = array('i');
			try{
				$_result = $this->_fetch_db_datum_p($sql, $_d, $_f);
			}catch (Exception $e) {
				//_lg($sql, 'SQL');_lg($_d, '_D');_lg($_result, 'RESULT');
			}
			return $_result;
		}else{
			return false;
		}
	}

	public function _fetch_field_from_tbl_id($field, $tbl, $id){
		if (!empty($field) && !empty($tbl) && !empty($id)){
			$sql = 'select '.$field.' from '.$tbl.' where id = :id';
			$_d = array('id' => $id);
			$_f = array('i');
			try{
				$_result = $this->_fetch_db_datum_p($sql, $_d, $_f);
			}catch (Exception $e) {
				//_lg($sql, 'SQL');_lg($_d, '_D');_lg($_result, 'RESULT');
			}
			return $_result;
		}else{
			return false;
		}
	}

	private function _pdo_fetch_num_array($_e_message){
		$result = false;
		if (!empty($this->_stmt)){
			try{
				$result = $this->_stmt->fetch(PDO::FETCH_NUM);
			}catch (PDOException $e){
				if ($result === false){
					$this->_db_logger($e, $_e_message, $this->_sql, $this->_d, $this->_f);
				}
			}
		}
		return $result;
	}

	private function _pdo_fetch($_e_message){
		$result = false;
		if (!empty($this->_stmt)){
			try{
				$result = $this->_stmt->fetch(PDO::FETCH_ASSOC);
			}catch (PDOException $e){
				if ($result === false){
					$this->_db_logger($e, $_e_message, $this->_sql, $this->_d, $this->_f);
				}
			}
		}
		return $result;
	}

	private function _pdo_fetch_all($_e_message){
		$result = false;
		if (!empty($this->_stmt)){
			try{
				$result = $this->_stmt->fetchAll(PDO::FETCH_ASSOC);
			}catch (PDOException $e){
				if ($result === false){
					$this->_db_logger($e, $_e_message, $this->_sql, $this->_d, $this->_f);
				}
			}
		}
		return $result;
	}

	private function _pdo_execute($_e_message){
		$result = false;
		if (!empty($this->_stmt)){
			try{
				$result = $this->_stmt->execute();
			}catch (PDOException $e){
				if ($result === false){
					$this->_db_logger($e, $_e_message, $this->_sql, $this->_d, $this->_f);
				}
			}
		}
		return $result;
	}

	private function _pdo_bind_values($_e_message){
		$i = 0;
		$result = false;
		try{
			if (!empty($this->_d) && !empty($this->_stmt)){
				foreach ($this->_d as $_key => $_d){
					$_data_type = PDO::PARAM_STR;
					$result = false;
					$_d = etn($_d);
					if ($this->_f[$i] === 'i'){$_data_type = PDO::PARAM_INT; if (empty($_d) || is_null($_d)){$_d = 0;}}
					if ($this->_f[$i] === 's'){$_data_type = PDO::PARAM_STR;}
					if (is_null($_d)){$_data_type = PDO::PARAM_NULL;}

					$_place_holder = ':'.$_key;
					$result = $this->_stmt->bindValue($_place_holder, $_d, $_data_type);
					$i++;
				}
			}
		}catch (PDOException $e){
			if ($result === false){
				$this->_db_logger($e, $_e_message, $this->_sql, $this->_d, $this->_f);
			}
		}
		return $result;
	}

	private function _pdo_prepare($_e_message){
		$this->_stmt = false;
		if (!empty($this->_sql)){
			try{

				$this->_stmt = $this->_pdo->prepare($this->_sql);
			}catch (PDOException $e){
				if ($this->_stmt === false){
					$this->_db_logger($e, $_e_message, $this->_sql, $this->_d, $this->_f);
				}
			}
			return $this->_stmt;
		}
		return false;
	}

	private function _set_data_globals($_sql = '', $_d = [], $_f = []) {
		$this->_sql = $_sql;
		$this->_d = $_d;
		$this->_f = $_f;
	}

	private function create_insert_sql_from_data($_t, $_d) {
		$_sql = 'insert into '.$_t.' set ';
		foreach ($_d as $_key => $_da){
			$_sql .= $_key . ' = :'.$_key.', ';
		}
		$_sql = substr($_sql, 0, -2);
		return $_sql;
	}

	private function create_update_sql_from_data($_t, $_d, $_w) {
		$_sql = 'update '.$_t.' set ';
		foreach ($_d as $_key => $_da){
			$_sql .= $_key . ' = :'.$_key.', ';
		}
		$_sql = substr($_sql, 0, -2);
		$_sql .= ' where ';
		foreach ($_w as $_key => $_wa){
			$_sql .= $_key . ' = :'.$_key.', ';
		}
		$_sql = substr($_sql, 0, -2);
		return $_sql;
	}

	private function create_delete_sql_from_data($_t, $_d){
		$_sql = 'delete from '.$_t.' where ';
		foreach ($_d as $_key => $_da){
			$_sql .= $_key . ' = :'.$_key.' and ';
		}
		$_sql = substr($_sql, 0, -5);
		//_lg($_sql);
		return $_sql;
	}

	public function _get_error(){
		return $this->_pdo->errorInfo();
	}

	public function _is_in_transaction(){
		if ($this->_pdo->inTransaction()){
			return true;
		}
		return false;
	}

	public function _begin_transaction(){
		$this->_pdo->beginTransaction();
	}

	public function _commit_transaction(){
		$this->_pdo->commit();
	}

	public function _rollback_transaction(){
		$this->_pdo->rollBack();
	}

	// LOGGING METHODS

	private function _db_logger($e, $_e_message, $_sql = '', $_d = [], $_f = []){
		echo 'ERROR MESSAGE: '.$_e_message.'<br>FILE: '.$e->getFile().'<br>LINE: '.$e->getLine();

		$array = array(
			'DATE' => date('Y-m-d H:i:s'),
			'ERROR MESSAGE' => $e->getMessage(),
			'IN FILE' => $e->getFile(),
			'AT LINE' => $e->getLine(),
			'_sql' => $_sql,
			'_d' => $_d,
			'_f' => $_f,
			'TRACE' => $e->getTraceAsString()
		);

		$error = $this->_build_error_text($e, $array);
		$this->_err_log($error, $_e_message);
	}


	private function _build_error_text($e, $array){
		$error = $this->_clt($array, 'ERROR BLOCK');
		return $error;
	}


	private function _err_log($input, $label = ''){
		$this->_set_log_name('_php_code_errors.log');
		$this->_lg($input, $label);
	}

	public function _lg($input, $label = ''){
		$this->_cl($input, $label);
	}

	public function _cl($input, $label = ''){
		$_file = __s_app_folder__.'/'.$this->_log_name_prefix.$this->_log_name;
		if (!file_exists($_file)){
			$f = fopen($_file, 'w');
			fclose($f);
		}
		$fh = fopen($_file, 'a');
		fwrite($fh, date("d:m:Y H:i:s").PHP_EOL);
		if (is_array($input)){
			$result = print_r($input, true);
		}else{
			$result = $this->_var_dump_ret($input);
		}
		fwrite($fh, $label);
		fwrite($fh, PHP_EOL);
		fwrite($fh, $result);
		fwrite($fh, PHP_EOL);
		fclose($fh);
	}

	private function _clt($input, $label = ''){
		$error = date("d:m:Y H:i:s").PHP_EOL;
		if (is_array($input)){
			$result = print_r($input, true);
		}else{
			$result = $this->_var_dump_ret($input);
		}
		$error .= $label;
		$error .= PHP_EOL;
		$error .= $result;
		$error .= PHP_EOL;
		return $error;
	}
	private function _var_dump_ret($mixed = null) {
		ob_start();
		var_dump($mixed);
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	public function _get_pdo(){return $this->_pdo;}
	public function _get_stmt(){return $this->_stmt;}
	public function _get_log_name() {return $this->_log_name;}
	public function _get_log_name_prefix() {return $this->_log_name_prefix;}

	public function _set_log_name($_t) {$this->_log_name = $_t;}
	public function _set_log_name_prefix($_t) {$this->_log_name_prefix = $_t;}
}

