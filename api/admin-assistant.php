<?php
/**
 * AI Assistant Admin Panel
 * 
 * This file provides an admin interface to configure the AI assistant
 */

// Prevent direct access
if (!defined('INCLUDED')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php?page=login');
    exit;
}

// Process form submission
$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and save the settings
    $provider = isset($_POST['provider']) ? $_POST['provider'] : 'openai';
    $apiKey = isset($_POST['api_key']) ? trim($_POST['api_key']) : '';
    $model = isset($_POST['model']) ? trim($_POST['model']) : '';
    
    // Basic validation
    if (empty($apiKey)) {
        $message = 'API key is required';
    } else {
        // Save settings to .env file or database
        $envFile = __DIR__ . '/../.env';
        $envContent = file_exists($envFile) ? file_get_contents($envFile) : '';
        
        // Update or add AI_PROVIDER
        if (preg_match('/AI_PROVIDER=.*/', $envContent)) {
            $envContent = preg_replace('/AI_PROVIDER=.*/', "AI_PROVIDER={$provider}", $envContent);
        } else {
            $envContent .= "\nAI_PROVIDER={$provider}";
        }
        
        // Update or add AI_API_KEY
        if (preg_match('/AI_API_KEY=.*/', $envContent)) {
            $envContent = preg_replace('/AI_API_KEY=.*/', "AI_API_KEY={$apiKey}", $envContent);
        } else {
            $envContent .= "\nAI_API_KEY={$apiKey}";
        }
        
        // Update or add AI_MODEL if provided
        if (!empty($model)) {
            if (preg_match('/AI_MODEL=.*/', $envContent)) {
                $envContent = preg_replace('/AI_MODEL=.*/', "AI_MODEL={$model}", $envContent);
            } else {
                $envContent .= "\nAI_MODEL={$model}";
            }
        }
        
        // Save the updated .env file
        if (file_put_contents($envFile, $envContent)) {
            $message = 'AI assistant settings saved successfully';
            $success = true;
        } else {
            $message = 'Failed to save settings. Please check file permissions.';
        }
    }
}

// Get current settings
$currentProvider = getenv('AI_PROVIDER') ?: 'openai';
$currentApiKey = getenv('AI_API_KEY') ?: '';
$currentModel = getenv('AI_MODEL') ?: '';

// Mask the API key for display
$maskedApiKey = !empty($currentApiKey) ? substr($currentApiKey, 0, 4) . str_repeat('*', strlen($currentApiKey) - 8) . substr($currentApiKey, -4) : '';
?>

<div class="container mt-4">
    <h2>Configure AI Assistant</h2>
    
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $success ? 'success' : 'danger'; ?>" role="alert">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <form method="post">
                <div class="mb-3">
                    <label for="provider" class="form-label">AI Provider</label>
                    <select class="form-select" id="provider" name="provider">
                        <option value="openai" <?php echo $currentProvider === 'openai' ? 'selected' : ''; ?>>OpenAI</option>
                        <option value="azure" <?php echo $currentProvider === 'azure' ? 'selected' : ''; ?>>Azure OpenAI</option>
                        <option value="anthropic" <?php echo $currentProvider === 'anthropic' ? 'selected' : ''; ?>>Anthropic</option>
                        <option value="deepseek" <?php echo $currentProvider === 'deepseek' ? 'selected' : ''; ?>>DeepSeek</option>
                    </select>
                    <div class="form-text">Select the AI provider you want to use</div>
                </div>
                
                <div class="mb-3">
                    <label for="api_key" class="form-label">API Key</label>
                    <input type="password" class="form-control" id="api_key" name="api_key" 
                           placeholder="<?php echo !empty($maskedApiKey) ? $maskedApiKey : 'Enter your API key'; ?>">
                    <div class="form-text">Your API key for authentication with the AI provider</div>
                </div>
                
                <div class="mb-3">
                    <label for="model" class="form-label">Model (Optional)</label>
                    <input type="text" class="form-control" id="model" name="model" 
                           value="<?php echo htmlspecialchars($currentModel); ?>" 
                           placeholder="e.g., gpt-4, claude-2">
                    <div class="form-text">Specify a model or leave blank to use the default</div>
                </div>
                
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                    <a href="index.php?page=dashboard" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card mt-4">
        <div class="card-header">
            Test AI Assistant
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="test_question" class="form-label">Test Question</label>
                <input type="text" class="form-control" id="test_question" placeholder="Enter a test question...">
            </div>
            <button type="button" id="test_button" class="btn btn-info">Test</button>
            
            <div class="mt-3" id="test_result" style="display: none;">
                <h5>Response:</h5>
                <div class="p-3 bg-light rounded" id="test_response"></div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('test_button').addEventListener('click', function() {
    const question = document.getElementById('test_question').value.trim();
    if (!question) {
        alert('Please enter a test question');
        return;
    }
    
    // Show loading state
    const button = this;
    const originalText = button.textContent;
    button.textContent = 'Testing...';
    button.disabled = true;
    
    // Make API request
    fetch('api/assistant.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ question: question })
    })
    .then(response => response.json())
    .then(data => {
        // Display the result
        document.getElementById('test_response').innerHTML = data.response || data.error || 'No response received';
        document.getElementById('test_result').style.display = 'block';
    })
    .catch(error => {
        document.getElementById('test_response').textContent = 'Error: ' + error.message;
        document.getElementById('test_result').style.display = 'block';
    })
    .finally(() => {
        // Reset button state
        button.textContent = originalText;
        button.disabled = false;
    });
});
</script>
