<?php
// Prevent direct access
if (!defined('INCLUDED')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}
?>

<!-- AI Assistant Widget -->
<div id="ai-assistant-widget" class="ai-assistant-closed">
    <div id="ai-assistant-button">
        <i class="fas fa-robot"></i>
    </div>
    <div id="ai-assistant-container">
        <div id="ai-assistant-header">
            <h5>MACA Career Assistant</h5>
            <button id="ai-assistant-close" class="btn btn-sm">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="ai-assistant-messages">
            <div class="ai-message">
                <div class="ai-message-content">
                    <p>👋 Hi there! I'm your MACA Career Assistant. I can help you explore majors and career paths. What would you like to know about?</p>
                    <div class="ai-suggestion-chips">
                        <button class="ai-suggestion-chip" data-query="What majors are popular?">Popular majors</button>
                        <button class="ai-suggestion-chip" data-query="What careers have good job prospects?">Job prospects</button>
                        <button class="ai-suggestion-chip" data-query="How do I choose a major?">Choosing a major</button>
                        <button class="ai-suggestion-chip" data-query="Tell me about Computer Science">Computer Science</button>
                        <button class="ai-suggestion-chip" data-query="What are the highest paying jobs?">Highest paying jobs</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="ai-assistant-input">
            <form id="ai-assistant-form">
                <div class="input-group">
                    <input type="text" id="ai-assistant-question" class="form-control" placeholder="Ask about majors or careers...">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add AI Assistant CSS -->
<style>
#ai-assistant-widget {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    font-family: 'Roboto', sans-serif;
}

#ai-assistant-button {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background-color: #dc3545;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
}

#ai-assistant-button i {
    font-size: 24px;
}

#ai-assistant-button:hover {
    transform: scale(1.05);
    background-color: #c82333;
}

#ai-assistant-container {
    position: absolute;
    bottom: 80px;
    right: 0;
    width: 350px;
    height: 500px;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transition: all 0.3s ease;
    opacity: 0;
    transform: translateY(20px);
    pointer-events: none;
}

.ai-assistant-open #ai-assistant-container {
    opacity: 1;
    transform: translateY(0);
    pointer-events: all;
}

#ai-assistant-header {
    padding: 15px;
    background-color: #dc3545;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

#ai-assistant-header h5 {
    margin: 0;
    font-weight: 600;
}

#ai-assistant-close {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    padding: 0;
}

#ai-assistant-messages {
    flex: 1;
    overflow-y: auto;
    padding: 15px;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.ai-message, .user-message {
    max-width: 80%;
    padding: 10px 15px;
    border-radius: 15px;
    margin-bottom: 5px;
}

.ai-message {
    align-self: flex-start;
    background-color: #f8f9fa;
    border-bottom-left-radius: 5px;
}

.user-message {
    align-self: flex-end;
    background-color: #dc3545;
    color: white;
    border-bottom-right-radius: 5px;
}

.ai-message-content p, .user-message-content p {
    margin: 0;
}

.ai-suggestion-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin-top: 10px;
}

.ai-suggestion-chip {
    background-color: #e9ecef;
    border: none;
    border-radius: 15px;
    padding: 5px 10px;
    font-size: 0.8rem;
    cursor: pointer;
    transition: background-color 0.2s;
}

.ai-suggestion-chip:hover {
    background-color: #dee2e6;
}

#ai-assistant-input {
    padding: 15px;
    border-top: 1px solid #e9ecef;
}

#ai-assistant-question {
    border-radius: 20px 0 0 20px;
}

#ai-assistant-form .btn {
    border-radius: 0 20px 20px 0;
}

.ai-typing {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 10px 15px;
    background-color: #f8f9fa;
    border-radius: 15px;
    align-self: flex-start;
    margin-bottom: 5px;
}

.ai-typing-dot {
    width: 8px;
    height: 8px;
    background-color: #adb5bd;
    border-radius: 50%;
    animation: typing-animation 1.4s infinite ease-in-out both;
}

.ai-typing-dot:nth-child(1) {
    animation-delay: -0.32s;
}

.ai-typing-dot:nth-child(2) {
    animation-delay: -0.16s;
}

@keyframes typing-animation {
    0%, 80%, 100% { transform: scale(0); }
    40% { transform: scale(1); }
}

@media (max-width: 576px) {
    #ai-assistant-container {
        width: 300px;
        height: 450px;
        bottom: 70px;
    }
    
    #ai-assistant-button {
        width: 50px;
        height: 50px;
    }
    
    #ai-assistant-button i {
        font-size: 20px;
    }
}
</style>
