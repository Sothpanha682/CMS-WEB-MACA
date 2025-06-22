# MACA CMS

## Project Overview

MACA CMS is a comprehensive Content Management System designed to manage various types of content, user interactions, and potentially AI-driven functionalities. This project appears to be a hybrid web application, combining PHP for backend operations and Next.js/React for the frontend, offering a robust and flexible platform for content administration.

## Features

- **User Management:** Tools for managing users and administrative access.
- **Content Management:**
  - News articles
  - Announcements
  - Talkshows
  - Roadshows
  - Popular Jobs, Majors, and Careers listings
  - Slider images and content
- **Site Settings:** Configuration and management of global website settings.
- **Media Management:** Handling and organization of media files.
- **AI Integration:** Features leveraging AI for enhanced content or user experience.
- **Dashboard:** An administrative interface for an overview and quick access to key functionalities.
- **Database Management:** Structured database for content storage and retrieval.
- **Frontend Components:** Modern, interactive user interface built with React/Next.js.

## Technologies Used

- **Backend:** PHP
- **Database:** MySQL / MariaDB (inferred from `.sql` files)
- **Frontend Framework:** React, Next.js
- **Styling:** Tailwind CSS, CSS
- **Scripting:** JavaScript, TypeScript
- **Package Manager:** pnpm

## Setup Instructions

To set up this project locally, follow these general steps:

1.  **Clone the repository:**
    ```bash
    git clone [repository-url]
    cd MACA
    ```
2.  **Database Setup:**
    - Import the `maca_cms_database.sql` file into your MySQL/MariaDB database.
    - Configure database connection settings in `config/database.php`.
3.  **PHP Environment:**
    - Ensure you have a PHP environment (e.g., Apache, Nginx with PHP-FPM, Laragon, XAMPP) configured to serve the PHP files.
4.  **Frontend Setup:**
    - Navigate to the project root.
    - Install frontend dependencies:
      ```bash
      pnpm install
      ```
    - Build the Next.js application (if applicable for production deployment):
      ```bash
      pnpm build
      ```
    - Start the development server:
      ```bash
      pnpm dev
      ```
5.  **Access the Application:**
    - Open your web browser and navigate to the configured URL for the PHP application (e.g., `http://localhost/MACA`).
    - The Next.js frontend might run on a different port (e.g., `http://localhost:3000`) and interact with the PHP backend via API calls.

## Usage

- **Admin Panel:** Access the administrative dashboard via `pages/login.php` (or similar) to manage content and settings.
- **Public Site:** The public-facing website can be accessed through `index.php` and various `pages/` files.

## Contributing

Contributions are welcome! Please follow standard Git workflow: fork the repository, create a new branch for your features or bug fixes, and submit a pull request.

## License

This project is licensed under the MIT License.
