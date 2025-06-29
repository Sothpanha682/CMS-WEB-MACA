// Initialize CKEditor for rich text editors
document.addEventListener("DOMContentLoaded", () => {
  console.log('script.js loaded.');
  if (typeof bootstrap !== 'undefined') {
    console.log('Bootstrap is loaded.');
  } else {
    console.log('Bootstrap is NOT loaded.');
  }

  // Initialize CKEditor if rich-editor elements exist
  if (document.querySelector(".rich-editor")) {
    // CKEditor is initialized in each page that needs it
  }

  // Bootstrap tooltips initialization
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  const tooltipList = tooltipTriggerList.map((tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl))

  // Copy URL functionality for media library
  document.querySelectorAll(".copy-url").forEach((button) => {
    button.addEventListener("click", function () {
      const url = this.getAttribute("data-url")
      navigator.clipboard.writeText(url).then(() => {
        // Change button text temporarily
        const originalText = this.textContent
        this.textContent = "Copied!"
        setTimeout(() => {
          this.textContent = originalText
        }, 2000)
      })
    })
  })

  // AI Assistant functionality
  const aiAssistantWidget = document.getElementById("ai-assistant-widget")
  const aiAssistantButton = document.getElementById("ai-assistant-button")
  const aiAssistantCloseButton = document.getElementById("ai-assistant-close")
  const aiAssistantForm = document.getElementById("ai-assistant-form")
  const aiAssistantQuestionInput = document.getElementById("ai-assistant-question")
  const aiAssistantMessages = document.getElementById("ai-assistant-messages")
  const aiSuggestionChips = document.querySelectorAll(".ai-suggestion-chip")

  let conversationHistory = []

  // Toggle AI Assistant visibility
  aiAssistantButton.addEventListener("click", () => {
    aiAssistantWidget.classList.toggle("ai-assistant-open")
    if (aiAssistantWidget.classList.contains("ai-assistant-open")) {
      aiAssistantQuestionInput.focus()
      scrollToBottom()
    }
  })

  aiAssistantCloseButton.addEventListener("click", () => {
    aiAssistantWidget.classList.remove("ai-assistant-open")
  })

  // Handle suggestion chip clicks
  aiSuggestionChips.forEach((chip) => {
    chip.addEventListener("click", function () {
      const query = this.getAttribute("data-query")
      aiAssistantQuestionInput.value = query
      aiAssistantForm.dispatchEvent(new Event("submit")) // Programmatically submit the form
    })
  })

  // Handle AI Assistant form submission
  aiAssistantForm.addEventListener("submit", async (e) => {
    e.preventDefault()
    const question = aiAssistantQuestionInput.value.trim()
    if (!question) return

    addUserMessage(question)
    aiAssistantQuestionInput.value = ""
    scrollToBottom()

    showTypingIndicator()

    try {
      const response = await fetch("api/ai-integration.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          question: question,
          history: conversationHistory,
          provider: "gemini", // Specify Gemini provider
        }),
      })

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      const data = await response.json()
      removeTypingIndicator()

      if (data.response) {
        addAiMessage(data.response)
        conversationHistory.push({ sender: "user", message: question })
        conversationHistory.push({ sender: "assistant", message: data.response })
      } else if (data.error) {
        addAiMessage(`Error: ${data.error}`)
      } else {
        addAiMessage("Sorry, I couldn't get a response from the AI.")
      }
    } catch (error) {
      console.error("AI Assistant error:", error)
      removeTypingIndicator()
      addAiMessage(
        "I apologize, but I'm currently having trouble connecting to the AI. Please try again later."
      )
    } finally {
      scrollToBottom()
    }
  })

  // Add user message to chat
  function addUserMessage(message) {
    const messageDiv = document.createElement("div")
    messageDiv.classList.add("user-message")
    messageDiv.innerHTML = `<div class="user-message-content"><p>${message}</p></div>`
    aiAssistantMessages.appendChild(messageDiv)
  }

  // Add AI message to chat
  function addAiMessage(message) {
    const messageDiv = document.createElement("div")
    messageDiv.classList.add("ai-message")
    messageDiv.innerHTML = `<div class="ai-message-content">${message}</div>`
    aiAssistantMessages.appendChild(messageDiv)
  }

  // Show typing indicator
  function showTypingIndicator() {
    const typingDiv = document.createElement("div")
    typingDiv.classList.add("ai-typing")
    typingDiv.innerHTML = `
            <div class="ai-typing-dot"></div>
            <div class="ai-typing-dot"></div>
            <div class="ai-typing-dot"></div>
        `
    aiAssistantMessages.appendChild(typingDiv)
    aiAssistantMessages.scrollTop = aiAssistantMessages.scrollHeight
  }

  // Remove typing indicator
  function removeTypingIndicator() {
    const typingDiv = aiAssistantMessages.querySelector(".ai-typing")
    if (typingDiv) {
      typingDiv.remove()
    }
  }

  // Scroll messages to bottom
  function scrollToBottom() {
    aiAssistantMessages.scrollTop = aiAssistantMessages.scrollHeight
  }

  // Preview image before upload
  const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]')
  imageInputs.forEach((input) => {
    input.addEventListener("change", function () {
      const file = this.files[0]
      if (file) {
        const reader = new FileReader()
        const previewId = this.getAttribute("data-preview")
        if (previewId) {
          const preview = document.getElementById(previewId)
          reader.onload = (e) => {
            preview.src = e.target.result
            preview.style.display = "block"
          }
          reader.readAsDataURL(file)
        }
      }
    })
  })
})
