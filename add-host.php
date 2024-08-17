<?php
    // Putanje do potrebnih datoteka
    $configFile = 'C:/xampp/apache/conf/extra/httpd-vhosts.conf'; // Putanja do httpd-vhosts.conf
    $hostsFile = 'C:/Windows/System32/drivers/etc/hosts'; // Putanja do hosts datoteke

    // Podešavanje za bazu podataka
    $servername = "localhost"; // ili IP adresa MySQL servera
    $username = "root"; // promenite ako koristite drugog korisnika
    $password = ""; // lozinka korisnika
    $charset = 'utf8mb4';

    try {
        $pdo = new PDO("mysql:host=$servername;charset=$charset", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

    // Funkcija za kreiranje baze podataka
    function createDatabase($pdo, $dbName)
    {
        // Zamena razmaka sa donjom crticom i uklanjanje domena
        $dbName = str_replace(' ', '_', $dbName); // Zamena razmaka sa donjom crticom
        $dbName = preg_replace('/\.(local|[a-zA-Z]+)$/', '', $dbName); // Uklanjanje domena

        // Proverite da li ime baze podataka odgovara pravilima
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $dbName)) {
            return "Database name contains invalid characters. Only letters, numbers, and underscores are allowed.";
        }

        try {
            $pdo->exec("CREATE DATABASE `$dbName` CHARACTER SET utf8 COLLATE utf8_unicode_ci");
            return "Database $dbName created successfully!";
        } catch (PDOException $e) {
            return "Failed to create database $dbName: " . $e->getMessage();
        }
    }

    // Funkcija za brisanje baze podataka
    function deleteDatabase($pdo, $dbName)
    {
        // Zamena razmaka sa donjom crticom i uklanjanje domena
        $dbName = str_replace(' ', '_', $dbName); // Zamena razmaka sa donjom crticom
        $dbName = preg_replace('/\.(local|[a-zA-Z]+)$/', '', $dbName); // Uklanjanje domena

        // Proverite da li ime baze podataka odgovara pravilima
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $dbName)) {
            return "Database name contains invalid characters. Only letters, numbers, and underscores are allowed.";
        }

        try {
            $pdo->exec("DROP DATABASE `$dbName`");
            return "Database $dbName deleted successfully!";
        } catch (PDOException $e) {
            return "Failed to delete database $dbName: " . $e->getMessage();
        }
    }

    // Funkcija za brisanje foldera
    function deleteFolder($folderPath)
    {
        // Proverava da li folder postoji
        if (!is_dir($folderPath)) {
            return "Folder $folderPath does not exist.";
        }

        // Recursivno briše sve fajlove i foldere unutar foldera
        $files = array_diff(scandir($folderPath), array('.', '..'));
        foreach ($files as $file) {
            $filePath = "$folderPath/$file";
            (is_dir($filePath)) ? deleteFolder($filePath) : unlink($filePath);
        }
        return rmdir($folderPath) ? "Folder $folderPath deleted successfully!" : "Failed to delete folder $folderPath.";
    }

    // Funkcija za brisanje virtualnog hosta
    function deleteVirtualHost($domainName)
    {
        global $hostsFile, $configFile, $pdo;

        // Zamena razmaka sa donjom crticom i uklanjanje domena
        $dbName = str_replace(' ', '_', $domainName); // Zamena razmaka sa donjom crticom
        $dbName = preg_replace('/\.(local|[a-zA-Z]+)$/', '', $dbName); // Uklanjanje domena

        // Uklanjanje unosa iz hosts fajla
        $hostsContent = file_get_contents($hostsFile);
        $hostsContent = preg_replace("/^.*\s+$domainName\s*$/m", '', $hostsContent);
        file_put_contents($hostsFile, $hostsContent);

        // Uklanjanje unosa iz vhost.conf fajla
        $vhostContent = file_get_contents($configFile);
        $vhostContent = preg_replace("/<VirtualHost[^>]*>.*?ServerName\s+$domainName.*?<\/VirtualHost>\s*/is", '', $vhostContent);
        file_put_contents($configFile, $vhostContent);

        // Brisanje baze podataka
        $dbDeleteResult = deleteDatabase($pdo, $dbName);

        // Brisanje foldera
        $folderPath = "C:/xampp/htdocs/$dbName";
        $folderDeleteResult = deleteFolder($folderPath);

        // Restartovanje Apache servera da bi izmene stupile na snagu
        shell_exec('C:\xampp\apache\bin\httpd.exe -k restart');

        return "Virtualni host za $domainName je uspešno izbrisan. $dbDeleteResult $folderDeleteResult";
    }

    // Obrada POST zahteva
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['server_name'])) {
            $serverName = $_POST['server_name'];
            $documentRoot = $_POST['document_root'];

            // Validacija unosa
            if (empty($serverName) || empty($documentRoot)) {
                $error = "All fields are required.";
            } else {
                // Provera da li već postoji u httpd-vhosts.conf
                $existingConfig = file_get_contents($configFile);
                if (strpos($existingConfig, $serverName) !== false) {
                    $error = "A virtual host with this server name already exists.";
                } else {
                    // Dodavanje novog virtualnog hosta u httpd-vhosts.conf
                    $vhostConfig = "
    <VirtualHost *:80>
        ServerAdmin webmaster@localhost
        DocumentRoot \"$documentRoot\"
        ServerName $serverName
        ErrorLog \"logs/$serverName-error.log\"
        CustomLog \"logs/$serverName-access.log\" common
    </VirtualHost>
    ";
                    file_put_contents($configFile, $vhostConfig, FILE_APPEND | LOCK_EX);

                    // Dodavanje unosa u hosts datoteku
                    $hostEntry = "127.0.0.1 $serverName\n";
                    $existingHosts = file_get_contents($hostsFile);
                    if (strpos($existingHosts, $serverName) === false) {
                        file_put_contents($hostsFile, $hostEntry, FILE_APPEND | LOCK_EX);
                    } else {
                        $error = "A host with this name already exists in hosts file.";
                    }

                    // Kreiranje baze podataka
                    if (!isset($error)) {
                        $dbCreateResult = createDatabase($pdo, $serverName);
                        // Kreiranje foldera
                        $folderPath = "C:/xampp/htdocs/" . str_replace(' ', '_', preg_replace('/\.(local|[a-zA-Z]+)$/', '', $serverName));
                        if (!file_exists($folderPath)) {
                            mkdir($folderPath, 0755, true);
                        }
                        $success = "Virtual host added successfully! $dbCreateResult";
                    }
                }
            }
        } elseif (isset($_POST['delete_domain'])) {
            $deleteDomain = $_POST['delete_domain'];
            $success = deleteVirtualHost($deleteDomain);
        }
    }

    // Funkcija za dobijanje postojećih domena iz vhosts.conf
    function getExistingDomains()
    {
        global $configFile;

        if (!file_exists($configFile)) {
            return []; // Fajl ne postoji
        }

        $vhostContent = file_get_contents($configFile);

        // Pronađite sve domene iz VirtualHost blokova koristeći jednostavan regex
        preg_match_all("/ServerName\s+([^\s]+)/m", $vhostContent, $matches);

        return array_unique($matches[1]); // Osiguraj da se domene ne ponavljaju
    }

    // Dobijanje postojećih domena za prikaz
    $domains = getExistingDomains();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Virtual Host</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <meta name="description" content="Manage your virtual hosts easily with this tool." />
    <meta name="keywords" content="virtualhost, apache, xampp, management" />
    <link href="/dashboard/stylesheets/normalize.css" rel="stylesheet" type="text/css" />
    <link href="/dashboard/stylesheets/all.css" rel="stylesheet" type="text/css" />
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/3.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <script src="/dashboard/javascripts/modernizr.js" type="text/javascript"></script>
    <link href="/dashboard/images/favicon.png" rel="icon" type="image/png" />
</head>
<body class="index">
    <header class="header contain-to-grid">
        <nav class="top-bar" data-topbar>
            <ul class="title-area">
                <li class="name">
                    <h1><a href="/dashboard/index.html">Apache Friends</a></h1>
                </li>
                <li class="toggle-topbar menu-icon">
                    <a href="#"><span>Menu</span></a>
                </li>
            </ul>
            <section class="top-bar-section">
                <ul class="left">
                    <li class="item "><a href="/dashboard/faq.html">FAQs</a></li>
                    <li class="item "><a href="/dashboard/howto.html">HOW-TO Guides</a></li>
                    <li class="item "><a target="_blank" href="/dashboard/phpinfo.php">PHPInfo</a></li>
                    <li class="item "><a target="_blank" href="/phpmyadmin/">phpMyAdmin</a></li>
                    <li class="item"><a target="_blank" href="/dashboard/add-host.php">Add Host</a></li>
                </ul>
            </section>
        </nav>
    </header>
    <div class="wrapper">
        <div class="hero">
            <div class="row">
                <div class="large-12 columns">
                    <h1>
                        <img src="/dashboard/images/virtual-host.png" />
                        <span>Virtual Host Manager</span>
                    </h1>
                </div>
            </div>
        </div>
        <div class="container mt-4">
            <h1>Add Virtual Host</h1>
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= $success; ?></div>
            <?php elseif (isset($error)): ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php endif; ?>
            <form action="add-host.php" method="post">
                <div class="form-group">
                    <label for="server_name">Server Name:</label>
                    <input type="text" id="server_name" name="server_name" class="form-control" placeholder="example.local" />
                </div>
                <div class="form-group">
                    <label for="document_root">Document Root:</label>
                    <input type="text" id="document_root" name="document_root" class="form-control" placeholder="C:/xampp/htdocs/example" />
                </div>
                <button type="submit" class="btn btn-primary">Add Host</button>
            </form>
            <h2 class="mt-5">Existing Domains</h2>
            <table class="table table-bordered mt-4 text-center">
                <thead>
                    <tr>
                        <th class="text-center">Domain</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($domains as $domain): ?>
                        <tr>
                            <td><?= htmlspecialchars($domain); ?></td>
                            <td>
                                <form method="POST" action="add-host.php" style="display:inline;">
                                    <input type="hidden" name="delete_domain" value="<?= htmlspecialchars($domain); ?>">
                                    <button type="submit" class="btn btn-danger"
                                        <?php if ($domain === 'localhost') echo 'disabled'; ?>>
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>