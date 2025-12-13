<?php
/* @var $this SiteController */
/* @var $model LoginForm */

$this->pageTitle = Yii::app()->name . ' - Login';
$this->breadcrumbs = array('Login');
?>

<div class="login-container">
    <h1 class="text-center">Login to Your Account</h1>
    <p class="text-center">Enter your credentials to continue</p>

    <div class="card">
        <div class="card-body">
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'login-form',
                'enableClientValidation'=>true,
                'clientOptions'=>array(
                    'validateOnSubmit'=>true,
                ),
                'htmlOptions'=>array('novalidate'=>'novalidate'),
            )); ?>

            <div class="mb-3">
                <?php echo $form->labelEx($model,'email'); ?>
                <?php echo $form->textField($model,'email',array('class'=>'form-control','placeholder'=>'Email','required'=>true)); ?>
                <?php echo $form->error($model,'email'); ?>
            </div>

            <div class="mb-3 position-relative">
                <?php echo $form->labelEx($model,'password'); ?>
                <?php echo $form->passwordField($model,'password',array('class'=>'form-control','placeholder'=>'Password','id'=>'password-field','required'=>true)); ?>
                <span toggle="#password-field" class="password-toggle" style="position:absolute; right:15px; top:38px; cursor:pointer;">👁️</span>
                <?php echo $form->error($model,'password'); ?>
            </div>

            <div class="mb-3 form-check">
                <?php echo $form->checkBox($model,'rememberMe',array('class'=>'form-check-input')); ?>
                <?php echo $form->label($model,'rememberMe',array('class'=>'form-check-label')); ?>
            </div>

            <div class="mb-3">
                <?php echo CHtml::submitButton('Login',array('class'=>'btn btn-primary','id'=>'submit-btn','disabled'=>true)); ?>
            </div>

            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>

<!-- Toast container -->
<div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

<style>
.password-toggle { user-select:none; }
.toast {
    padding: 10px 20px;
    margin-bottom: 10px;
    border-radius: 5px;
    color: #fff;
    font-weight: bold;
    opacity: 0.95;
}
.toast-success { background-color: #28a745; }
.toast-error { background-color: #dc3545; }
</style>

<script>
$(document).ready(function(){
    // Password eye toggle
    $(".password-toggle").click(function(){
        let input = $($(this).attr("toggle"));
        input.attr("type", input.attr("type") === "password" ? "text" : "password");
    });

    // Enable submit button only if required fields are filled
    $('#login-form input[required]').on('input change', function(){
        let isValid = true;
        $('#login-form input[required]').each(function(){ if($(this).val() === '') isValid=false; });
        $('#submit-btn').prop('disabled', !isValid);
    });

    // Toast function
    function showToast(message, type='success') {
        let toast = $('<div class="toast toast-'+type+'">'+message+'</div>');
        $('#toast-container').append(toast);
        setTimeout(()=>{ toast.fadeOut(500,()=>{ toast.remove(); }); }, 4000);
    }

    // Show flash messages as toast
    <?php if(Yii::app()->user->hasFlash('success')): ?>
        showToast("<?php echo Yii::app()->user->getFlash('success'); ?>",'success');
    <?php endif; ?>
    <?php if(Yii::app()->user->hasFlash('error')): ?>
        showToast("<?php echo Yii::app()->user->getFlash('error'); ?>",'error');
    <?php endif; ?>
    <?php if(Yii::app()->user->hasFlash('error_fields')): ?>
        <?php 
            $msgs = explode('|', Yii::app()->user->getFlash('error_fields'));
            foreach($msgs as $msg):
        ?>
            showToast("<?php echo $msg; ?>",'error');
        <?php endforeach; ?>
    <?php endif; ?>
});
</script>
