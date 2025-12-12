<?php
// protected/views/site/simplecalc.php
// Fix the ternary operator in the result display
?>

<!DOCTYPE html>
<html>
<head>
    <title>Simple Calculator</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .calculator-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            width: 400px;
            padding: 30px;
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
        }
        
        input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input[type="number"]:focus {
            border-color: #667eea;
            outline: none;
        }
        
        select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            background: white;
        }
        
        .calculate-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px;
            width: 100%;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
            margin-top: 10px;
        }
        
        .calculate-btn:hover {
            transform: translateY(-2px);
        }
        
        .result {
            margin-top: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #667eea;
        }
        
        .result h3 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .result-value {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
        }
        
        .example {
            margin-top: 20px;
            padding: 15px;
            background: #eef2ff;
            border-radius: 8px;
            font-size: 14px;
            color: #666;
        }
        
        .example-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #667eea;
        }
        
        .nav-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #667eea;
            text-decoration: none;
        }
        
        .nav-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="calculator-container">
        <h1>📱 Simple Calculator</h1>
        
        <form method="post">
            <div class="form-group">
                <label for="num1">First Number</label>
                <input type="number" id="num1" name="num1" value="<?php echo htmlspecialchars($num1); ?>" 
                       step="any" required placeholder="Enter first number">
            </div>
            
            <div class="form-group">
                <label for="operation">Operation</label>
                <select id="operation" name="operation">
                    <option value="add" <?php echo $operation == 'add' ? 'selected' : ''; ?>>➕ Addition</option>
                    <option value="subtract" <?php echo $operation == 'subtract' ? 'selected' : ''; ?>>➖ Subtraction</option>
                    <option value="multiply" <?php echo $operation == 'multiply' ? 'selected' : ''; ?>>✖️ Multiplication</option>
                    <option value="divide" <?php echo $operation == 'divide' ? 'selected' : ''; ?>>➗ Division</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="num2">Second Number</label>
                <input type="number" id="num2" name="num2" value="<?php echo htmlspecialchars($num2); ?>" 
                       step="any" required placeholder="Enter second number">
            </div>
            
            <button type="submit" class="calculate-btn">Calculate</button>
        </form>
        
        <?php if ($result !== null): ?>
            <div class="result">
                <h3>Result:</h3>
                <div class="result-value">
                    <?php echo $result; ?>
                </div>
                <p style="margin-top: 10px; color: #666; font-size: 14px;">
                    <?php 
                    // FIXED: Add parentheses to nested ternary operator
                    $operator = ($operation == 'add') ? '+' : 
                               (($operation == 'subtract') ? '-' : 
                               (($operation == 'multiply') ? '×' : '÷'));
                    
                    echo htmlspecialchars($num1) . ' ' . $operator . ' ' . 
                         htmlspecialchars($num2) . ' = ' . $result; 
                    ?>
                </p>
            </div>
        <?php endif; ?>
        
        <div class="example">
            <div class="example-title">💡 Quick Examples:</div>
            <p>• 10 + 5 = 15</p>
            <p>• 20 - 8 = 12</p>
            <p>• 6 × 7 = 42</p>
            <p>• 50 ÷ 10 = 5</p>
        </div>
        
        <a href="<?php echo Yii::app()->createUrl('site/index'); ?>" class="nav-link">
            ← Back to Home
        </a>
    </div>
    
    <script>
        // Simple JavaScript for better UX
        document.addEventListener('DOMContentLoaded', function() {
            // Focus on first input
            document.getElementById('num1').focus();
            
            // Clear result when user starts typing
            const inputs = document.querySelectorAll('input[type="number"]');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    const resultDiv = document.querySelector('.result');
                    if (resultDiv) {
                        resultDiv.style.opacity = '0.5';
                    }
                });
            });
        });
    </script>
</body>
</html>