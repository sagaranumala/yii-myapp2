<?php
/**
 * CErrorAction class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link https://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

/**
 * CErrorAction handles the error action.
 *
 * CErrorAction is a base class for actions that handle errors.
 * It displays the error view based on the specified view name.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.web.actions
 * @since 1.0
 */
class CErrorAction extends CAction
{
	/**
	 * @var string the view name to be rendered. Defaults to 'error'.
	 */
	public $view='error';
	/**
	 * @var integer the HTTP error code. Defaults to 200.
	 */
	public $errorCode=200;

	/**
	 * Runs the action.
	 */
	public function run()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->controller->render($this->view, $error);
		}
	}
}