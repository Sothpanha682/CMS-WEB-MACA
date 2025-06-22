<?php
// This is a simplified version of the assistant that works without database connections
// Place this file in the root directory for testing

// Set headers for JSON response
header('Content-Type: application/json');

// Get the question from the request
$data = json_decode(file_get_contents('php://input'), true);
$question = isset($data['question']) ? trim($data['question']) : '';

if (empty($question)) {
    echo json_encode(['error' => 'Question is required']);
    exit;
}

// Generate a simple response
$response = "Thank you for your question about \"$question\". I'm a simple version of the MACA assistant. ";
$response .= "In the full version, I can provide information about majors, careers, and educational opportunities.";

// Return the response
echo json_encode(['response' => $response]);
?>
