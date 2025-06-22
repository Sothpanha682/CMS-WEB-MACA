<?php
/**
 * AI Integration API
 * 
 * This file provides integration with external AI APIs like DeepSeek
 * It handles authentication, request formatting, and response parsing
 */

// Prevent direct access
if (!defined('INCLUDED')) {
    define('INCLUDED', true);
}

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
    public function __construct($provider = 'deepseek', $apiKey = null, $model = null) {
        $this->provider = strtolower($provider);
        // Always use the hardcoded DeepSeek API key if no key is provided
        $this->apiKey = $apiKey ?: getenv('AI_API_KEY') ?: 'sk-6e3d09fe05594cb88181f9cb89b457d6';
        
        // Set defaults based on provider
        switch ($this->provider) {
            case 'openai':
                $this->apiEndpoint = 'https://api.openai.com/v1/chat/completions';
                $this->model = $model ?: 'gpt-3.5-turbo';
                break;
            case 'azure':
                $this->apiEndpoint = getenv('AZURE_OPENAI_ENDPOINT');
                $this->model = $model ?: 'gpt-35-turbo';
                break;
            case 'anthropic':
                $this->apiEndpoint = 'https://api.anthropic.com/v1/messages';
                $this->model = $model ?: 'claude-2';
                break;
            case 'deepseek':
            default:
                // Default to DeepSeek even if an unsupported provider is specified
                $this->provider = 'deepseek';
                $this->apiEndpoint = 'https://api.deepseek.com/v1/chat/completions';
                $this->model = $model ?: 'deepseek-chat';
                break;
        }
        
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
        $systemPrompt = "You are MACA's educational assistant, an expert in academic and career guidance. " .
                        "Your purpose is to help students make informed decisions about their educational and career paths. " .
                        "Provide detailed, accurate, and helpful information about majors, careers, job prospects, and educational opportunities. " .
                        "When appropriate, include links to relevant pages on the MACA website using the format <a href='index.php?page=PAGE_NAME'>Link Text</a>. " .
                        "Common pages include: popular-majors, popular-jobs, program/talkshow, program/roadshow, program/online-learning, contact, about. " .
                        "Always be encouraging and supportive of students' educational journeys. " .
                        "Format your responses with HTML paragraphs (<p>) and lists (<ul>, <li>) when appropriate for better readability. " .
                        "Provide unique, detailed responses for each question, even if they seem similar to previous questions. " .
                        "For questions about specific majors or careers, include information about required education, skills, job prospects, and salary ranges when relevant.";
        
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
        
        switch ($this->provider) {
            case 'openai':
                return [
                    'model' => $this->model,
                    'messages' => array_merge([['role' => 'system', 'content' => $systemPrompt]], $messages),
                    'max_tokens' => $this->maxTokens,
                    'temperature' => $this->temperature
                ];
                
            case 'azure':
                return [
                    'messages' => array_merge([['role' => 'system', 'content' => $systemPrompt]], $messages),
                    'max_tokens' => $this->maxTokens,
                    'temperature' => $this->temperature
                ];
                
            case 'anthropic':
                return [
                    'model' => $this->model,
                    'messages' => $messages,
                    'system' => $systemPrompt,
                    'max_tokens' => $this->maxTokens,
                    'temperature' => $this->temperature
                ];
            case 'deepseek':
            default:
                return [
                    'model' => $this->model,
                    'messages' => array_merge([['role' => 'system', 'content' => $systemPrompt]], $messages),
                    'max_tokens' => $this->maxTokens,
                    'temperature' => $this->temperature,
                    'top_p' => 0.9, // Added for more focused responses
                    'presence_penalty' => 0.1, // Slight penalty to avoid repetition
                    'frequency_penalty' => 0.1 // Slight penalty to avoid repetition
                ];
        }
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
            case 'openai':
                $headers[] = "Authorization: Bearer {$this->apiKey}";
                break;
            case 'azure':
                $headers[] = "api-key: {$this->apiKey}";
                break;
            case 'anthropic':
                $headers[] = "x-api-key: {$this->apiKey}";
                $headers[] = "anthropic-version: 2023-06-01";
                break;
            case 'deepseek':
            default:
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
            throw new Exception("API request failed with HTTP code {$httpCode}: {$response}");
        }
        
        // Decode the response
        $decodedResponse = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
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
            case 'openai':
            case 'azure':
                if (isset($response['choices'][0]['message']['content'])) {
                    return $response['choices'][0]['message']['content'];
                }
                break;
                
            case 'anthropic':
                if (isset($response['content'][0]['text'])) {
                    return $response['content'][0]['text'];
                }
                break;
            case 'deepseek':
            default:
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
        return "<p>I apologize, but I'm currently having trouble accessing my knowledge base. Please try asking your question again in a moment.</p>
        <p>In the meantime, you might find helpful information on our website:</p>
        <ul>
            <li><a href='index.php?page=popular-majors'>Popular Majors</a></li>
            <li><a href='index.php?page=popular-jobs'>Popular Jobs</a></li>
            <li><a href='index.php?page=program/online-learning'>Online Learning Programs</a></li>
            <li><a href='index.php?page=contact'>Contact Us</a> for personalized assistance</li>
        </ul>";
    }
}
?>
