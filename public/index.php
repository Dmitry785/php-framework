<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        require_once __DIR__ . '/../vendor/autoload.php';

        use App\ORM\Database;
        use LukaszZychal\EnvLoader\EnvLoader;

        if(!EnvLoader::load(__DIR__ . "/../.env") 
            && !EnvLoader::load(__DIR__ . "/../.env.example"))
            die("Unable to load env file");
        $database = new Database(EnvLoader::get("DB_NAME"),
            EnvLoader::get("DB_USERNAME"),
            EnvLoader::get("DB_PASSWORD", ""),
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        
    ?>
    <h1>Hello</h1>
</body>
</html>