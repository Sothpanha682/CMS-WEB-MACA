<?php
// This file is for debugging the AI assistant API
// It tests the connection to the DeepSeek API and logs the results

// Define INCLUDED constant to prevent direct access to included files
define('INCLUDED', true);

// Include the AI integration class
require_once 'ai-integration.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>AI Assistant Debug Tool</h1>";

// Test the DeepSeek API connection
echo "<h2>Testing DeepSeek API Connection</h2>";

try {
    // Create an instance of the AI integration class
    $apiKey = 'sk-6e3d09fe05594cb88181f9cb89b457d6';
    $ai = new AIIntegration('deepseek', $apiKey);
    
    // Test with a simple question
    $question = "What is 2+2?";
    echo "<p>Sending test question: '{$question}'</p>";
    
    // Generate a response
    $response = $ai->generateResponse($question);
    
    // Display the response
    echo "<p>Response received successfully!</p>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
    
    // Test with a more complex question
    $question = "How to become a software developer?";
    echo "<p>Sending test question: '{$question}'</p>";
    
    // Generate a response
    $response = $ai->generateResponse($question);
    
    // Display the response
    echo "<p>Response received successfully!</p>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
    
} catch (Exception $e) {
    // Display the error
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

// Test the fallback response generator
echo "<h2>Testing Fallback Response Generator</h2>";

try {
    $ai = new AIIntegration();
    
    $questions = [
        "What majors are popular?",
        "What careers have good job prospects?",
        "How do I choose a major?",
        "How to become a software developer?"
    ];
    
    foreach ($questions as $question) {
        echo "<p>Question: '{$question}'</p>";
        $response = $ai->getFallbackResponse($question);
        echo "<div style='border: 1px solid #ccc; padding: 10px; margin-bottom: 20px;'>{$response}</div>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
