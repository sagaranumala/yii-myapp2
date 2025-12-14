<?php
// protected/controllers/BlogController.php
class BlogController extends CController  // Changed from Controller to CController
{
    public function filters()
    {
        return array(
            'accessControl',
        );
    }
    
    public function accessRules()
    {
        return array(
            // Allow all users to view published blogs
            array('allow',
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            
            // Allow logged-in users to create blogs
            array('allow',
                'actions' => array('create'),
                'users' => array('@'),
            ),
            
            // Allow users to update their own blogs OR admin to update any
            array('allow',
                'actions' => array('update'),
                'users' => array('@'),
                'expression' => array($this, 'canEditBlog'),
            ),
            
            // Allow only admin to delete blogs
            array('allow',
                'actions' => array('delete'),
                'expression' => '!Yii::app()->user->isGuest && Yii::app()->user->getState("role") === "admin"', // Fixed
            ),
            
            // Allow logged-in users to view their own blogs
            array('allow',
                'actions' => array('myBlogs'),
                'users' => array('@'),
            ),
            
            // Deny everything else
            array('deny',
                'users' => array('*'),
            ),
        );
    }
    
    // Check if user can edit the blog
    public function canEditBlog()
    {
        $id = Yii::app()->request->getParam('id');
        if (!$id) return false;
        
        $blog = Blog::model()->findByPk($id);
        if (!$blog) return false;
        
        // If blog has canEdit method
        if (method_exists($blog, 'canEdit')) {
            return $blog->canEdit();
        }
        
        // Simple check: user is author or admin
        return ($blog->userId == Yii::app()->user->id) || 
               (Yii::app()->user->getState('role') === 'admin');
    }
    
    // Action: List all published blogs
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('Blog', array(
            'criteria' => array(
                'condition' => 'status = :status',
                'params' => array(':status' => 'published'), // Assuming status column
                'order' => 'createdAt DESC',
            ),
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));
        
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }
    
    // Action: View single blog
    public function actionView($id)
    {
        $blog = $this->loadModel($id);
        
        // Check if user can view
        if ($blog->status != 'published') {
            $userId = Yii::app()->user->id;
            $userRole = Yii::app()->user->getState('role');
            
            // Only author or admin can view non-published blogs
            if ($blog->userId != $userId && $userRole !== 'admin') {
                throw new CHttpException(403, 'You are not authorized to view this page.');
            }
        }
        
        $this->render('view', array(
            'model' => $blog,
        ));
    }
    
    // Action: Create new blog - SIMPLIFIED VERSION
    public function actionCreate()
    {
        // Check if user is logged in
        if (Yii::app()->user->isGuest) {
            $this->redirect(array('site/login'));
            return;
        }
        
        $model = new Blog;
        
        if (isset($_POST['Blog'])) {
            $model->attributes = $_POST['Blog'];
            $model->userId = Yii::app()->user->id; // Set current user as author
            $model->createdAt = new CDbExpression('NOW()');
            $model->status = 'draft'; // Default status
            
            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'Blog created successfully!');
                $this->redirect(array('view', 'id' => $model->id));
            }
        }
        
        $this->render('create', array(
            'model' => $model,
        ));
    }
    
    // Action: Update blog
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        
        // Check if user can edit
        if (!$this->canEditBlog()) {
            throw new CHttpException(403, 'You are not authorized to update this blog.');
        }
        
        if (isset($_POST['Blog'])) {
            $model->attributes = $_POST['Blog'];
            
            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'Blog updated successfully!');
                $this->redirect(array('view', 'id' => $model->id));
            }
        }
        
        $this->render('update', array(
            'model' => $model,
        ));
    }
    
    // Action: Delete blog (Admin only)
    public function actionDelete($id)
    {
        // Check if user is admin
        $userRole = Yii::app()->user->getState('role');
        if ($userRole !== 'admin') {
            throw new CHttpException(403, 'Only administrators can delete blogs.');
        }
        
        $model = $this->loadModel($id);
        
        if ($model->delete()) {
            Yii::app()->user->setFlash('success', 'Blog deleted successfully!');
        } else {
            Yii::app()->user->setFlash('error', 'Error deleting blog.');
        }
        
        // If not AJAX request, redirect
        if (!isset($_GET['ajax'])) {
            $this->redirect(array('index'));
        }
    }
    
    // Action: My blogs (for logged-in users)
    public function actionMyBlogs()
    {
        if (Yii::app()->user->isGuest) {
            $this->redirect(array('site/login'));
            return;
        }
        
        $dataProvider = new CActiveDataProvider('Blog', array(
            'criteria' => array(
                'condition' => 'userId = :userId',
                'params' => array(':userId' => Yii::app()->user->id),
                'order' => 'createdAt DESC',
            ),
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));
        
        $this->render('myBlogs', array(
            'dataProvider' => $dataProvider,
        ));
    }
    
    // Load blog model
    public function loadModel($id)
    {
        $model = Blog::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested blog does not exist.');
        }
        return $model;
    }
}
?>