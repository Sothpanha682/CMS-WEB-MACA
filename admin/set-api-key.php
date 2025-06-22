<?php
// Include authentication check
require_once '../includes/auth.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ../index.php?page=login');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Process form submission
$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['api_key'])) {
    $apiKey = trim($_POST['api_key']);
    
    // Validate API key (basic validation)
    if (empty($apiKey)) {
        $message = 'API key cannot be empty.';
    } else {
        // Path to the API keys file
        $apiKeysFile = '../config/api-keys.php';
        
        // Create backup of the current file
        if (file_exists($apiKeysFile)) {
            copy($apiKeysFile, $apiKeysFile . '.bak');
        }
        
        // Create or update the API keys file
        $fileContent = "<?php\n";
        $fileContent .= "// This file contains API keys for various services\n";
        $fileContent .= "// IMPORTANT: Keep this file secure and never expose these keys publicly\n\n";
        $fileContent .= "// DeepSeek API key for the AI assistant\n";
        $fileContent .= "define('DEEPSEEK_API_KEY', '" . addslashes($apiKey) . "');\n\n";
        $fileContent .= "// You can add other API keys here as needed\n";
        $fileContent .= "// define('OTHER_API_KEY', 'your_other_api_key');\n";
        $fileContent .= "?>";
        
        // Write to file
        if (file_put_contents($apiKeysFile, $fileContent)) {
            $message = 'DeepSeek API key has been successfully updated.';
            $success = true;
            
            // Log the action
            error_log('Admin user ID ' . $_SESSION['user_id'] . ' updated the DeepSeek API key');
        } else {
            $message = 'Failed to update API key. Please check file permissions.';
        }
    }
}

// Get current API key if it exists
$currentApiKey = '';
if (defined('DEEPSEEK_API_KEY')) {
    $currentApiKey = DEEPSEEK_API_KEY;
    if ($currentApiKey === 'YOUR_DEEPSEEK_API_KEY') {
        $currentApiKey = '';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set DeepSeek API Key - MACA Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        .container {
            max-width: 800px;
            margin-top: 50px;
        }
        .api-key-form {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .test-result {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
            display: none;
        }
        .test-result pre {
            max-height: 300px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Set DeepSeek API Key</h1>
        <p class="lead">Configure the DeepSeek API key for the AI assistant.</p>
        
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $success ? 'success' : 'danger'; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <div class="api-key-form">
            <form method="post" action="">
                <div class="form-group">
                    <label for="api_key">DeepSeek API Key:</label>
                    <input type="text" class="form-control" id="api_key" name="api_key" 
                           value="<?php echo htmlspecialchars($currentApiKey); ?>" 
                           placeholder="Enter your DeepSeek API key">
                    <small class="form-text text-muted">
                        This key is used to authenticate requests to the DeepSeek AI API.
                        Get your API key from the <a href="https://deepseek.com" target="_blank">DeepSeek website</a>.
                    </small>
                </div>
                <button type="submit" class="btn btn-primary">Save API Key</button>
                <button type="button" id="test-api" class="btn btn-secondary">Test API Connection</button>
            </form>
        </div>
        
        <div class="test-result" id="test-result">
            <h4>API Test Result</h4>
            <div class="spinner-border text-primary" role="status" id="test-spinner">
                <span class="sr-only">Loading...</span>
            </div>
            <pre id="test-output"></pre>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5>About the DeepSeek API Integration</h5>
            </div>
            <div class="card-body">
                <p>The DeepSeek API powers the AI assistant on your website, allowing it to provide intelligent responses to user questions about majors, careers, and educational opportunities.</p>
                
                <h6>Benefits:</h6>
                <ul>
                    <li>Provides detailed, accurate responses to a wide range of questions</li>
                    <li>Offers personalized suggestions based on user queries</li>
                    <li>Enhances user engagement on your website</li>
                    <li>Reduces the need for manual responses to common questions</li>
                </ul>
                
                <h6>Implementation Details:</h6>
                <ul>
                    <li>The AI assistant is accessible via the chat widget on your website</li>
                    <li>Responses are generated in real-time using the DeepSeek API</li>
                    <li>Fallback responses are provided if the API is unavailable</li>
                    <li>User questions and AI responses are not stored permanently</li>
                </ul>
                
                <a href="../index.php?page=dashboard" class="btn btn-primary">Return to Dashboard</a>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#test-api').click(function() {
                // Show test result area and spinner
                $('#test-result').show();
                $('#test-spinner').show();
                $('#test-output').text('Testing API connection...');
                
                // Send test request
                $.ajax({
                    url: '../api/test-deepseek.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Format JSON response
                        var formattedOutput = JSON.stringify(data, null, 2);
                        $('#test-output').text(formattedOutput);
                        
                        // Add color based on status
                        if (data.status === 'success') {
                            $('#test-result').removeClass('bg-danger').addClass('bg-success text-white');
                        } else {
                            $('#test-result').removeClass('bg-success').addClass('bg-danger text-white');
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#test-output').text('Error testing API: ' + error);
                        $('#test-result').removeClass('bg-success').addClass('bg-danger text-white');
                    },
                    complete: function() {
                        // Hide spinner
                        $('#test-spinner').hide();
                    }
                });
            });
        });
    </script>
</body>
</html>
