<?php
class DbTestController extends Controller
{
    public function actionIndex()
    {
        try {
            $db = Yii::app()->db; // Get DB component
            $db->active = true;   // Open connection
            
            echo "<h2>Database Tables:</h2>";
            
            // Show all tables
            $tables = $db->createCommand("SHOW TABLES")->queryColumn();
            
            if (empty($tables)) {
                echo "<p>No tables found in the database.</p>";
            } else {
                echo "<ul>";
                foreach ($tables as $table) {
                    echo "<li>$table</li>";
                }
                echo "</ul>";
                
                // Count total tables
                echo "<p>Total tables: " . count($tables) . "</p>";
            }
            
        } catch (Exception $e) {
            echo "<h2>Database connection failed:</h2>";
            echo "<pre>" . $e->getMessage() . "</pre>";
        }
    }
}