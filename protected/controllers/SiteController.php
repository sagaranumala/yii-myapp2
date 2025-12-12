<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	public function filters() {
    return array('accessControl'); // enable access control filter
}

public function accessRules() {
    return array(
        array('allow',
            'actions'=>array('index','signup','login','contact','page','calculator','simpleCalc','testDb','testCar'),
            'users'=>array('*'), // allow all users including guests
        ),
        array('allow',
            'users'=>array('@'), // authenticated users
        ),
        array('deny',
            'users'=>array('*'), // deny all others
        ),
    );
}


	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	// public function actionSignup()
	// 	{
	// 		error_log("Signup action called!"); // Log to PHP error log
	// 		echo "<pre>Signup action called!</pre>"; // Optional visual check
	// 		$model = new SignupForm;

	// 		if (isset($_POST['SignupForm'])) {
	// 			$model->attributes = $_POST['SignupForm'];
	// 			error_log("POST received: " . print_r($_POST['SignupForm'], true)); // Log POST data
	// 		}

	// 		$this->render('signup', array('model' => $model));
	// 	}

	public function actionSignup()
{
    $model = new SignupForm;

    if (isset($_POST['SignupForm'])) {
        $model->attributes = $_POST['SignupForm'];
        Yii::log("Signup POST data: " . print_r($_POST['SignupForm'], true), 'info');

        if ($model->validate()) {
            Yii::log("Validation passed", 'info');

            if ($model->register()) {
                Yii::log("User registered successfully", 'info');
                Yii::app()->user->setFlash('success', 'Registration successful!');
                $this->redirect(array('site/index'));
            } else {
                Yii::log("Registration failed: " . print_r($model->getErrors(), true), 'error');
                Yii::app()->user->setFlash('error', 'Registration failed. Check logs.');
            }

        } else {
            Yii::log("Validation errors: " . print_r($model->getErrors(), true), 'warning');

            // Collect all validation errors into flash for toast
            $errors = $model->getErrors();
            $errorMessages = [];
            foreach ($errors as $field => $messages) {
                foreach ($messages as $msg) {
                    $errorMessages[] = $msg;
                }
            }
            Yii::app()->user->setFlash('error_fields', implode('|', $errorMessages));
        }
    }

    $this->render('signup', array('model' => $model));
}





	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	public function actionCalculator()
	{
        // Disable CSRF validation for this action
    Yii::app()->detachEventHandler('onBeginRequest', array(Yii::app()->request, 'validateCsrfToken'));

		$result = null;
		$num1 = isset($_POST['num1']) ? $_POST['num1'] : 0;
		$num2 = isset($_POST['num2']) ? $_POST['num2'] : 0;
		$operation = isset($_POST['operation']) ? $_POST['operation'] : 'add';
		
		if (isset($_POST['calculate'])) {
			switch ($operation) {
				case 'add':
					$result = $num1 + $num2;
					break;
				case 'subtract':
					$result = $num1 - $num2;
					break;
				case 'multiply':
					$result = $num1 * $num2;
					break;
				case 'divide':
					$result = $num2 != 0 ? $num1 / $num2 : 'Cannot divide by zero';
					break;
			}
		}
		
		$this->render('calculator', array(
			'num1' => $num1,
			'num2' => $num2,
			'operation' => $operation,
			'result' => $result,
		));
	}

	public function actionTestDb() {
    $connection = Yii::app()->db;

    try {
        // Test query
        $connection->createCommand("
            CREATE TABLE IF NOT EXISTS test_connection (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL
            )
        ")->execute();

        // Insert a test row
        $connection->createCommand("
            INSERT INTO test_connection (name) VALUES ('Test User')
        ")->execute();

        // Fetch the row
        $result = $connection->createCommand("SELECT * FROM test_connection")->queryAll();

        echo '<pre>';
        print_r($result);
        echo '</pre>';
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Add this method to your SiteController class
public function actionSimpleCalc()
{
    $result = null;
    $num1 = 0;
    $num2 = 0;
    $operation = 'add';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $num1 = isset($_POST['num1']) ? floatval($_POST['num1']) : 0;
        $num2 = isset($_POST['num2']) ? floatval($_POST['num2']) : 0;
        $operation = isset($_POST['operation']) ? $_POST['operation'] : 'add';
        
        switch ($operation) {
            case 'add':
                $result = $num1 + $num2;
                break;
            case 'subtract':
                $result = $num1 - $num2;
                break;
            case 'multiply':
                $result = $num1 * $num2;
                break;
            case 'divide':
                $result = $num2 != 0 ? $num1 / $num2 : 'Cannot divide by zero';
                break;
        }
    }
    
    $this->render('simplecalc', array(
        'result' => $result,
        'num1' => $num1,
        'num2' => $num2,
        'operation' => $operation
    ));
}
public function actionTestCar()
{
    echo "<h3>Testing Car Model</h3>";
    
    // Test 1: Check if file exists
    $modelFile = Yii::getPathOfAlias('application.models') . '/Car.php';
    echo "Model file path: " . $modelFile . "<br>";
    echo "File exists: " . (file_exists($modelFile) ? 'YES' : 'NO') . "<br><br>";
    
    // Test 2: Try to include manually
    echo "<h4>Manual include test:</h4>";
    if (file_exists($modelFile)) {
        require_once($modelFile);
        echo "File included successfully<br>";
    } else {
        echo "File not found<br>";
    }
    
    // Test 3: Check if class exists
    echo "<h4>Class existence check:</h4>";
    if (class_exists('Car')) {
        echo "✓ Car class exists<br>";
        
        // Test 4: Create instance
        try {
            $car = new Car();
            echo "✓ Car instance created<br>";
            echo "Table name: " . $car->tableName() . "<br>";
            
            // Test 5: Test static method
            echo "Static model method: " . get_class(Car::model()) . "<br>";
            
        } catch (Exception $e) {
            echo "✗ Error creating instance: " . $e->getMessage() . "<br>";
        }
    } else {
        echo "✗ Car class does NOT exist<br>";
        
        // Try to find why
        echo "<h4>Debugging class loading:</h4>";
        $classes = get_declared_classes();
        foreach ($classes as $class) {
            if (strpos($class, 'Car') !== false) {
                echo "Found similar class: $class<br>";
            }
        }
    }
    
    // Test 6: Check Yii imports
    echo "<h4>Yii imports:</h4>";
    $import = Yii::app()->import;
    echo "Import paths configured...<br>";
}

}