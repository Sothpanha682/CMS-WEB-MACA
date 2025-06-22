// Initialize CKEditor for rich text editors
document.addEventListener("DOMContentLoaded", () => {
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
