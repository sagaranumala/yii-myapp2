<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="language" content="en">

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print">
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection">
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css">

	<style>
		/* Full width overrides */
		body, html {
			margin: 0;
			padding: 0;
			width: 100%;
			height: 100%;
		}
		
		#page {
			width: 100%;
			max-width: 100%;
			margin: 0;
			padding: 0;
		}
		
		.container {
			width: 100% !important;
			max-width: 100% !important;
			margin: 0 !important;
			padding: 0 !important;
		}
		
		#header {
			width: 100%;
			background: #333;
			color: white;
			padding: 15px 0;
		}
		
		#logo {
			width: 100%;
			max-width: 1200px;
			margin: 0 auto;
			padding: 0 20px;
			font-size: 24px;
			font-weight: bold;
		}
		
		#mainmenu {
			width: 100%;
			background: #f8f8f8;
			border-bottom: 1px solid #ddd;
		}
		
		#mainmenu ul {
			width: 100%;
			max-width: 1200px;
			margin: 0 auto;
			padding: 0 20px;
			list-style: none;
			display: flex;
		}
		
		#mainmenu li {
			margin: 0;
		}
		
		#mainmenu a {
			display: block;
			padding: 15px 20px;
			color: #333;
			text-decoration: none;
		}
		
		#mainmenu a:hover {
			background: #eee;
		}
		
		.content-container {
			width: 100%;
			max-width: 1200px;
			margin: 0 auto;
			padding: 20px;
		}
		
		#footer {
			width: 100%;
			background: #333;
			color: white;
			text-align: center;
			padding: 20px 0;
			margin-top: 30px;
		}
		
		.clear {
			clear: both;
		}
		
		/* Toast container */
		#toast-container {
			position: fixed;
			top: 20px;
			right: 20px;
			z-index: 9999;
		}
	</style>

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
	<!-- Toast container -->
	<div id="toast-container"></div>

	<div id="page">
		<div id="header">
			<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
		</div><!-- header -->

		<div id="mainmenu">
			<?php $this->widget('zii.widgets.CMenu',array(
				'items'=>array(
					array('label'=>'Home', 'url'=>array('/site/index')),
					array('label'=>'Dashboard', 'url'=>array('/site/dashboard'), 'visible'=>!Yii::app()->user->isGuest),
					array('label'=>'Profile', 'url'=>array('/site/profile'), 'visible'=>!Yii::app()->user->isGuest),
					array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
					array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
				),
				'htmlOptions' => array('class' => 'menu-list'),
			)); ?>
		</div><!-- mainmenu -->
		
		<?php if(isset($this->breadcrumbs)):?>
			<div class="content-container">
				<?php $this->widget('zii.widgets.CBreadcrumbs', array(
					'links'=>$this->breadcrumbs,
				)); ?><!-- breadcrumbs -->
			</div>
		<?php endif?>

		<div class="content-container">
			<?php echo $content; ?>
		</div>

		<div class="clear"></div>

		<div id="footer">
			Copyright &copy; <?php echo date('Y'); ?> by <?php echo CHtml::encode(Yii::app()->name); ?>.<br/>
			All Rights Reserved.<br/>
			<?php echo Yii::powered(); ?>
		</div><!-- footer -->
	</div><!-- page -->

</body>
</html>