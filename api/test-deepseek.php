<?php
// Set headers for JSON response
header('Content-Type: application/json');

// Include API keys
require_once '../config/api-keys.php';

// Check if DeepSeek API key is set
if (!defined('DEEPSEEK_API_KEY') || DEEPSEEK_API_KEY === 'YOUR_DEEPSEEK_API_KEY') {
    echo json_encode([
        'status' => 'error',
        'message' => 'DeepSeek API key is not configured. Please update the config/api-keys.php file.'
    ]);
    exit;
}

// Test question
$testQuestion = "What are some popular majors for students interested in technology?";

// DeepSeek API endpoint
$apiUrl = 'https://api.deepseek.com/v1/chat/completions';

// System prompt
$systemPrompt = "You are MACA's AI assistant, designed to help students with questions about majors, careers, and educational opportunities. 
Provide helpful, accurate, and concise information. 
When appropriate, mention MACA's services like career counseling, talkshows, or roadshows.
Always include 3 relevant follow-up questions at the end of your response.";

// Prepare the request payload
$payload = [
    'model' => 'deepseek-chat',
    'messages' => [
        [
            'role' => 'system',
            'content' => $systemPrompt
        ],
        [
            'role' => 'user',
            'content' => $testQuestion
        ]
    ],
    'temperature' => 0.7,
    'max_tokens' => 800
];

// Initialize cURL session
$ch = curl_init($apiUrl);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . DEEPSEEK_API_KEY
]);

// Execute cURL request
$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

// Close cURL session
curl_close($ch);

// Prepare response
$response = [
    'status' => ($httpCode == 200) ? 'success' : 'error',
    'http_code' => $httpCode
];

if ($httpCode == 200) {
    $apiResponse = json_decode($result, true);
    $response['api_response'] = $apiResponse;
    $response['message'] = 'DeepSeek API test successful!';
} else {
    $response['curl_error'] = $error;
    $response['api_response'] = json_decode($result, true);
    $response['message'] = 'DeepSeek API test failed.';
}

// Output response
echo json_encode($response, JSON_PRETTY_PRINT);
?>
