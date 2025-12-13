<?php
/* @var $this AuthController */
/* @var $model User */

$this->pageTitle = Yii::app()->name . ' - Register';
?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Register</h3>
                </div>
                <div class="card-body">
                    <?php $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'register-form',
                        'enableClientValidation' => true,
                        'clientOptions' => array(
                            'validateOnSubmit' => true,
                        ),
                    )); ?>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <?php echo $form->labelEx($model, 'name'); ?>
                            <?php echo $form->textField($model, 'name', array('class' => 'form-control', 'placeholder' => 'Full Name')); ?>
                            <?php echo $form->error($model, 'name'); ?>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <?php echo $form->labelEx($model, 'email'); ?>
                            <?php echo $form->textField($model, 'email', array('class' => 'form-control', 'placeholder' => 'Email')); ?>
                            <?php echo $form->error($model, 'email'); ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <?php echo $form->labelEx($model, 'password'); ?>
                            <?php echo $form->passwordField($model, 'password', array('class' => 'form-control', 'placeholder' => 'Password')); ?>
                            <?php echo $form->error($model, 'password'); ?>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <?php echo $form->labelEx($model, 'phone'); ?>
                            <?php echo $form->textField($model, 'phone', array('class' => 'form-control', 'placeholder' => 'Phone Number')); ?>
                            <?php echo $form->error($model, 'phone'); ?>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <?php echo CHtml::submitButton('Register', array('class' => 'btn btn-primary')); ?>
                    </div>
                    
                    <div class="text-center mt-3">
                        <p>Already have an account? <a href="<?php echo $this->createUrl('login'); ?>">Login here</a></p>
                    </div>
                    
                    <?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
</div>