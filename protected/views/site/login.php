<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .api-info {
            margin-top: 30px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <h1>Login</h1>
    
    <?php if (Yii::app()->user->hasFlash('error')): ?>
        <div style="color: red; margin-bottom: 15px;">
            <?php echo Yii::app()->user->getFlash('error'); ?>
        </div>
    <?php endif; ?>
    
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'login-form',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    )); ?>
    
    <div class="form-group">
        <?php echo $form->label($model, 'email'); ?>
        <?php echo $form->textField($model, 'email'); ?>
        <?php echo $form->error($model, 'email'); ?>
    </div>
    
    <div class="form-group">
        <?php echo $form->label($model, 'password'); ?>
        <?php echo $form->passwordField($model, 'password'); ?>
        <?php echo $form->error($model, 'password'); ?>
    </div>
    
    <div class="form-group">
        <?php echo $form->checkBox($model, 'rememberMe'); ?>
        <?php echo $form->label($model, 'rememberMe'); ?>
        <?php echo $form->error($model, 'rememberMe'); ?>
    </div>
    
    <div class="form-group">
        <button type="submit">Login</button>
    </div>
    
    <?php $this->endWidget(); ?>
    
    <div class="api-info">
        <h3>API Login (JSON)</h3>
        <p><strong>POST</strong> http://localhost:8080/index.php?r=site/login</p>
        <pre>
{
    "email": "your@email.com",
    "password": "yourpassword"
}
        </pre>
        <p>Or use: http://localhost:8080/index.php?r=auth/login</p>
    </div>
</body>
</html>