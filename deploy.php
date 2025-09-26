<?php
    /**
     * GIT DEPLOYMENT SCRIPT
     *
     * Used for automatically deploying websites via GitHub or Bitbucket
     *
     * @version 3.0
     * @link    https://gist.github.com/odeion/213a1227222722272227222722272227
     */

    // The name of the branch to pull from.
    $branch = 'main'; // Assuming 'main' is the default branch, adjust if needed.

    // The path to your Git repository.
    $repo_path = __DIR__;

    // Log file path
    $log_file = 'deploy.log';

    // Secret key for webhook verification (optional, but recommended)
    $secret = '6d4dd0bd8c4c50b7828c5d5df81c30153a77096657e5c79c76b043f759df0b2e'; // CHANGE THIS to a strong, random string

    // Function to log messages
    function log_message($message) {
        global $log_file;
        file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ' . $message . "\n", FILE_APPEND);
    }

    // Check for secret key if provided
    if ($secret !== 'YOUR_SECRET_KEY') {
        $headers = getallheaders();
        $hubSignature = isset($headers['X-Hub-Signature']) ? $headers['X-Hub-Signature'] : null;

        if (!isset($hubSignature)) {
            log_message('No X-Hub-Signature found. Aborting.');
            http_response_code(403);
            die('No X-Hub-Signature found.');
        }

        $payload = file_get_contents('php://input');
        $signature = 'sha1=' . hash_hmac('sha1', $payload, $secret);

        if (!hash_equals($hubSignature, $signature)) {
            log_message('X-Hub-Signature does not match. Aborting.');
            http_response_code(403);
            die('X-Hub-Signature does not match.');
        }
    }

    // Only respond to POST requests
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        log_message('Deployment initiated by POST request.');

        // Execute git pull command
        $command = "cd $repo_path && git pull origin $branch 2>&1";
        $output = shell_exec($command);

        log_message("Git pull output:\n$output");

        echo "<pre>$output</pre>";
    } else {
        log_message('Access denied. Only POST requests are allowed.');
        http_response_code(403);
        echo 'Access denied. Only POST requests are allowed.';
    }
?>
