<?php
// Define the path to the .env.local file
$envPath = __DIR__ . '/../.env.local';

// Read the .env.local file
$file = fopen($envPath, "r") or die("Unable to open file!");
$connectionString = "";
while (!feof($file)) {
    $line = fgets($file);
    if (strpos($line, 'DATABASE_URL') !== false) {
        $connectionString = substr($line, strpos($line, "=") + 1);

        break;
    }
}

$connectionString = trim($connectionString, '"');

// Split the string into two parts: credentials and the rest
list($credentials, $rest) = explode('@', $connectionString, 2);

// Remove the protocol from the credentials
$credentials = str_replace('mysql://', '', $credentials);

// Split the credentials into username and password
list($username, $password) = explode(':', $credentials);

// Split the rest into host and the rest
list($host, $rest) = explode('/', $rest, 2);

// The first part of the rest is the database name
$dbname = explode('?', $rest)[0];

$dsn = "mysql:host=$host;dbname=$dbname";



// Create the PDO connection
try {
    $pdo = new PDO($dsn, $username, $password);

    // Define possible status messages for each status category
    $statusMessages = [
        'Normal' => ['Everything is fine', 'No issues detected', 'Running smoothly'],
        'Avertissement' => ['Check module settings', 'Potential issues detected', 'Performance could be improved'],
        'Critique' => ['Immediate attention required', 'Critical issues detected', 'Module not functioning properly']
    ];


    while (true) {
        // Fetching all modules
        $modulesQuery = $pdo->query('SELECT * FROM module');
        $modules = $modulesQuery->fetchAll();


        foreach ($modules as $module) {
            echo  "module : " . $module["module_name"] . PHP_EOL;

            //randomly change the status at random chance
            if ((bool) rand(0, 1)) {


                $statusListQuery =  $pdo->prepare('SELECT * FROM status');
                $statusListQuery->execute();
                $statusList = $statusListQuery->fetchAll();

                $status = $statusList[array_rand($statusList)];


                // Randomly select a status message from the corresponding array
                $statusMessage = $statusMessages[
                    $status['status_category']
                    ][
                    array_rand($statusMessages[$status['status_category']])
            ];

                // Update status_name and status_message
                $pdo->exec("UPDATE module SET status_name = '" . $status['status_name'] . "', status_message = '$statusMessage' WHERE module_id = '" . $module['module_id'] . "'");
                echo  "    NewStatus : " . $status["status_name"];
                echo " of Category : " . $status["status_category"] . PHP_EOL;
                echo "        With message : " . $statusMessage . PHP_EOL;
            }
            // For each module, fetch its associated ModuleTypeValues
            $mtvQuery = $pdo->prepare('SELECT * FROM module_type_value WHERE module_type_name = ?');
            $mtvQuery->execute([$module['module_type_name']]);
            $mtvs = $mtvQuery->fetchAll();

            // Check status of the module, if it is 'severe', then don't create the logs
            $statusQuery = $pdo->prepare('SELECT * FROM status WHERE status_name = ?');
            $statusQuery->execute([$module['status_name']]);
            $status = $statusQuery->fetch();

            if ($status['status_category'] !== 'Critique') {
                foreach ($mtvs as $mtv) {
                    // Here we are simulating the generation of a new value log. Adjust according to your requirements
                    $newLog = [
                        'data' => rand(1, 100), // Random data for demonstration
                        'log_date' => date('Y-m-d H:i:s'), // Current date and time
                        'module_type_value_id' => $mtv['module_type_value_id'],
                        'module_id' => $module['module_id']
                    ];

                    // insert the log into the database
                    $logInsert = $pdo->prepare("INSERT INTO value_log (data, log_date, module_type_value_id, module_id) VALUES (?,?,?,?)");
                    $logInsert->execute(array_values($newLog));

                    var_dump($newLog);
                }
            }
            echo PHP_EOL;
        }
        // Sleep for 15 seconds before the loop continues
        sleep(15);
    }
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
