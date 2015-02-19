<?

function remove_slashes($str)
{
	if ( !is_string($str) ) return $str;
	if ( get_magic_quotes_gpc() )
	{
		return stripslashes($str);
	}
	return $str;
}

function isPositiveIntInStringFormat($value)
{
	// todo
	return is_string($value) && is_numeric($value) && (int)$value > 0;
}

function isContentString($value)
{
	return is_string($value) && strlen( trim($value) ) > 0;
}

function checkGetParameter($param)
{
	if ( !isset($_GET[$param]) ) return NULL;
	if ( !is_string($_GET[$param]) ) return NULL;
	if ( strlen($_GET[$param]) <= 0 ) return NULL;
	
	return remove_slashes( $_GET[$param] );
}

function checkPostParameter($param)
{
	if ( !isset($_POST[$param]) ) return NULL;
	if ( !is_string($_POST[$param]) ) return NULL;
	if ( strlen($_POST[$param]) <= 0 ) return NULL;
	
	return remove_slashes( $_POST[$param] );
}

function checkValidEmail($email)
{
	return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
}

function getCurrentDateString($separate)
{
	$n = getdate();
	return $n['year'] . $separate . $n['mon'] . $separate . $n['mday'];
}

function getCurrentDatetimeString($separateDate, $separateTime)
{
	$n = getdate();
	return		$n['year'] . $separateDate . $n['mon'] . $separateDate . $n['mday'] . ' ' .
				$n['hours'] . $separateTime . $n['minutes'] . $separateTime . $n['seconds'];
}

function isGuest()
{
	return ! MySession::currentMember();
}

function isAdmin()
{
	$cm = MySession::currentMember();
	if (!$cm) return FALSE;
	return $cm->groupId() == 2;
}

function isNormal()
{
	$cm = MySession::currentMember();
	if (!$cm) return FALSE;
	return $cm->groupId() == 1;
}

function isMod()
{
	$cm = MySession::currentMember();
	if (!$cm) return FALSE;
	return $cm->groupId() == 3;
}

?>
