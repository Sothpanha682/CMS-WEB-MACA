<?php
/**
 * Setup DeepSeek API
 * 
 * This script sets up the DeepSeek API key in the .env file
 */

// Define the path to the .env file
$envFile = __DIR__ . '/../.env';

// The DeepSeek API key
$apiKey = 'sk-6e3d09fe05594cb88181f9cb89b457d6';

// Create or update the .env file
$envContent = file_exists($envFile) ? file_get_contents($envFile) : '';

// Set the AI provider to DeepSeek
if (preg_match('/AI_PROVIDER=.*/', $envContent)) {
    $envContent = preg_replace('/AI_PROVIDER=.*/', "AI_PROVIDER=deepseek", $envContent);
} else {
    $envContent .= "\nAI_PROVIDER=deepseek";
}

// Set the API key
if (preg_match('/AI_API_KEY=.*/', $envContent)) {
    $envContent = preg_replace('/AI_API_KEY=.*/', "AI_API_KEY={$apiKey}", $envContent);
} else {
    $envContent .= "\nAI_API_KEY={$apiKey}";
}

// Save the updated .env file
if (file_put_contents($envFile, $envContent)) {
    echo "DeepSeek API key has been set up successfully.\n";
} else {
    echo "Failed to save settings. Please check file permissions.\n";
}

// Also update the model if needed
$model = 'deepseek-chat';
if (preg_match('/AI_MODEL=.*/', $envContent)) {
    $envContent = preg_replace('/AI_MODEL=.*/', "AI_MODEL={$model}", $envContent);
} else {
    $envContent .= "\nAI_MODEL={$model}";
}

// Save the updated .env file again
if (file_put_contents($envFile, $envContent)) {
    echo "DeepSeek model has been set up successfully.\n";
} else {
    echo "Failed to save model setting. Please check file permissions.\n";
}

echo "Setup complete. The AI assistant is now configured to use DeepSeek.\n";
?>
