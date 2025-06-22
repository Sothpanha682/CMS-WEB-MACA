document.addEventListener("DOMContentLoaded", () => {
  // Auto dropdown for Program menu on hover (desktop only)
  const dropdownToggle = document.querySelector(".dropdown-toggle")
  const dropdownMenu = document.querySelector(".dropdown-menu")
  const dropdown = document.querySelector(".dropdown")

  if (window.innerWidth >= 992) {
    // Only on desktop
    dropdown.addEventListener("mouseenter", () => {
      dropdownMenu.classList.add("show")
      dropdownToggle.setAttribute("aria-expanded", "true")
    })

    dropdown.addEventListener("mouseleave", () => {
      dropdownMenu.classList.remove("show")
      dropdownToggle.setAttribute("aria-expanded", "false")
    })
  }

  // Add smooth scroll effect
  document.querySelectorAll('a[href^="index.php"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      // Add a small delay for visual effect
      if (!this.classList.contains("dropdown-toggle")) {
        const href = this.getAttribute("href")
        const currentPage = window.location.href.split("?")[0]

        if (href.includes(currentPage)) {
          e.preventDefault()

          // Add active class
          document.querySelectorAll(".nav-item").forEach((item) => {
            item.classList.remove("active")
          })
          this.parentElement.classList.add("active")

          // Redirect with a small delay for visual effect
          setTimeout(() => {
            window.location.href = href
          }, 200)
        }
      }
    })
  })
})
