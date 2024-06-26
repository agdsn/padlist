<?php
session_start();

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

$provider = require 'providerConfig.php';

// Check if user is not logged in
if (!isset($_SESSION['user'])) {
    // User is not logged in, initiate the OAuth login process
    $authorizationUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();

    header('Location: ' . $authorizationUrl);
    exit;
}

$shortName = explode(" ", $_SESSION['user'])[0];

$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');

try {
    $dbh = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}

$query = 'SELECT "Notes".title, "Notes"."updatedAt", "Notes"."shortid", "Users".profile FROM "Notes" JOIN "Users" ON "Notes"."ownerId" = "Users".id WHERE (permission = \'freely\' OR permission = \'editable\' OR permission = \'limited\') ORDER BY "Notes"."updatedAt" DESC';
try {
    $stmt = $dbh->query($query);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}

function formatDateString($stringDate)
{
    $datetime = DateTime::createFromFormat('Y-m-d H:i:s.uP', $stringDate);
    $formattedDate = $datetime->format('d.m.Y H:i');
    return $formattedDate;
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pad lister</title>
    <link rel="stylesheet" href="/pico.min.css">

</head>

<body>
    <div class="container">
        <br>
        <h6>Willkommen <?= $shortName ?> ✨</h6>
        <table>
            <tr>
                <th>Titel</th>
                <th>Owner</th>
                <th>Last edit</th>
            </tr>

            <?php
            foreach ($rows as $row) {
            ?>
                <tr>
                    <td>
                        <a href="<?= getenv("HEDGEDOC_URL") ?>/<?= $row['shortid'] ?>"><?= $row['title'] ?></a>
                    </td>
                    <td>
                        <?= json_decode($row['profile'])->displayName ?>
                    </td>
                    <td>
                        <?= formatDateString($row['updatedAt']) ?>
                    </td>
                </tr>

            <?php
            }
            ?>
        </table>
        <br><br>
        <a href="logout.php">Logout</a>
        <br><br>
    </div>
</body>

</html>
