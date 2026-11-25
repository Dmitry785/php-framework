<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
</head>
<body>
    <h1>Пользователи</h1>
    <?php foreach($users as $user): ?>
        <li><?= htmlspecialchars($user->getName())?></li>
    <?php endforeach; ?>
</body>
</html>