# CoreLease: Environment Setup Guide

## Section 1: System Preparation (Windows)

1.  **Enable Virtualization**: Ensure that Virtualization (VT-x or AMD-V) is enabled in your computer's BIOS/UEFI settings.
2.  **Install WSL2 and Ubuntu**:
    *   Open **PowerShell** as Administrator.
    *   Execute the command: `wsl --install`
    *   Restart your computer when prompted.
    *   After the restart, Ubuntu will open a console. Follow the prompts to create a **Username** and **Password**. Note these credentials, as they are required for administrative tasks.
3.  **Update Ubuntu**:
    *   In the Ubuntu terminal, run:
        ```bash
        sudo apt update && sudo apt upgrade -y
        ```

---

## Section 2: Docker and VS Code Installation

1.  **Docker Desktop**:
    *   Download and install [Docker Desktop for Windows](https://www.docker.com/products/docker-desktop/).
    *   During installation, ensure **Install required Windows components for WSL 2** is checked.
    *   Once installed, open Docker Desktop. Go to **Settings > Resources > WSL Integration**. Enable the toggle for your **Ubuntu** distribution and click **Apply & Restart**.
2.  **Visual Studio Code**:
    *   Install [VS Code](https://code.visualstudio.com/).
    *   Open VS Code and install the **Dev Containers** extension and the **WSL** extension from the Extensions Marketplace (Ctrl+Shift+X).

---

## Section 3: Project Retrieval and Initial Configuration

**Important**: Do not store the project on the Windows file system (`C:\Users\...`). It must be stored inside the WSL file system for performance and permission compatibility.

1.  **Open Ubuntu Terminal** and navigate to your home directory:
    ```bash
    cd ~
    ```
2.  **Clone the Repository**:
    ```bash
    git clone https://github.com/your-organization/corelease.git
    cd corelease
    ```
3.  **Create the Environment File**:
    *   Before opening the container, create the local environment file from the template:
        ```bash
        cp .env.example .env
        ```
4.  **Open in VS Code**:
    ```bash
    code .
    ```

---

## Section 4: Initializing the DevContainer

1.  **Launch Container**: When VS Code opens, a prompt will appear in the bottom-right corner asking to **Reopen in Container**. Click this button.
    *   *Note: The initial build may take several minutes as it downloads the Ubuntu 24.04 image and installs PHP 8.3 and Apache.*
2.  **Verify Status**: Once the build is complete, the green status bar in the bottom-left corner should display **Dev Container: CoreLease Dev Environment**.

---

## Section 5: Internal Project Configuration

Open the integrated terminal in VS Code (Ctrl + `) and execute the following commands in order:

1.  **Install PHP Dependencies**:
    ```bash
    composer install
    ```
2.  **Configure Environment Variables**:
    *   Open the **.env** file in the VS Code editor.
    *   Update the following lines to match the Docker network configuration:
        ```dotenv
        APP_NAME=CoreLease
        APP_ENV=local
        APP_KEY=
        APP_DEBUG=true
        APP_URL=http://localhost:8000

        DB_CONNECTION=mysql
        DB_HOST=db
        DB_PORT=3306
        DB_DATABASE=corelease_db
        DB_USERNAME=dev_user
        DB_PASSWORD=dev_password
        ```
3.  **Generate Application Key**:
    ```bash
    php artisan key:generate
    ```
4.  **Set Directory Permissions**:
    ```bash
    chmod -R 775 storage bootstrap/cache
    ```

---

## Section 6: Synchronizing the Container State

After modifying the `.env` file and installing dependencies, it is necessary to refresh the container's environment to ensure all services recognize the new configuration.

1.  **Rebuild Container**:
    *   Press `F1` or `Ctrl+Shift+P` to open the Command Palette.
    *   Type and select: **Dev Containers: Rebuild Container**.
    *   Wait for the container to reload.
2.  **Finalize Database Structure**:
    *   Once the terminal is available again, run the migrations:
        ```bash
        php artisan migrate:fresh --seed
        ```

---

## Section 7: Verified Access Paths

The application is now accessible via the host machine's web browser:

*   **Application Interface**: [http://localhost:8000](http://localhost:8000)
*   **Database Management (phpMyAdmin)**: [http://localhost:8080](http://localhost:8080)
    *   **Server**: `db`
    *   **Username**: `root`
    *   **Password**: `root_password`

## Collaborative Standards

*   **Permissions**: If you experience "Permission Denied" errors, rerun the `chmod` command from Section 5.
*   **Version Control**: Always perform `git` commands from the VS Code terminal inside the container to maintain consistent file ownership.
*   **Dependencies**: If a team member adds a new library, you must run `composer install` followed by a container rebuild.
