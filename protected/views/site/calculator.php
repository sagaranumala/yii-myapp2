<h1>Simple Calculator</h1>

<?php $form = $this->beginWidget('CActiveForm'); ?>

    <div>
        <label>Number 1:</label>
        <?php echo CHtml::textField('num1', $num1); ?>
    </div>
    
    <div>
        <label>Number 2:</label>
        <?php echo CHtml::textField('num2', $num2); ?>
    </div>
    
    <div>
        <label>Operation:</label>
        <?php echo CHtml::dropDownList('operation', $operation, array(
            'add' => 'Add (+)',
            'subtract' => 'Subtract (-)',
            'multiply' => 'Multiply (*)',
            'divide' => 'Divide (/)',
        )); ?>
    </div>
    
    <div>
        <?php echo CHtml::submitButton('Calculate', array('name' => 'calculate')); ?>
    </div>

<?php $this->endWidget(); ?>

<?php if ($result !== null): ?>
    <h3>Result: <?php echo $result; ?></h3>
<?php endif; ?>

<hr>
<p><strong>Debug Info:</strong></p>
<p>POST data: <?php print_r($_POST); ?></p>