<? php
/**
 * php/test-permissions.php - Test file permissions
 * DELETE THIS FILE after testing! 
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permissions Test</title>
    <link rel="stylesheet" href="../css/style.css"> <!-- ✅ CHANGÉ -->
    <style>
        .test-result {
            padding: 15px;
            margin: 10px 0;
            border-left: 4px solid;
            font-family: monospace;
        }
        .test-success {
            border-color: #00ff00;
            background-color: rgba(0, 255, 0, 0.1);
            color: #00ff00;
        }
        .test-error {
            border-color: #ff0000;
            background-color: rgba(255, 0, 0, 0.1);
            color: #ff0000;
        }
        . test-warning {
            border-color: #ffaa00;
            background-color: rgba(255, 170, 0, 0.1);
            color: #ffaa00;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 style="color: var(--text-color);">🔍 PERMISSIONS TEST</h1>
        <p class="prompt">root@miiraak:~/php$ ./test-permissions.sh</p>

        <? php
        $tests = [
            'stats_file' => [
                'name' => 'Stats File (../data/stats.json)',
                'path' => '../data/stats.json', // ✅ CHANGÉ
                'should_exist' => true,
                'should_read' => true,
                'should_write' => true,
            ],
            'logs_dir' => [
                'name' => 'Logs Directory (../logs/)',
                'path' => '../logs', // ✅ CHANGÉ
                'should_exist' => true,
                'should_read' => true,
                'should_write' => true,
            ],
            'logs_file' => [
                'name' => 'Logs File (../logs/download_errors. log)',
                'path' => '../logs/download_errors.log', // ✅ CHANGÉ
                'should_exist' => false,
                'should_read' => true,
                'should_write' => true,
            ],
            'files_dir' => [
                'name' => 'Files Directory (../files/)',
                'path' => '../files', // ✅ CHANGÉ
                'should_exist' => true,
                'should_read' => true,
                'should_write' => false,
            ],
        ];

        foreach ($tests as $key => $test) {
            $path = $test['path'];
            $exists = file_exists($path);
            $readable = $exists && is_readable($path);
            $writable = $exists && is_writable($path);
            
            $allGood = true;
            $messages = [];
            
            if ($test['should_exist'] && !$exists) {
                $allGood = false;
                $messages[] = "❌ File/directory does not exist";
            } elseif ($exists) {
                $messages[] = "✓ Exists";
            }
            
            if ($test['should_read'] && $exists && ! $readable) {
                $allGood = false;
                $messages[] = "❌ Not readable";
            } elseif ($readable) {
                $messages[] = "✓ Readable";
            }
            
            if ($test['should_write'] && $exists && !$writable) {
                $allGood = false;
                $messages[] = "❌ Not writable - Run: chmod 666 $path";
            } elseif ($writable) {
                $messages[] = "✓ Writable";
            }
            
            if ($exists) {
                $perms = fileperms($path);
                $permsStr = substr(sprintf('%o', $perms), -4);
                $messages[] = "Permissions: $permsStr";
            }
            
            $class = $allGood ? 'test-success' : 'test-error';
            if (! $test['should_exist'] && ! $exists) {
                $class = 'test-warning';
                $messages[] = "⚠ Will be created automatically";
            }
            
            echo '<div class="test-result ' .  $class . '">';
            echo '<strong>' . $test['name'] . '</strong><br>';
            echo implode(' | ', $messages);
            echo '</div>';
        }
        ?>

        <div style="margin-top: 30px; padding: 20px; border: 2px solid var(--text-color); background-color: rgba(0, 255, 0, 0.05);">
            <h3 style="color: var(--text-color);">📝 Next Steps:</h3>
            <ol style="color: var(--text-white);">
                <li>Fix any ❌ errors shown above</li>
                <li>Create missing directories/files if needed</li>
                <li>Set correct permissions (chmod 666 for writable files)</li>
                <li>Re-run this test until all checks pass</li>
                <li><strong>DELETE this file (php/test-permissions.php) when done!</strong></li>
            </ol>
        </div>

        <div style="text-align: center; margin: 20px 0;">
            <a href="test-permissions.php" style="color: var(--text-color); border: 1px solid var(--text-color); padding: 10px 20px; text-decoration: none;">🔄 Refresh Test</a>
        </div>
    </div>
</body>
</html>