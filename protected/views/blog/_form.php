<?php
// protected/views/blog/_form.php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'blog-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>

<?php echo $form->errorSummary($model); ?>

<div class="form-group">
    <?php echo $form->labelEx($model, 'title'); ?>
    <?php echo $form->textField($model, 'title', array('class' => 'form-control', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'title'); ?>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'excerpt'); ?>
    <?php echo $form->textArea($model, 'excerpt', array('class' => 'form-control', 'rows' => 3, 'maxlength' => 500)); ?>
    <?php echo $form->error($model, 'excerpt'); ?>
    <small class="text-muted">Short summary (optional, max 500 characters)</small>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'content'); ?>
    <?php echo $form->textArea($model, 'content', array('class' => 'form-control', 'rows' => 15)); ?>
    <?php echo $form->error($model, 'content'); ?>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'status'); ?>
    <?php echo $form->dropDownList($model, 'status', $model->getStatusOptions(), array('class' => 'form-control')); ?>
    <?php echo $form->error($model, 'status'); ?>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'featuredImage'); ?>
    <?php echo $form->textField($model, 'featuredImage', array('class' => 'form-control', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'featuredImage'); ?>
    <small class="text-muted">Image filename or URL</small>
</div>

<!-- SIMPLIFIED: Remove date picker for now -->
<div class="form-group">
    <?php echo $form->labelEx($model, 'publishedAt'); ?>
    <?php echo $form->textField($model, 'publishedAt', array('class' => 'form-control')); ?>
    <?php echo $form->error($model, 'publishedAt'); ?>
    <small class="text-muted">Leave empty to auto-set when publishing (Format: YYYY-MM-DD)</small>
</div>

<div class="form-group buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-primary')); ?>
    <?php echo CHtml::link('Cancel', array('index'), array('class' => 'btn btn-default')); ?>
</div>

<?php $this->endWidget(); ?>