<?php
	//Ler Registros
	function DBRead($table, $params = null, $fields = '*'){
		
		$params = ($params)? " {$params}": null;
		$fields = ($fields)? "{$fields}" : "*";

		$query = "SELECT {$fields} FROM {$table}{$params}";
		$result = DBExecute($query);

		if (!@mysqli_num_rows($result)) {
			return false;
		}else{
			while ( $res = @mysqli_fetch_assoc($result)) {
				$data[] = $res;
			}

			return $data;
		}
	}

	//Grava registros
	function DBCreate($table, array $data){
		
		$data = DBEscape($data);

		$fields = implode(', ',array_keys($data));
		$values = "'".implode("', '", $data)."'";

		$query = "INSERT INTO {$table} ({$fields}) VALUES ({$values})";
		
		return DBExecute($query);
	}

	//Exclui registros
	function DBDelete($table, $params){

		$query = "DELETE FROM {$table} WHERE {$params}";
		return DBExecute($query);
	}

	//Atualiza registros
	function DBUpdate($table, $params, $fields, $value){

		$query = "UPDATE {$table} SET {$fields}={$value} {$params}";
		$result = DBExecute($query);
		if (!@mysqli_num_rows($result)) {
			return false;
		}else{
			while ( $res = @mysqli_fetch_assoc($result)) {
				$data[] = $res;
			}

			return $data;
		}

	}

	//Executa Querys
	function DBExecute($que){
		$link = DBConnect();
		$result = @mysqli_query($link, $que) or die (@mysqli_error($link));
		DBClose($link);
		return $result;
	}
?>