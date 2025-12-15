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
	<script src="https://cdn.tailwindcss.com"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

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
			padding: 8px 0; /* Reduced from 15px */
			position: sticky;
			top: 0;
			z-index: 1000;
		}
		
		#logo {
			width: 100%;
			max-width: 1200px;
			margin: 0 auto;
			padding: 0 15px; /* Reduced from 20px */
			font-size: 20px; /* Reduced from 24px */
			font-weight: bold;
			line-height: 1.2;
		}
		
		#mainmenu {
			width: 100%;
			background: #6346ceff;
			border-bottom: 1px solid #ddd;
			padding: 0; /* No padding */
		}
		
		#mainmenu ul {
			width: 100%;
			max-width: 1200px;
			margin: 0 auto;
			padding: 0 15px; /* Reduced from 20px */
			list-style: none;
			display: flex;
			height: 45px; /* Fixed height */
		}
		
		#mainmenu li {
			margin: 0;
		}
		
		#mainmenu a {
			display: flex;
			align-items: center;
			padding: 0 15px; /* Reduced from 15px 20px */
			color: #333;
			text-decoration: none;
			height: 100%;
			font-size: 14px;
		}
		
		#mainmenu a:hover {
			color: #eee;
		}
		
		.content-container {
			width: 100%;
			max-width: 1200px;
			margin: 0 auto;
			padding: 15px; /* Reduced from 20px */
		}
		
		/* Remove margin for specific pages */
		.blog-content .content-container {
			padding-top: 0;
		}
		
		/* For blog pages specifically */
		body.blog-page .content-container {
			padding-top: 5px;
		}
		
		#footer {
			width: 100%;
			background: #333;
			color: white;
			text-align: center;
			padding: 15px 0; /* Reduced from 20px */
			margin-top: 20px; /* Reduced from 30px */
			font-size: 14px;
		}
		
		.clear {
			clear: both;
		}
		
		/* Toast container */
		#toast-container {
			position: fixed;
			top: 60px; /* Position below header */
			right: 15px;
			z-index: 9999;
		}
		
		/* Breadcrumbs spacing */
		.breadcrumbs {
			margin: 8px 0 !important; /* Reduced spacing */
			padding: 0 !important;
		}
		
		/* Compact layout */
		.menu-list {
			margin: 0;
			padding: 0;
		}
		
		/* Remove unnecessary spacing */
		#mainmenu .menu-list {
			height: 45px;
		}
	</style>

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body class="<?php echo isset($this->bodyClass) ? $this->bodyClass : ''; ?>">
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
					array('label'=>'Blogs', 'url'=>array('/blog/index')), /* Added Blogs link */
					array('label'=>'Dashboard', 'url'=>array('/site/dashboard'), 'visible'=>!Yii::app()->user->isGuest),
					array('label'=>'Profile', 'url'=>array('/site/profile'), 'visible'=>!Yii::app()->user->isGuest),
					array('label'=>'Login', 'url'=>array('auth/login'), 'visible'=>Yii::app()->user->isGuest),
					array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
				),
				'htmlOptions' => array('class' => 'menu-list'),
			)); ?>
		</div><!-- mainmenu -->
		
		<?php if(isset($this->breadcrumbs) && !empty($this->breadcrumbs)):?>
			<div class="content-container" style="padding-top: 8px;">
				<?php $this->widget('zii.widgets.CBreadcrumbs', array(
					'links'=>$this->breadcrumbs,
					'htmlOptions' => array('class' => 'breadcrumbs')
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