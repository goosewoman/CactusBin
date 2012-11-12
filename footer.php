
<div id="footer" class="main">
<?php
$speed_check_finish = exec_speed_check();
$speed_check_runtime = $speed_check_finish - $speed_check_start;

if ($speed_check_runtime < 0) {
    $speed_check_runtime += 1;
}
?>
<p>Page generated in <?php echo $speed_check_runtime; ?> seconds.<br />
<a href="copyright.php">&copy; MrRadthorne (Luuk Jacobs)</a>.</p>
</div>
