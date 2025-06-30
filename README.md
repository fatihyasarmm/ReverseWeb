Welcome to ReverseWeb! This project is an intentionally vulnerable web application designed for educational purposes. It allows security enthusiasts, students, and developers to practice identifying and exploiting common web vulnerabilities in a safe, controlled environment.

## 🚀 Features & Vulnerabilities

This application includes a variety of real-world vulnerabilities to test your skills:

* **SQL Injection**
    * UNION Attack (Data Exfiltration)
* **Cross-Site Scripting (XSS)**
    * Reflected XSS
    * Stored XSS
* **Insecure Direct Object Reference (IDOR)**
    * Via a leaky API endpoint.
* **Remote Code Execution (RCE)**
* **JSON Web Token (JWT) Vulnerabilities**
* **Race Condition**
    * Exploiting a time-of-check-to-time-of-use (TOCTOU) flaw in a coupon system.
* **CORS Misconfiguration**
* And other logical flaws!

## 🔧 Requirements

* [Docker Desktop](https://www.docker.com/products/docker-desktop/)
* A tool for interception and advanced requests like [Burp Suite](https://portswigger.net/burp) (especially for Race Condition).

## ⚙️ Installation and Setup

Setting up the project is easy thanks to Docker. Follow these steps:

1.  **Clone the Repository:**
    Clone this project to your local machine using Git:
    ```bash
    git clone [https://github.com/fatihyasarmm/ReverseWeb.git](https://github.com/fatihyasarmm/ReverseWeb.git)
    cd ReverseWeb
    ```

2.  **Start the Docker Containers:**
    Open a terminal in the project's root directory (where `docker-compose.yml` is located) and run the following command.
    ```bash
    docker-compose up -d
    ```

3.  **Install PHP Dependencies:**
    After the containers are running, execute the following command to install the required PHP libraries via Composer:
    ```bash
    docker-compose exec php-app composer install
    ```

4.  **Set Up the Database:**
    * Open your web browser and navigate to **`http://localhost:8080`** to access PhpMyAdmin.
    * Log in with the following credentials:
        * **Server:** `db`
        * **Username:** `root`
        * **Password:** `root_password`
    * On the left, click on the `reverseweb_db` database.
    * Click on the **"Import"** tab at the top.
    * Click "Choose File" and select the `reverseweb_db.sql` file from this project.
    * Click "Go". This will create all necessary tables and populate them with sample data.

5.  **Access the Application:**
    You're all set! Navigate to **`http://localhost`** in your browser to start using the application.ü

   ## (Optional but Recommended for Using Burpsuite) Editing Your Hosts File
   

Some modern browsers treat `localhost` with special security rules, which can sometimes interfere with testing certain vulnerabilities (like cookie-based attacks or CORS). To get the most realistic experience, it's recommended to access the application through a custom domain name like `reverseweb.local`.

To do this, you need to edit the `hosts` file on your computer:

---

## 🪟 On Windows

1. Open **Notepad (Not Defteri)** as an Administrator:
    
    - Search for **Notepad** in the Start Menu
        
    - Right-click it and select **"Run as administrator"**
        
2. From Notepad, go to **File > Open**
    
3. Navigate to:  
    `C:\Windows\System32\drivers\etc\`
    
4. In the bottom-right of the **Open** dialog, change  
    `"Text Documents (*.txt)"` to `"All Files (*.*)"`
    
5. Select the **hosts** file and click **Open**
    
6. Add the following line to the end of the file:
    
    `127.0.0.1 reverseweb.local`
    
7. Save the file and close Notepad.
    
---

## 🐧 On Linux/macOS

1. Open a terminal
    
2. Run the following command:

    `sudo nano /etc/hosts`
    
3. Enter your password when prompted.
    
4. Add the following line to the end of the file:
    
    `127.0.0.1 reverseweb.local`
    
5. Save the file:
    
    - Press `Ctrl+O`, then `Enter`
        
    - Press `Ctrl+X` to exit

## 👨‍💻 Test Accounts

You can use the following accounts to test the application:

* **Normal User:**
    * **Username:** `fatih`
    * **Password:** `fatih123`

Contact me : tasdas90@gmail.com


Happy hacking!
