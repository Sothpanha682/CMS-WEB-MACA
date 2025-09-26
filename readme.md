# CI/CD Setup for AsiaRegistry Hosting

This document outlines the steps to set up Continuous Integration/Continuous Deployment (CI/CD) for your project on AsiaRegistry hosting using a Git webhook and a `deploy.php` script. This setup will automatically update your site whenever you push changes to your GitHub repository.

## Prerequisites

*   **SSH Access:** You have SSH access to your AsiaRegistry hosting, even if there's no direct terminal access. This is required for the `deploy.php` script to execute `git pull`.
*   **Git Repository:** Your project is hosted on GitHub.
*   **`deploy.php` script:** The `deploy.php` file has been added to your project's root directory.

## Step 1: Configure `deploy.php`

1.  **Open `deploy.php`** in your project's root directory.
2.  **Change the secret key:** Locate the line `$secret = 'YOUR_SECRET_KEY';` and replace `'YOUR_SECRET_KEY'` with a strong, random string. This secret key will be used to verify that the webhook requests are legitimate.
    ```php
    $secret = 'YOUR_VERY_STRONG_AND_RANDOM_SECRET_KEY_HERE';
    ```
3.  **Ensure correct branch:** Verify that the `$branch` variable in `deploy.php` matches the branch you want to deploy from (e.g., `main`, `master`).
    ```php
    $branch = 'main'; // Or 'master', or your deployment branch
    ```
4.  **Upload `deploy.php`:** Ensure this modified `deploy.php` file is uploaded to the root directory of your website on AsiaRegistry hosting.

## Step 2: Set up GitHub Webhook

1.  **Go to your GitHub repository:** Navigate to your project's repository on GitHub (e.g., `https://github.com/your-username/your-repo-name`).
2.  **Access Settings:** Click on the "Settings" tab.
3.  **Navigate to Webhooks:** In the left sidebar, click on "Webhooks."
4.  **Add webhook:** Click the "Add webhook" button.
5.  **Configure the webhook:**
    *   **Payload URL:** Enter the full URL to your `deploy.php` script on your AsiaRegistry hosting. For example: `http://your-domain.com/deploy.php` or `https://your-domain.com/deploy.php`.
    *   **Content type:** Select `application/x-www-form-urlencoded`.
    *   **Secret:** Paste the exact secret key you set in your `deploy.php` file (from Step 1.2).
    *   **Which events would you like to trigger this webhook?** Select "Just the push event."
    *   **Active:** Ensure "Active" is checked.
6.  **Add webhook:** Click the "Add webhook" button at the bottom.

## Step 3: Initial Deployment (Manual)

Before the webhook can automatically pull changes, your hosting environment needs to be a Git repository.

1.  **Connect via SSH:** Use an SSH client (like PuTTY on Windows, or the `ssh` command on Linux/macOS) to connect to your AsiaRegistry hosting.
    *   You will need your SSH username, host, and password/key.
    *   Example command: `ssh your_username@your_hosting_ip_or_domain`
2.  **Navigate to your website's root directory:**
    ```bash
    cd /path/to/your/website/root
    ```
    (Replace `/path/to/your/website/root` with the actual path on your server, e.g., `public_html` or `www`).
3.  **Initialize Git and pull your repository:**
    ```bash
    git init
    git remote add origin https://github.com/Sothpanha682/CMS-WEB-MACA.git # Replace with your actual repository URL
    git pull origin main # Replace 'main' with your deployment branch
    ```
    This step is crucial to make your server directory a Git repository that can be updated by `git pull`.

## Step 4: Test the CI/CD Setup

1.  Make a small change to a file in your local project.
2.  Commit the change and push it to your GitHub repository's deployment branch (e.g., `main`).
    ```bash
    git add .
    git commit -m "Test CI/CD setup"
    git push origin main
    ```
3.  After a few moments, visit your website. The changes should be live.
4.  You can also check the `deploy.log` file (if it's created and accessible on your server) for deployment messages. On GitHub, you can check the "Recent Deliveries" section under your webhook settings to see if the webhook was triggered successfully.

## Troubleshooting

*   **`deploy.log` not created/updated:** Check file permissions on your server. The web server user needs write access to the directory where `deploy.php` and `deploy.log` are located.
*   **`git pull` fails:** Ensure the Git repository on your server is correctly initialized and has the correct remote (`origin`). Also, check SSH keys if your repository is private and requires authentication.
*   **Webhook not triggering:** Double-check the Payload URL and Secret in your GitHub webhook settings. Ensure the URL is publicly accessible.
*   **"No X-Hub-Signature found" or "X-Hub-Signature does not match":** This indicates an issue with the secret key or content type. Ensure the secret in GitHub matches `deploy.php` exactly, and the content type is `application/x-www-form-urlencoded`.
