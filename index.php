<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV EDITOR DEMO</title>
    <style>
        table td{
            border: 1px solid #eee;
        }
    </style>
</head>
<body>
    <?php
        include 'CSVManager.php';

        $CSVManager = new CSVManager("example.csv", ";", true, true); // Load file
        $CSVManager->selectColumns(["category"]); // Specify which columns should be selected
        $CSVManager->renderContent("table"); // Display content in tabular form
        
        // 

        
    ?>
</body>
</html>