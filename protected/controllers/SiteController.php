<?php
class SiteController extends Controller
{
    /**
     * Declares class-based actions.
     */
   public function actions()
{
    // Remove the CErrorAction reference completely
    return array();
}

/**
 * Error action to handle errors
 */
public function actionError()
{
    if ($error = Yii::app()->errorHandler->error) {
        if (Yii::app()->request->isAjaxRequest) {
            echo $error['message'];
        } else {
            $this->render('error', $error);
        }
    }
}
    /**
     * This is the default 'index' action.
     * Redirects to auth/login if not authenticated, dashboard if logged in
     */
    public function actionIndex()
    {
        if (Yii::app()->user->isGuest) {
            $this->redirect(array('auth/login'));  // Redirect to AuthController
        } else {
            $this->redirect(array('site/dashboard'));
        }
    }

    /**
     * Simple Dashboard page (only for logged-in users)
     */
    public function actionDashboard()
    {
        // Check if user is logged in
        if (Yii::app()->user->isGuest) {
            $this->redirect(array('auth/login'));  // Redirect to AuthController
        }
        
        // Get current user
        $user = User::model()->findByPk(Yii::app()->user->id);
        
        // Simple dashboard data
        $dashboardData = array(
            'welcomeMessage' => 'Welcome to your Dashboard',
            'user' => $user,
            'loginTime' => date('Y-m-d H:i:s'),
            'totalUsers' => User::model()->count(),
        );
        
        $this->render('dashboard', $dashboardData);
    }

    /**
     * Logs out the current user and redirect to auth/login
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(array('auth/login'));  // Redirect to AuthController
    }
    
    /**
     * Simple Profile page
     */
    public function actionProfile()
    {
        // Check if user is logged in
        if (Yii::app()->user->isGuest) {
            $this->redirect(array('auth/login'));  // Redirect to AuthController
        }
        
        $user = User::model()->findByPk(Yii::app()->user->id);
        
        if (!$user) {
            throw new CHttpException(404, 'User not found.');
        }
        
        $this->render('profile', array(
            'user' => $user,
        ));
    }
}