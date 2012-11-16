 <?php
	function exec_speed_check(){
    $speed_check_mtime = explode(" ", microtime());
    $speed_check_msec = (double) $speed_check_mtime[0];
    $speed_check_sec = (double) $speed_check_mtime[1];
    return $speed_check_sec + $speed_check_msec;
	} 
	$speed_check_start = exec_speed_check();
	
    session_start();
    $username = "root";
    $password = "";
    $host = "localhost";
    $dbname = "pastebin";

    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

    try
    {
        $db = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options);
    }
    catch(PDOException $ex)
    {
		
        die("Failed to connect to the database: " . $ex->getMessage());
    }
    
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
    {
        function undo_magic_quotes_gpc(&$array)
        {
            foreach($array as &$value)
            {
                if(is_array($value))
                {
                    undo_magic_quotes_gpc($value);
                }
                else
                {
                    $value = stripslashes($value);
                }
            }
        }
    
        undo_magic_quotes_gpc($_POST);
        undo_magic_quotes_gpc($_GET);
        undo_magic_quotes_gpc($_COOKIE);
    }
	function commonJs() {
	return '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>'.
			'<script type="text/javascript">
			function validateForm(formname, formfield, message)
			{
			var x=document.forms[formname][formfield].value;
			if (x==null || x=="")
			  {
			  showNotice(message);
			  return false;
			  } else {
			  return true;
			  }
			}
			
			function showNotice(notice) {
				var o = document.getElementById(\'notice\');
				o.style.visibility = \'visible\';
				o.innerHTML= notice;
			}
			function hideNotice() {
				var o = document.getElementById(\'notice\');
				o.style.visibility = \'hidden\';
			}
			$(document).ready(function(){
	$(\'#nav li\').hover(function () {
		$(this).find(\'.dropDown\').slideToggle(200);
		$(this).toggleClass(\'active\');
    });
});
		</script>
		';
	}
    function notice($reason) {
	return 	"<script type='text/javascript'>
				showNotice('$reason');
			</script>";
	}
	
	function time_diff($time){
	$timenow = time();
	$s = $timenow -$time;
    $m=0;$hr=0;$d=0;$td="now";
		if ($s == 0) {
			$td = "now";
		}
		if ($s > 0) {
			$td = "$s second"; if ($s>1) $td .= "s";
			$td .= " ago";
		}
		if($s>59) {
			$m = (int)($s/60);
			$s = $s-($m*60);
			$td = "$m minute"; if ($m>1) $td .= "s";
			$td .= " ago";
		}
		if($m>59){
			$hr = (int)($m/60);
			$m = $m-($hr*60);
			$td = "$hr hour"; if($hr>1) $td .= "s";
			$td .= " ago";
		}
		if($hr>23){
			$d = (int)($hr/24);
			$hr = $hr-($d*24);
			$td = "$d day"; if($d>1) $td .= "s";
			$td .= " ago";
		}
    return $td;
}