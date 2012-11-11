
<div id="header">

	<div class="wrapper">
		<div id="navContainer">		
<?php
if (isset($_SESSION['user'])) {
?>	
			<ul class="loggedin" id="logo">
			<li class="logo"><a class="loggedin" href="index.php"><img src="img/cactuspaste.png" alt="logo" /></a><li>
			</ul>
			<ul class="clear" id="loggedin">
			<li class="first"><a href="user.php?u=<?php echo $_SESSION['user']['username']; ?>">My Cactusbin</a></li>
			<li><a href="random.php">Random Paste</a></li>
			<li><a href="index.php">New Paste</a></li>
			<li><a href='pastelist.php'>All Pastes</a></li>
			<li><a href='memberlist.php'>Memberlist</a></li>
			<li><a href='edit_account.php'>Edit Account</a></li>
			<li class="last"><a href='logout.php'>Logout</a></li>
	<?php
} else {
?>	
			<ul class="logo" id="logo">
			<li class="logo"><a class="loggedout" href="index.php"><img src="img/cactuspaste.png" alt="logo" /></a><li>
			</ul>
			<ul class="clear" id="nav">
			<li class="first"><a href="random.php">Random paste</a></li>			
			<li><a href="index.php">New paste</a></li>
			<li><a href='login.php'>Log in</a></li>
			<li><a href='pastelist.php'>All Pastes</a></li>
			<li class="last"><a href='register.php'>Register</a></li>
	<?php
}

?>
			</ul>
		 </div>
	</div>
</div>