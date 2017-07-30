<?php
/**
 * Sql statement classes and methods.
 */

class sql_db
{

	var $db_connect_id;
	var $query_result;
	var $row = array();
	var $rowset = array();
	var $num_queries = 0;
	var $in_transaction = 0;

	//
	// Constructor
	// sql_connect_data (class_config.php)
	

	function sql_db($sqlserver, $sqluser, $sqlpassword, $database, $persistency = false)
	{
		$this->persistency = $persistency;
		$this->user = $sqluser;
		$this->password = $sqlpassword;
		$this->server = $sqlserver;
		$this->dbname = $database;

		// 5+1 mysql_connect() 函數打開非持久的 MySQL 連接。
	// 5+1 使用 mysql_pconnect() 函式會開啟持續性的資料庫連線 (persistent connection), 執行這個函式時, 函式會先從現有的持續性連線中, 找出有無相同主機、使用者帳號、密碼的資料庫連線, 有的話, 就直接傳回連線的資源, 而不會重新產生新的連接。在同時使用相同資料庫連線的情況下, 使用 mysql_pconnect() 可以省下每次重新開啟資料庫連接的時間, 程式碼的執行會快一些些。但是, 假設您的網站是在一部共用的伺服器上執行, 使用 mysql_pconnect() 開啟持續性資料庫連線時, 將可能會獨佔住那部伺服器的資源, 造成其它網站無法同時進行連線的後果, 在這樣子的環境下, 通常會避免使用 mysql_pconnect() 函式。

		$this->db_connect_id = ($this->persistency) ? mysql_pconnect($this->server, $this->user, $this->password) : mysql_connect($this->server, $this->user, $this->password);

		if( $this->db_connect_id )
		{
			if( $database != "" )
			{
				$this->dbname = $database;
				// 5+1 mysql_select_db() 函數設置活動的 MySQL 數據庫。如果成功，則該函數返回 true。如果失敗，則返回 false。
				$dbselect = mysql_select_db($this->dbname);

				if( !$dbselect )
				{
					// 5+1 mysql_close() 函數關閉非持久的MySQL 連接
					mysql_close($this->db_connect_id);
					$this->db_connect_id = $dbselect;
				}
			}

			return $this->db_connect_id;
		}
		else
		{
			return false;
		}
	}

	//
	// Other base methods
	//
	function sql_close()
	{
		if( $this->db_connect_id )
		{
			//
			// Commit any remaining transactions
			//
			if( $this->in_transaction )
			{
				mysql_query("COMMIT", $this->db_connect_id);
			}

			return mysql_close($this->db_connect_id);
		}
		else
		{
			return false;
		}
	}

	//
	// Base query method
	//
	function sql_query($query = "", $transaction = FALSE)
	{
		//
		// Remove any pre-existing queries
		//
		unset($this->query_result);

		if( $query != "" )
		{
			$this->num_queries++;
			if( $transaction == BEGIN_TRANSACTION && !$this->in_transaction )
			{
				$result = mysql_query("BEGIN", $this->db_connect_id);
				if(!$result)
				{
					return false;
				}
				$this->in_transaction = TRUE;
			}

			$this->query_result = mysql_query($query, $this->db_connect_id);
		}
		else
		{
			if( $transaction == END_TRANSACTION && $this->in_transaction )
			{
				$result = mysql_query("COMMIT", $this->db_connect_id);
			}
		}

		if( $this->query_result )
		{
			unset($this->row[$this->query_result]);
			unset($this->rowset[$this->query_result]);

			if( $transaction == END_TRANSACTION && $this->in_transaction )
			{
				$this->in_transaction = FALSE;

				if ( !mysql_query("COMMIT", $this->db_connect_id) )
				{
					mysql_query("ROLLBACK", $this->db_connect_id);
					return false;
				}
			}
			
			return $this->query_result;
		}
		else
		{
			if( $this->in_transaction )
			{
				mysql_query("ROLLBACK", $this->db_connect_id);
				$this->in_transaction = FALSE;
			}
			return false;
		}
	}

	//
	// Other query methods
	//
	function sql_numrows($query_id = 0)
	{
		if( !$query_id )
		{
			$query_id = $this->query_result;
		}
		
		return ( $query_id ) ? mysql_num_rows($query_id) : false;
	}

	function sql_affectedrows()
	{
		return ( $this->db_connect_id ) ? mysql_affected_rows($this->db_connect_id) : false;
	}

	function sql_numfields($query_id = 0)
	{
		if( !$query_id )
		{
			$query_id = $this->query_result;
		}

		return ( $query_id ) ? mysql_num_fields($query_id) : false;
	}

	function sql_fieldname($offset, $query_id = 0)
	{
		if( !$query_id )
		{
			$query_id = $this->query_result;
		}
		return ( $query_id ) ? mysql_field_name($query_id, $offset) : false;
	}

	function sql_fieldtype($offset, $query_id = 0)
	{
		if( !$query_id )
		{
			$query_id = $this->query_result;
		}

		return ( $query_id ) ? mysql_field_type($query_id, $offset) : false;
	}

	function sql_fetchrow($query_id = 0)
	{
		if( !$query_id )
		{
			$query_id = $this->query_result;
		}

		if( $query_id )
		{
			$this->row[$query_id] = mysql_fetch_array($query_id, MYSQL_ASSOC);
			return $this->row[$query_id];
		}
		else
		{
			return false;
		}
	}

	function sql_fetchrowset($query_id = 0)
	{
		if( !$query_id )
		{
			$query_id = $this->query_result;
		}

		if( $query_id )
		{
			unset($this->rowset[$query_id]);
			unset($this->row[$query_id]);

			while($this->rowset[$query_id] = mysql_fetch_array($query_id, MYSQL_ASSOC))
			{
				$result[] = $this->rowset[$query_id];
			}

			return $result;
		}
		else
		{
			return false;
		}
	}

	function sql_fetchfield($field, $rownum = -1, $query_id = 0)
	{
		if( !$query_id )
		{
			$query_id = $this->query_result;
		}

		if( $query_id )
		{
			if( $rownum > -1 )
			{
				$result = mysql_result($query_id, $rownum, $field);
			}
			else
			{
				if( empty($this->row[$query_id]) && empty($this->rowset[$query_id]) )
				{
					if( $this->sql_fetchrow() )
					{
						$result = $this->row[$query_id][$field];
					}
				}
				else
				{
					if( $this->rowset[$query_id] )
					{
						$result = $this->rowset[$query_id][$field];
					}
					else if( $this->row[$query_id] )
					{
						$result = $this->row[$query_id][$field];
					}
				}
			}

			return $result;
		}
		else
		{
			return false;
		}
	}

	function sql_rowseek($rownum, $query_id = 0)
	{
		if( !$query_id )
		{
			$query_id = $this->query_result;
		}

		return ( $query_id ) ? mysql_data_seek($query_id, $rownum) : false;
	}

	function sql_nextid()
	{
		return ( $this->db_connect_id ) ? mysql_insert_id($this->db_connect_id) : false;
	}

	function sql_freeresult($query_id = 0)
	{
		if( !$query_id )
		{
			$query_id = $this->query_result;
		}

		if ( $query_id )
		{
			unset($this->row[$query_id]);
			unset($this->rowset[$query_id]);

			mysql_free_result($query_id);

			return true;
		}
		else
		{
			return false;
		}
	}

	function sql_error()
	{
		$result['message'] = mysql_error($this->db_connect_id);
		$result['code'] = mysql_errno($this->db_connect_id);

		return $result;
	}

} // class sql_db


?>
