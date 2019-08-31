<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<title>MineMap</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP&display=swap&subset=japanese" rel="stylesheet">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<?= Asset::css('materialize.css'); ?>
</head>
<body class="grey lighten-5">
	<div id="app">
		<header-bar></header-bar>
		<router-view></router-view>
	</div>
	<?= Security::js_fetch_token(); ?>
	<?= Asset::js('materialize.js'); ?>
	<?= Asset::js('app.js'); ?>
</body>
</html>
