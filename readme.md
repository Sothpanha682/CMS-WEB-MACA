# CI/CD Setup for AsiaRegistry Hosting

This document outlines the steps to set up Continuous Integration/Continuous Deployment (CI/CD) for your project on AsiaRegistry hosting. Due to the lack of SSH shell access, we will be using **GitHub Actions with FTP deployment**. This setup will automatically upload your site whenever you push changes to your GitHub repository.

## Prerequisites

*   **FTP Access:** You have FTP credentials for your AsiaRegistry hosting.
*   **Git Repository:** Your project is hosted on GitHub.
*   **`.github/workflows/ftp-deploy.yml` file:** This GitHub Actions workflow file has been added to your project.

## Step 1: Remove `deploy.php` (Optional but Recommended)

Since we are no longer using the Git webhook method, the `deploy.php` file is not needed. You can remove it from your project and from your hosting server to avoid confusion.

## Step 2: Configure GitHub Secrets for FTP Credentials

The GitHub Actions workflow uses secrets to securely store your FTP credentials.

1.  **Go to your GitHub repository:** Navigate to your project's repository on GitHub (e.g., `https://github.com/Sothpanha682/CMS-WEB-MACA.git`).
2.  **Access Settings:** Click on the "Settings" tab.
3.  **Navigate to Secrets and variables:** In the left sidebar, click on "Secrets and variables" -> "Actions".
4.  **Add new repository secrets:** Click on "New repository secret" and add the following secrets, using your actual FTP details:
    *   **`FTP_SERVER`**: Your FTP host (e.g., `ftp.yourdomain.com` or `18.139.13.90`).
    *   **`FTP_USERNAME`**: Your FTP username (e.g., `d0k50n7p`).
    *   **`FTP_PASSWORD`**: Your FTP password.
    *   **`FTP_PORT`**: Your FTP port (usually `21` for FTP, or `22` if your hosting supports SFTP and you intend to use it).
    *   **`FTP_PROTOCOL`**: Your FTP protocol (`ftp`, `ftps`, or `sftp`). Check with your hosting provider which protocol is supported. If you are unsure, `ftp` is a common default.

    **Example:**
    *   `FTP_SERVER`: `18.139.13.90`
    *   `FTP_USERNAME`: `d0k50n7p`
    *   `FTP_PASSWORD`: `your_ftp_password_here`
    *   `FTP_PORT`: `21`
    *   `FTP_PROTOCOL`: `ftp`

## Step 3: Review `ftp-deploy.yml`

1.  **Open `.github/workflows/ftp-deploy.yml`** in your project.
2.  **Verify `branches`:** Ensure the `branches` section under `on: push:` matches your deployment branch (e.g., `main`).
    ```yaml
    on:
      push:
        branches:
          - main # or your primary branch, e.g., master
    ```
3.  **Verify `server-dir`:** Ensure `server-dir` is set to the correct root directory on your hosting where your website files should go (e.g., `/public_html/`).
    ```yaml
    server-dir: /public_html/ # The directory on your server to deploy to
    ```
4.  **Verify `protocol` and `port`:** If your hosting uses a different FTP protocol (e.g., `ftps` or `sftp`) or port, update these lines in the workflow file.
    ```yaml
    port: ${{ secrets.FTP_PORT }} # Usually 21 for FTP, 22 for SFTP (if available)
    protocol: ftp # or ftps, sftp (if available)
    ```
    If you set `FTP_PORT` and `FTP_PROTOCOL` secrets, the workflow will use those. Otherwise, it will default to `ftp` on port `21`.

## Step 4: Test the CI/CD Setup

1.  Make a small change to a file in your local project.
2.  Commit the change and push it to your GitHub repository's deployment branch (e.g., `main`).
    ```bash
    git add .
    git commit -m "Test CI/CD setup with FTP"
    git push origin main
    ```
3.  **Check GitHub Actions:** Go to your GitHub repository, click on the "Actions" tab. You should see a workflow run named "FTP Deploy" in progress.
4.  **Monitor the workflow:** Click on the running workflow to see the steps. If it completes successfully, it means your files were uploaded.
5.  **Verify on your website:** After the GitHub Actions workflow completes, visit your website (`https://mymaca.asia/`). The changes should be live.

## Troubleshooting

*   **Workflow fails:** Check the logs in the GitHub Actions run for specific error messages. Common issues include incorrect FTP credentials, wrong server directory, or firewall issues.
*   **Changes not appearing:** Ensure the `server-dir` in `ftp-deploy.yml` is the absolute correct path to your website's root on the hosting. Also, clear your browser cache.
*   **FTP connection issues:** Double-check your `FTP_SERVER`, `FTP_USERNAME`, `FTP_PASSWORD`, `FTP_PORT`, and `FTP_PROTOCOL` secrets in GitHub.
