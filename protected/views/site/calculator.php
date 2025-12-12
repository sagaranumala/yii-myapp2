<h1>Simple Calculator</h1>

<form method="post">
    <div>
        <label>Number 1:</label>
        <input type="number" step="any" name="num1" value="<?php echo htmlspecialchars($num1); ?>">
    </div>
    
    <div>
        <label>Number 2:</label>
        <input type="number" step="any" name="num2" value="<?php echo htmlspecialchars($num2); ?>">
    </div>
    
    <div>
        <label>Operation:</label>
        <select name="operation">
            <option value="add" <?php echo $operation == 'add' ? 'selected' : ''; ?>>Add (+)</option>
            <option value="subtract" <?php echo $operation == 'subtract' ? 'selected' : ''; ?>>Subtract (-)</option>
            <option value="multiply" <?php echo $operation == 'multiply' ? 'selected' : ''; ?>>Multiply (*)</option>
            <option value="divide" <?php echo $operation == 'divide' ? 'selected' : ''; ?>>Divide (/)</option>
        </select>
    </div>
    
    <div>
        <button type="submit" name="calculate">Calculate</button>
    </div>
</form>

<?php if ($result !== null): ?>
    <h3 style="color: green;">Result: <?php echo $result; ?></h3>
<?php endif; ?>

<hr>
<p><strong>Debug Info:</strong></p>
<p>POST data: <?php print_r($_POST); ?></p>
<p>CSRF Token Enabled: <?php echo Yii::app()->request->enableCsrfValidation ? 'Yes' : 'No'; ?></p>