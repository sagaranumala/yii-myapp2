<?php
/* @var $this SiteController */
/* @var $model SignupForm */

$this->pageTitle = Yii::app()->name . ' - Sign Up';
$this->breadcrumbs = array('Sign Up');
?>

<div class="signup-container">
    <h1 class="text-center">Create Your Account</h1>
    <p class="text-center">Join our community today</p>

    <div class="card">
        <div class="card-body">
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'signup-form',
                'enableClientValidation'=>true,
                'clientOptions'=>array(
                    'validateOnSubmit'=>true,
                ),
                'htmlOptions'=>array('novalidate'=>'novalidate'),
            )); ?>

            <!-- Name -->
            <div class="mb-3">
                <?php echo $form->labelEx($model,'name'); ?>
                <?php echo $form->textField($model,'name',array(
                    'class'=>'form-control','placeholder'=>'Full Name','required'=>true
                )); ?>
                <?php echo $form->error($model,'name'); ?>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <?php echo $form->labelEx($model,'email'); ?>
                <?php echo $form->textField($model,'email',array(
                    'class'=>'form-control','placeholder'=>'Email','type'=>'email','required'=>true
                )); ?>
                <?php echo $form->error($model,'email'); ?>
            </div>

            <!-- Phone -->
            <div class="mb-3">
                <?php echo $form->labelEx($model,'phone'); ?>
                <?php echo $form->textField($model,'phone',array(
                    'class'=>'form-control','placeholder'=>'Phone'
                )); ?>
                <?php echo $form->error($model,'phone'); ?>
            </div>

            <!-- Password -->
            <div class="mb-3 position-relative">
                <?php echo $form->labelEx($model,'password'); ?>
                <?php echo $form->passwordField($model,'password',array(
                    'class'=>'form-control','placeholder'=>'Password','id'=>'password-field','required'=>true
                )); ?>
                <span toggle="#password-field" class="password-toggle" style="position:absolute; right:15px; top:38px; cursor:pointer;">👁️</span>
                <?php echo $form->error($model,'password'); ?>
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <?php echo $form->labelEx($model,'password_repeat'); ?>
                <?php echo $form->passwordField($model,'password_repeat',array(
                    'class'=>'form-control','placeholder'=>'Confirm Password','required'=>true
                )); ?>
                <?php echo $form->error($model,'password_repeat'); ?>
            </div>

            <!-- Role (optional, backend will force 'user') -->
            <div class="mb-3">
                <?php echo $form->labelEx($model,'role'); ?>
                <?php echo $form->dropDownList($model,'role',$model->getRoleOptions(),array(
                    'class'=>'form-control','empty'=>'-- Select Role --'
                )); ?>
                <?php echo $form->error($model,'role'); ?>
            </div>

            <!-- Agree Terms -->
            <div class="mb-3 form-check">
                <?php echo $form->checkBox($model,'agree_terms',array('class'=>'form-check-input')); ?>
                <?php echo $form->label($model,'agree_terms',array('class'=>'form-check-label')); ?>
                <?php echo $form->error($model,'agree_terms'); ?>
            </div>

            <!-- Submit -->
            <div class="mb-3">
                <?php echo CHtml::submitButton('Sign Up',array(
                    'class'=>'btn btn-primary','id'=>'submit-btn','disabled'=>true
                )); ?>
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
    // Password toggle
    $(".password-toggle").click(function(){
        let input = $($(this).attr("toggle"));
        input.attr("type", input.attr("type") === "password" ? "text" : "password");
    });

    // Enable submit button only if required fields are filled and checkbox checked
    function validateForm() {
        let isValid = true;
        $('#signup-form input[required]').each(function(){ 
            if($(this).val() === '') isValid=false; 
        });
        if(!$('#SignupForm_agree_terms').is(':checked')) isValid = false;
        $('#submit-btn').prop('disabled', !isValid);
    }
    $('input, select').on('input change', validateForm);
    validateForm();

    // Toast function
    function showToast(message,type='success'){
        let toast = $('<div class="toast toast-'+type+'">'+message+'</div>');
        $('#toast-container').append(toast);
        setTimeout(()=>{ toast.fadeOut(500,()=>{ toast.remove(); }); },4000);
    }

    // Show flash messages
    <?php if(Yii::app()->user->hasFlash('success')): ?>
        showToast("<?php echo Yii::app()->user->getFlash('success'); ?>",'success');
    <?php endif; ?>
    <?php if(Yii::app()->user->hasFlash('error')): ?>
        showToast("<?php echo Yii::app()->user->getFlash('error'); ?>",'error');
    <?php endif; ?>
    <?php if(Yii::app()->user->hasFlash('error_fields')): ?>
        <?php 
        $msgs = explode('|', Yii::app()->user->getFlash('error_fields'));
        foreach($msgs as $msg): ?>
            showToast("<?php echo $msg; ?>",'error');
        <?php endforeach; ?>
    <?php endif; ?>
});
</script>
