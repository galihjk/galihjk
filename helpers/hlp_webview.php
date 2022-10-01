<?php 
//html helpers...
//=============== page layout
function htmlBegin($options = []){
	$title = $options['title'] ?? '';
	?>
	<!doctype html>
	<html lang="en">
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<link href="/assets/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
			<link rel="stylesheet" href="/assets/dx.light.css">
			
			<link rel="icon" href="https://realfavicongenerator.net/homepage_icons/platforms/google.png">
			<title>[GJK] <?= (empty($title) ? '' : " - $title")?> </title>

			<script type="text/javascript" src="/assets/jquery-3.6.0.min.js"></script>
			<!-- <script type="text/javascript" src="/assets/dx.all.js"></script> -->
			<script src="https://cdn3.devexpress.com/jslib/22.1.5/js/dx.all.js"></script>

			<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3643933755900950"
     		crossorigin="anonymous"></script>
			
		</head>
		<body class="dx-viewport">
	<?php
	return "";
}
function htmlEnd($options = []){
	?>
		<script src="/assets/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
		</body>
	</html>
	<?php
	return "";
}
//============================
function htmlTopBar(){
	$r = getAllRequest();
	if(empty($r->app))$r->app = "";
	if(empty($r->act))$r->act = "";
	?>
	<div style='padding: 11px;border-style: groove;margin-bottom: 10px;'>
		<span style='float: right;'>
			<?php
			if(isset($_SESSION["login"])){
					echo "<a href='?act=myprofile'>" . $_SESSION["login"]["first_name"] . " " . $_SESSION["login"]["last_name"] . "</a>";
					echo " - <a href='/?act=logout'>logout</a> ";
			}
			else{
				echo " <a href='https://t.me/galihjkbot?start=cmd_weblogin'>Login</a> ";
			}
			?>
		</span>
		<form action='/' method='post'>
			<select name='goto' onchange='this.form.submit()'>
				<?php 
				if(!empty($_SESSION['roles'])){
					foreach(getMenu($_SESSION['roles']) as $key=>$menu){
						if(is_array($menu)){
							echo  "<optgroup label='$key'>";
							foreach($menu as $url=>$submenu){
								$selected = ($url == getMenuUrl($r->app,$r->act) ? 'selected' : '');
								echo  "<option value='$url' $selected >$submenu</option>";
							}
							echo  "</optgroup>";
						}
						else{
							$selected = ($key == getMenuUrl($r->app,$r->act) ? 'selected' : '');
							echo  "<option value='$key' $selected >$menu</option>";
						}
					}
				}
				?>
			</select>
		</form>
	</div>
	<?php
}