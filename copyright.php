<?php require("common.php"); 
    header('Content-Type: text/html; charset=utf-8');
	echo "<!DOCTYPE html>
<!--[if IE 9 ]> <html class='ie9'> <![endif]-->
";?>
<html>
  <head>
  <link rel="stylesheet" href="http://<?php echo "{$_SERVER['SERVER_NAME']}"; ?>/css/main.css" />
  <?php echo commonJs(); ?>
  </head>
  <body>
  <a href="javascript:hideNotice();">
	<div id="notice">
	</div>
	</a>
	<?php include_once("header.php"); //HEADER ?>
	<div id="maindiv">
		<div id="content" class="main">
			<h1>The MIT License (MIT)</h1>
			<p>Copyright © 2012 &lt;Luuk Jacobs&gt;</p>

			<p>Permission is hereby granted, free of charge, to any person obtaining a copy
			of this software and associated documentation files (the “Software”), to deal
			in the Software without restriction, including without limitation the rights
			to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
			copies of the Software, and to permit persons to whom the Software is
			furnished to do so, subject to the following conditions:</p>

			<p>The above copyright notice and this permission notice shall be included in
			all copies or substantial portions of the Software.</p>

			<p>THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
			IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
			FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
			AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
			LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
			OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
			THE SOFTWARE.</p>
		</div>
		<?php include_once("sidebar.php"); //SIDEBAR ?>
		<?php include_once("footer.php"); //FOOTER ?>
	</div>	
  </body>
</html>  