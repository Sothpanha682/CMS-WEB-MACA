<?php
/**
 * AI Integration API
 * 
 * This file provides integration with external AI APIs
 * It handles authentication, request formatting, and response parsing
 */

// Prevent direct access
if (!defined('INCLUDED')) {
    define('INCLUDED', true);
}

// Include API keys
require_once __DIR__ . '/../config/api-keys.php';

class AIIntegration {
    private $apiKey;
    private $apiEndpoint;
    private $provider;
    private $model;
    private $maxTokens;
    private $temperature;
    
    /**
     * Constructor
     * 
     * @param string $provider The AI provider (deepseek, openai, etc.)
     * @param string $apiKey The API key for authentication
     * @param string $model The model to use (optional)
     */
    public function __construct($provider = 'gemini', $apiKey = null, $model = null) {
        $this->provider = strtolower($provider);
        
        // Load API key: prioritize provided key, then fallback to hardcoded Gemini key for development/testing
        $this->apiKey = $apiKey;
        if (empty($this->apiKey)) {
            $this->apiKey = 'AIzaSyBKSsNU9umvHRaaGuWGqCWCu5repYcTmg0'; // Placeholder/development Gemini API key
            error_log("Warning: Using hardcoded Gemini API key. Please configure your API key in admin/set-api-key.php.");
        }
        
        // Set defaults for Gemini
        $this->apiEndpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';
        $this->model = $model ?: 'gemini-pro';
        
        // Default parameters - optimized for educational responses
        $this->maxTokens = 1000; // Increased for more detailed responses
        $this->temperature = 0.7; // Balanced temperature for creativity and accuracy
    }
    
    /**
     * Set the maximum number of tokens to generate
     * 
     * @param int $maxTokens Maximum tokens
     * @return $this For method chaining
     */
    public function setMaxTokens($maxTokens) {
        $this->maxTokens = $maxTokens;
        return $this;
    }
    
    /**
     * Set the temperature for response generation
     * 
     * @param float $temperature Temperature (0.0 to 1.0)
     * @return $this For method chaining
     */
    public function setTemperature($temperature) {
        $this->temperature = $temperature;
        return $this;
    }
    
    /**
     * Generate a response to the user's question
     * 
     * @param string $question The user's question
     * @param array $context Additional context (optional)
     * @return string The AI-generated response
     * @throws Exception If the API request fails
     */
    public function generateResponse($question, $context = []) {
        // Check if API key is available
        if (empty($this->apiKey)) {
            throw new Exception('AI API key is not configured');
        }
        
        // Log the request for debugging
        error_log("Generating AI response for question: {$question}");
        
        // Format the request based on the provider
        $requestData = $this->formatRequest($question, $context);
        
        // Make the API request
        $response = $this->makeApiRequest($requestData);
        
        // Parse the response
        $parsedResponse = $this->parseResponse($response);
        
        // Format the response with HTML if needed
        return $this->formatResponseWithHTML($parsedResponse);
    }
    
    /**
     * Format the request data based on the provider
     * 
     * @param string $question The user's question
     * @param array $context Additional context
     * @return array The formatted request data
     */
    private function formatRequest($question, $context) {
        // Enhanced system prompt for better educational responses
        $systemPrompt = "You are MACA's highly intelligent and empathetic educational assistant, an expert in academic and career guidance. " .
                        "Your core purpose is to empower students to make well-informed, strategic decisions about their educational and career trajectories. " .
                        "Deliver comprehensive, precise, and actionable insights on a wide array of topics including university majors, career paths, job market trends, and diverse educational opportunities. " .
                        "Whenever relevant, seamlessly integrate internal links to specific MACA website pages using the format <a href='index.php?page=PAGE_NAME'>Link Text</a>. " .
                        "Prioritize links to: popular-majors, popular-jobs, program/talkshow, program/roadshow, program/online-learning, contact, and about. " .
                        "Maintain an encouraging, supportive, and inspiring tone throughout all interactions, fostering confidence in students' educational journeys. " .
                        "Structure your responses meticulously with HTML paragraphs (<p>), ordered lists (<ol>, <li>), and unordered lists (<ul>, <li>) for optimal readability and clarity. " .
                        "Generate distinct, deeply analytical, and highly detailed responses for every query, even if the subject matter appears similar to previous questions. " .
                        "For inquiries concerning specific majors or careers, provide exhaustive information encompassing required educational qualifications, essential skills, future job prospects, and realistic salary ranges, citing credible sources where applicable. " .
                        "Strive to anticipate follow-up questions and provide a holistic perspective, guiding users towards deeper understanding and practical next steps.";
        
        // Get conversation history if available
        $messages = [];
        if (!empty($context['history']) && is_array($context['history'])) {
            foreach ($context['history'] as $item) {
                if ($item['sender'] === 'user') {
                    $messages[] = ['role' => 'user', 'content' => $item['message']];
                } else {
                    $messages[] = ['role' => 'assistant', 'content' => $item['message']];
                }
            }
        }
        
        // Add the current question
        $messages[] = ['role' => 'user', 'content' => $question];
        
        // Gemini uses 'contents' and 'parts' for messages
        $formattedMessages = [];
        foreach ($messages as $msg) {
            $formattedMessages[] = [
                'role' => ($msg['role'] === 'system' || $msg['role'] === 'assistant') ? 'model' : 'user',
                'parts' => [['text' => $msg['content']]]
            ];
        }
        // Add system prompt as a user message at the beginning for Gemini
        array_unshift($formattedMessages, ['role' => 'user', 'parts' => [['text' => $systemPrompt]]]);

        return [
            'contents' => $formattedMessages,
            'generationConfig' => [
                'maxOutputTokens' => $this->maxTokens,
                'temperature' => $this->temperature,
                'topP' => 0.9,
            ],
        ];
    }
    
    /**
     * Make the API request
     * 
     * @param array $requestData The formatted request data
     * @return array The API response
     * @throws Exception If the API request fails
     */
    private function makeApiRequest($requestData) {
        // Initialize cURL
        $ch = curl_init($this->apiEndpoint);
        
        // Set request headers
        $headers = ['Content-Type: application/json'];
        
        // Add provider-specific headers
        switch ($this->provider) {
            case 'gemini':
                $headers[] = "x-goog-api-key: {$this->apiKey}";
                break;
            default:
                // This case should ideally not be reached if provider is always 'gemini'
                $headers[] = "Authorization: Bearer {$this->apiKey}";
                break;
        }
        
        // Log the request for debugging
        error_log("Making API request to: {$this->apiEndpoint}");
        error_log("Request data: " . json_encode($requestData));
        
        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Increased timeout for more reliable responses
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // Connection timeout
        
        // Execute the request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        // Check for errors
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception("API request failed: {$error}");
        }
        
        curl_close($ch);
        
        // Log the response for debugging
        error_log("API response HTTP code: {$httpCode}");
        error_log("API response: " . substr($response, 0, 500) . "..."); // Log first 500 chars
        
        // Check HTTP status code
        if ($httpCode < 200 || $httpCode >= 300) {
            error_log("API request failed with HTTP code {$httpCode}: {$response}"); // Log full response on error
            throw new Exception("API request failed with HTTP code {$httpCode}: {$response}");
        }
        
        // Decode the response
        $decodedResponse = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("Failed to decode API response: " . json_last_error_msg() . " Raw response: " . $response); // Log raw response if JSON decode fails
            throw new Exception("Failed to decode API response: " . json_last_error_msg());
        }
        
        return $decodedResponse;
    }
    
    /**
     * Parse the API response
     * 
     * @param array $response The API response
     * @return string The extracted text response
     * @throws Exception If the response cannot be parsed
     */
    private function parseResponse($response) {
        switch ($this->provider) {
            case 'gemini':
                if (isset($response['candidates'][0]['content']['parts'][0]['text'])) {
                    return $response['candidates'][0]['content']['parts'][0]['text'];
                }
                // Check for Gemini specific error messages
                if (isset($response['error']['message'])) {
                    error_log("Gemini API error: " . $response['error']['message']);
                    throw new Exception("Gemini API error: " . $response['error']['message']);
                }
                break;
            default:
                // This case should ideally not be reached if provider is always 'gemini'
                if (isset($response['choices'][0]['message']['content'])) {
                    return $response['choices'][0]['message']['content'];
                }
                break;
        }
        
        // If we couldn't parse the response
        error_log("Failed to parse AI response: " . json_encode($response));
        throw new Exception("Failed to parse AI response");
    }
    
    /**
     * Format the response with HTML if needed
     * 
     * @param string $response The raw response text
     * @return string The formatted response with HTML
     */
    private function formatResponseWithHTML($response) {
        // If the response already has HTML formatting, return it as is
        if (strpos($response, '<p>') !== false || strpos($response, '<ul>') !== false) {
            return $response;
        }
        
        // Otherwise, add basic HTML formatting
        $formatted = '';
        $paragraphs = explode("\n\n", $response);
        
        foreach ($paragraphs as $paragraph) {
            if (trim($paragraph) !== '') {
                // Check if this is a list (lines starting with - or *)
                if (preg_match('/^[\s]*[-*][\s]/', $paragraph)) {
                    $formatted .= "<ul>";
                    $lines = explode("\n", $paragraph);
                    foreach ($lines as $line) {
                        if (preg_match('/^[\s]*[-*][\s](.+)$/', $line, $matches)) {
                            $formatted .= "<li>" . $matches[1] . "</li>";
                        }
                    }
                    $formatted .= "</ul>";
                } else {
                    // Regular paragraph
                    $formatted .= "<p>" . str_replace("\n", "<br>", $paragraph) . "</p>";
                }
            }
        }
        
        return $formatted;
    }
    
    /**
     * Get a fallback response when the API is unavailable
     * 
     * @param string $question The user's question
     * @return string A fallback response
     */
    public function getFallbackResponse($question) {
        return "<p>I apologize, but I'm currently experiencing technical difficulties and cannot access my full knowledge base. Please try asking your question again in a moment.</p>
        <p>In the meantime, you might find helpful information on our website:</p>
        <ul>
            <li><a href='index.php?page=popular-majors'>Popular Majors</a></li>
            <li><a href='index.php?page=popular-jobs'>Popular Jobs</a></li>
            <li><a href='index.php?page=program/online-learning'>Online Learning Programs</a></li>
            <li><a href='index.php?page=contact'>Contact Us</a> for personalized assistance</li>
        </ul>";
    }
}

// Handle incoming API requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    $question = $data['question'] ?? '';
    $history = $data['history'] ?? [];
    $provider = $data['provider'] ?? 'gemini'; // Default to gemini if not specified
    $apiKey = $data['apiKey'] ?? null; // Pass API key from frontend if available

    if (empty($question)) {
        echo json_encode(['error' => 'No question provided.']);
        exit;
    }

    try {
        $aiIntegration = new AIIntegration($provider, $apiKey);
        $response = $aiIntegration->generateResponse($question, ['history' => $history]);
        echo json_encode(['response' => $response]);
    } catch (Exception $e) {
        error_log("AI Integration Error: " . $e->getMessage());
        $aiIntegration = new AIIntegration($provider, $apiKey); // Re-instantiate to get fallback
        echo json_encode(['error' => $e->getMessage(), 'response' => $aiIntegration->getFallbackResponse($question)]);
    }
    exit;
}
?>
