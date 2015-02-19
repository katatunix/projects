<?

class PreparedStatement
{
	var $list;
	var $pieces;
	var $count;
	
	function __construct($sql)
	{
		$this->pieces = explode('?', $sql);
		$this->count = count($this->pieces);
		$this->list = array();
	}
	
	function setValue($index, $value)
	{
		if ($index < 1 || $index >= $this->count)
		{
			return FALSE;
		}
		
		$this->list[$index] = $value;
		
		return TRUE;
	}
	
	function getSql()
	{
		$s = '';
		
		for ($i = 0; $i < $this->count - 1; $i++)
		{
			$s .= $this->pieces[$i];
			$j = $i + 1;
			
			//if ( isset($this->list[$j]) )
			{
				$value = $this->list[$j];
				if ( is_null($value) )
				{
					$s .= 'NULL';
				}
				else if ( is_array($value) )
				{
					$len = count( $value );
					if ($len > 0)
					{
						for ($k = 0; $k < $len - 1; $k++)
						{
							$s .= "'" . DataSource::instance()->escape($value[$k]) . "', ";
						}
						$s .= "'" . DataSource::instance()->escape($value[$len - 1]) . "'";
					}
				}
				else
				{
					$s .= "'" . DataSource::instance()->escape($value) . "'";
				}
			}
		}
		
		$s .= $this->pieces[$this->count - 1];
		
		return $s;
	}
}

?>
