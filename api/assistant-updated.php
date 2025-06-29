<?php
// Set headers for JSON response
header('Content-Type: application/json');

// Disable error display in the response
ini_set('display_errors', 0);
error_reporting(0);

// Log errors to server log instead
ini_set('log_errors', 1);
error_log('AI Assistant API called');

// Include API keys
require_once '../config/api-keys.php';

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get the question from the request
$data = json_decode(file_get_contents('php://input'), true);
$question = isset($data['question']) ? trim($data['question']) : '';

if (empty($question)) {
    http_response_code(400);
    echo json_encode(['error' => 'Question is required']);
    exit;
}

// Log the question for debugging
error_log('Question received: ' . $question);

// Always use the fallback response as DeepSeek API is being removed
$response = getFallbackResponse($question);

// Return the response
echo json_encode($response);


/**
 * Extract suggestions from the AI response
 * 
 * @param string $response The AI response
 * @return array Array of suggestions
 */
function extractSuggestions($response) {
    // Default suggestions if none are found
    $defaultSuggestions = [
        "Tell me about popular majors",
        "What careers have good prospects?",
        "How do I choose the right major?"
    ];
    
    // Try to find questions in the response
    preg_match_all('/\d+\.\s*([^?]+\?)/', $response, $matches);
    
    if (!empty($matches[1])) {
        // Return up to 3 questions found
        $suggestions = array_slice($matches[1], 0, 3);
        return array_map('trim', $suggestions);
    }
    
    // Alternative pattern: look for questions at the end of the response
    if (preg_match('/(?:questions:|follow-up:|you might ask:)\s*((?:[^?]+\?\s*){1,3})/is', $response, $matches)) {
        preg_match_all('/([^?]+\?)/', $matches[1], $questionMatches);
        if (!empty($questionMatches[1])) {
            $suggestions = array_slice($questionMatches[1], 0, 3);
            return array_map('trim', $suggestions);
        }
    }
    
    return $defaultSuggestions;
}

/**
 * Clean the response by removing the suggestions section
 * 
 * @param string $response The AI response
 * @return string Cleaned response
 */
function cleanResponse($response) {
    // Remove numbered questions at the end
    $cleanedResponse = preg_replace('/(?:\n\n|\n)(?:Here are some follow-up questions:|Some questions you might have:|You might want to ask:|Follow-up questions:)(?:\n|\s)*(?:\d+\.\s*[^?]+\?\s*)+$/is', '', $response);
    
    // If the pattern didn't match, return the original response
    if ($cleanedResponse === $response) {
        // Try alternative pattern
        $cleanedResponse = preg_replace('/(?:\n\n|\n)(?:questions:|follow-up:|you might ask:)\s*(?:[^?]+\?\s*){1,3}$/is', '', $response);
    }
    
    return trim($cleanedResponse);
}

/**
 * Get fallback response when API call fails
 * 
 * @param string $question The user's question
 * @return array Fallback response with text and suggestions
 */
function getFallbackResponse($question) {
    // Convert question to lowercase for easier matching
    $question_lower = strtolower($question);
    
    // Default response
    $response = [
        'text' => "I'm here to help with questions about majors, careers, and educational opportunities. What would you like to know?",
        'suggestions' => [
            "Tell me about popular majors",
            "What careers have good prospects?",
            "How do I choose the right major?"
        ]
    ];
    
    // Check for keywords in the question
    if (strpos($question_lower, 'major') !== false || strpos($question_lower, 'study') !== false) {
        $response['text'] = "Some popular majors students are interested in include Computer Science, Business Administration, Engineering, Psychology, and Education. Each offers different career paths and opportunities. What specific field are you interested in?";
        $response['suggestions'] = [
            "Tell me about Computer Science",
            "What can I do with a Business degree?",
            "Which majors have the best job prospects?"
        ];
    } 
    else if (strpos($question_lower, 'job') !== false || strpos($question_lower, 'career') !== false) {
        $response['text'] = "Some careers with good job prospects include Software Developer, Data Analyst, Healthcare Professional, and Financial Analyst. The best career for you depends on your interests, skills, and values. Would you like information about a specific career field?";
        $response['suggestions'] = [
            "Highest paying careers",
            "Careers in technology",
            "Jobs that don't require a degree"
        ];
    }
    else if (strpos($question_lower, 'choose') !== false || strpos($question_lower, 'decide') !== false || strpos($question_lower, 'help') !== false) {
        $response['text'] = "Choosing a major can be challenging! Consider your interests, strengths, career goals, and the job market. It's also helpful to talk with professionals in fields you're interested in and explore through internships or coursework. Our Career Counselling service can provide personalized guidance.";
        $response['suggestions'] = [
            "How to discover my strengths",
            "Should I choose passion or practicality?",
            "Can I change my major later?"
        ];
    }
    
    return $response;
}
?>
