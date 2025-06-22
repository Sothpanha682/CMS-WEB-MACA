/**
 * MACA AI Assistant
 * Provides an interactive chat interface for students to ask questions about majors and careers
 */
document.addEventListener("DOMContentLoaded", () => {
  // Get DOM elements
  const assistantWidget = document.getElementById("ai-assistant-widget")
  const assistantButton = document.getElementById("ai-assistant-button")
  const assistantClose = document.getElementById("ai-assistant-close")
  const assistantForm = document.getElementById("ai-assistant-form")
  const assistantQuestion = document.getElementById("ai-assistant-question")
  const assistantMessages = document.getElementById("ai-assistant-messages")

  // Toggle assistant visibility
  assistantButton.addEventListener("click", () => {
    assistantWidget.classList.toggle("ai-assistant-open")
    assistantWidget.classList.toggle("ai-assistant-closed")

    // Focus on input when opened
    if (assistantWidget.classList.contains("ai-assistant-open")) {
      assistantQuestion.focus()
    }
  })

  // Close assistant
  assistantClose.addEventListener("click", () => {
    assistantWidget.classList.remove("ai-assistant-open")
    assistantWidget.classList.add("ai-assistant-closed")
  })

  // Handle form submission
  assistantForm.addEventListener("submit", (e) => {
    e.preventDefault()

    const question = assistantQuestion.value.trim()
    if (question === "") return

    // Add user message to chat
    addMessage(question, "user")

    // Clear input
    assistantQuestion.value = ""

    // Show typing indicator
    showTypingIndicator()

    // Send question to API
    sendQuestion(question)
  })

  // Handle suggestion chips
  document.addEventListener("click", (e) => {
    if (e.target.classList.contains("ai-suggestion-chip")) {
      const question = e.target.getAttribute("data-query")

      // Add user message to chat
      addMessage(question, "user")

      // Show typing indicator
      showTypingIndicator()

      // Send question to API
      sendQuestion(question)
    }
  })

  /**
   * Add a message to the chat
   *
   * @param {string} message - The message text
   * @param {string} sender - 'user' or 'ai'
   */
  function addMessage(message, sender) {
    const messageDiv = document.createElement("div")
    messageDiv.className = sender === "user" ? "user-message" : "ai-message"

    const messageContent = document.createElement("div")
    messageContent.className = sender === "user" ? "user-message-content" : "ai-message-content"

    const messageParagraph = document.createElement("p")
    messageParagraph.textContent = message

    messageContent.appendChild(messageParagraph)
    messageDiv.appendChild(messageContent)
    assistantMessages.appendChild(messageDiv)

    // Scroll to bottom
    assistantMessages.scrollTop = assistantMessages.scrollHeight
  }

  /**
   * Add an AI message with HTML content
   *
   * @param {string} html - The HTML content
   * @param {Array} suggestions - Optional array of suggestion chips
   */
  function addAIMessageWithHTML(html, suggestions = []) {
    const messageDiv = document.createElement("div")
    messageDiv.className = "ai-message"

    const messageContent = document.createElement("div")
    messageContent.className = "ai-message-content"

    // Convert plain text to HTML with paragraphs and links
    const formattedHtml = formatMessageText(html)
    messageContent.innerHTML = formattedHtml

    // Add suggestion chips if provided
    if (suggestions && suggestions.length > 0) {
      const suggestionsDiv = document.createElement("div")
      suggestionsDiv.className = "ai-suggestion-chips"

      suggestions.forEach((suggestion) => {
        const chip = document.createElement("button")
        chip.className = "ai-suggestion-chip"
        chip.setAttribute("data-query", suggestion)
        chip.textContent = suggestion
        suggestionsDiv.appendChild(chip)
      })

      messageContent.appendChild(suggestionsDiv)
    }

    messageDiv.appendChild(messageContent)
    assistantMessages.appendChild(messageDiv)

    // Scroll to bottom
    assistantMessages.scrollTop = assistantMessages.scrollHeight
  }

  /**
   * Format message text with paragraphs and links
   *
   * @param {string} text - The message text
   * @return {string} Formatted HTML
   */
  function formatMessageText(text) {
    // Convert URLs to clickable links
    const urlRegex = /(https?:\/\/[^\s]+)/g
    let formattedText = text.replace(urlRegex, '<a href="$1" target="_blank">$1</a>')

    // Convert internal links
    const internalLinkRegex = /<a href=['"]index\.php\?page=([^'"]+)['"]>([^<]+)<\/a>/g
    formattedText = formattedText.replace(internalLinkRegex, '<a href="index.php?page=$1">$2</a>')

    // Convert line breaks to paragraphs
    const paragraphs = formattedText.split(/\n\n+/)
    if (paragraphs.length > 1) {
      formattedText = paragraphs.map((p) => `<p>${p.replace(/\n/g, "<br>")}</p>`).join("")
    } else {
      formattedText = `<p>${formattedText.replace(/\n/g, "<br>")}</p>`
    }

    return formattedText
  }

  /**
   * Show typing indicator
   */
  function showTypingIndicator() {
    const typingDiv = document.createElement("div")
    typingDiv.className = "ai-typing"
    typingDiv.id = "ai-typing-indicator"

    for (let i = 0; i < 3; i++) {
      const dot = document.createElement("div")
      dot.className = "ai-typing-dot"
      typingDiv.appendChild(dot)
    }

    assistantMessages.appendChild(typingDiv)
    assistantMessages.scrollTop = assistantMessages.scrollHeight
  }

  /**
   * Hide typing indicator
   */
  function hideTypingIndicator() {
    const typingIndicator = document.getElementById("ai-typing-indicator")
    if (typingIndicator) {
      typingIndicator.remove()
    }
  }

  /**
   * Send question to API
   *
   * @param {string} question - The user's question
   */
  function sendQuestion(question) {
    // Add debug log
    console.log("Sending question to API:", question)

    // Show typing indicator
    showTypingIndicator()

    // Use the updated API endpoint
    fetch("api/assistant-updated.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ question: question }),
    })
      .then((response) => {
        console.log("API response status:", response.status)

        // Check if response is OK
        if (!response.ok) {
          throw new Error("Network response was not ok: " + response.status)
        }

        // Check content type to ensure it's JSON
        const contentType = response.headers.get("content-type")
        if (!contentType || !contentType.includes("application/json")) {
          throw new Error("Response is not JSON. Received: " + contentType)
        }

        return response.json()
      })
      .then((data) => {
        console.log("API response data:", data)

        // Hide typing indicator
        hideTypingIndicator()

        // Add AI response with suggestions if available
        if (data && data.response) {
          addAIMessageWithHTML(data.response, data.suggestions || [])
        } else {
          throw new Error("Invalid response format")
        }
      })
      .catch((error) => {
        console.error("Error:", error)

        // Hide typing indicator
        hideTypingIndicator()

        // Add error message
        addAIMessageWithHTML("I'm sorry, I'm having trouble right now. Let me try a simpler response instead.")

        // Provide a fallback response directly from JavaScript
        setTimeout(() => {
          const fallbackResponse =
            "I can help with questions about majors, careers, and educational opportunities. You can ask about popular majors, job prospects, or how to choose a career path that's right for you."

          const fallbackSuggestions = [
            "Tell me about popular majors",
            "What careers have good prospects?",
            "How do I choose the right major?",
          ]

          addAIMessageWithHTML(fallbackResponse, fallbackSuggestions)
        }, 1000)
      })
  }
})
