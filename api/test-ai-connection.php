<?php
// Set headers for JSON response
header('Content-Type: application/json');

// Disable error display in the response
ini_set('display_errors', 0);
error_reporting(0);

// Log errors to server log instead
ini_set('log_errors', 1);
error_log('AI Connection Test API called');

// Include API keys
require_once '../config/api-keys.php';

// Include the AIIntegration class
require_once 'ai-integration.php';

// Check if it's a GET request
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

try {
    // Attempt to instantiate AIIntegration with a dummy key or the configured one
    // This will check if the class can be loaded and basic setup is okay
    $aiIntegration = new AIIntegration('gemini', defined('GEMINI_API_KEY') ? GEMINI_API_KEY : 'YOUR_GEMINI_API_KEY');

    // Attempt a simple response generation to test the API connection
    // Use a very short maxTokens to keep the request light
    $testQuestion = "Hello, what is your purpose?";
    $response = $aiIntegration->setMaxTokens(50)->generateResponse($testQuestion);

    // If we get a response, consider it a success
    if (!empty($response)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'AI API connection successful.',
            'test_response_snippet' => substr($response, 0, 100) . '...' // Return a snippet
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'AI API connection failed: Empty response from API.'
        ]);
    }

} catch (Exception $e) {
    error_log("AI Connection Test Error: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'AI API connection failed: ' . $e->getMessage()
    ]);
}
?>
