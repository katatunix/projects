<?

require_once('includes/Cart.class.php');

function valid_email($email) {
	return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
}

function date_cmp($d1, $m1, $y1, $d2, $m2, $y2) {
	if ($y1 < $y2) return -1;
	if ($y1 > $y2) return 1;
	
	if ($m1 < $m2) return -1;
	if ($m1 > $m2) return 1;
	
	if ($d1 < $d2) return -1;
	if ($d1 > $d2) return -1;
	
	return 0;
}

function valid_date_vn($date_str) {
	$p = explode('/', $date_str);
	
	if ( count($p) != 3 ) {
		return FALSE;
	}
	
	$dd = (int)$p[0];
	if ($dd <= 0 || $dd > 31) return FALSE;
	
	$mm = (int)$p[1];
	if ($mm <= 0 || $mm > 12) return FALSE;
	
	$yy = (int)$p[2];
	if ($yy < 1900 || $yy > 2200) return FALSE;
	
	return checkdate($mm, $dd, $yy);
}

function convert_date_to_us($date_vn) {
	if ( !isset($date_vn) || strlen($date_vn) == 0 ) {
		return '';
	}
	$p = explode('/', $date_vn);
	return $p[2] . '-' . $p[1] . '-' . $p[0];
}

function convert_date_to_vn($date_us) {
	if ( !isset($date_us) || strlen($date_us) == 0 ) {
		return '';
	}
	$p = explode('-', $date_us);
	return $p[2] . '/' . $p[1] . '/' . $p[0];
}

function remove_slashes($str) {
	if (get_magic_quotes_gpc()) {
		return stripslashes($str);
	}
	return $str;
}

function make_navigation($total_page, $cur_page, $url) {
	if ($total_page < 1) return '';
	if ($cur_page < 1 || $cur_page > $total_page) return '';
	
	$s = '<div class="navigation">';
	
	if ($cur_page == 1)	$s .= '<span>Previous</span>';
	else				$s .= '<a href="' . $url . ($cur_page - 1) . '">Previous</a>';
	
	if ($cur_page == 1)	$s .= '<span>1</span>';
	else				$s .= '<a href="' . $url . '1">1</a>';
	
	$j = 2;
	if ($cur_page >= 8) {
		$s .= '<span>...</span>';
		$j = $cur_page - 4;
	}
	
	for ($i = $j; $i < $cur_page; $i++) {
		$s .= '<a href="' . $url . $i . '">' . $i . '</a>';
	}
	
	if ($cur_page > 1) {
		$s .= '<span>' . $cur_page . '</span>';
	}
	
	$j = $total_page - 1;
	if ($cur_page <= $total_page - 7) {
		$j = $cur_page + 4;
	}
	
	for ($i = $cur_page + 1; $i <= $j; $i++) {
		$s .= '<a href="' . $url . $i . '">' . $i . '</a>';
	}
	
	if ($cur_page <= $total_page - 7) {
		$s .= '<span>...</span>';
	}
	
	if ($cur_page < $total_page) {
		$s .= '<a href="' . $url . $total_page . '">' . $total_page . '</a>';
	}
	
	if ($cur_page == $total_page)	$s .= "<span>Next</span>";
	else							$s .= '<a href="' . $url . ($cur_page + 1) . '">Next</a>';
	
	$s .= '</div>';
	
	return $s;
}

function get_pic_at($pics, $index) {
	if (trim($pics) == '') return '';
	$arr = explode('/', $pics);
	return $arr[$index];
}

function explode_pics($pics) {
	if (trim($pics) == '') return '';
	return explode('/', $pics);
}

function delete_upload_image($file_name) {
	unlink( __SITE_PATH . '/' . __UPLOAD_DIR . $file_name );
}

function is_existed_image($file_name) {
	return file_exists( __SITE_PATH . '/' . __UPLOAD_DIR . $file_name );
}

function clean_upload_images($file_names) {
	$count = 0;
	if ( $handle = opendir(__SITE_PATH . '/' . __UPLOAD_DIR) ) {
		
		while ( FALSE !== ( $file = readdir($handle) ) ) {
			if (!is_dir($file)) {
				if ( !in_array($file, $file_names) ) {
					$count++;
					delete_upload_image($file);
				}
			}
		}
		
		closedir($handle);
	}
	return $count;
}

function format_money($n) {
	$str = (string)$n;
	$i = strlen($str) - 1;
	$res = '';
	while (true) {
		if ($i > 2) {
			$res = substr($str, $i - 2, 3) . $res;
			$res = ',' . $res;
		} else {
			$res = substr($str, 0, $i + 1) . $res;
			break;
		}
		$i -= 3;
	}
	return $res;
}

function getCart() {
	if ( !isset($_SESSION['cart']) || !$_SESSION['cart'] ) {
		$_SESSION['cart'] = new Cart();
	}
	return $_SESSION['cart'];
}

function get_cur_datetime() {
	$n = getdate();
	return $n['year'] . '-' . $n['mon'] . '-' . $n['mday'] . ' ' . $n['hours'] . ':' . $n['minutes'] . ':' . $n['seconds'];
}

function get_cur_date($s) {
	$n = getdate();
	if ( strlen($n['mday']) == 1 ) {
		$n['mday'] = '0' . $n['mday'];
	}
	if ( strlen($n['mon']) == 1 ) {
		$n['mon'] = '0' . $n['mon'];
	}
	return $n['mday'] . $s . $n['mon'] . $s . $n['year'];
}

function convert_datetime_to_vn($date_time_us) {
	$p = explode(' ', $date_time_us);
	if (count($p) != 2) {
		return NULL;
	}
	return convert_date_to_vn($p[0]) . ' ' . $p[1];
}

function check_seo_char($c) {
	if ( ($c >= 'a' && $c <= 'z') || ($c >= 'A' && $c <= 'Z') || ($c >= '0' && $c <= '9')
				|| ($c == '-') || ($c == '_') ) return TRUE;
	return FALSE;
}

function check_seo_url($seo_url) {
	$len = strlen( $seo_url );
	for ($i = 0; $i < $len; $i++) {
		if ( check_seo_char( $seo_url[$i] ) == FALSE ) {
			return FALSE;
		}
	}
	return TRUE;
}

?>
