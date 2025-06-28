<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Status - ReverseWeb</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .status-message { background-color: white; padding: 2em; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 600px; margin: 2em auto; text-align: left; }
        .status-message h1 { text-align: center; color: #333; }
        .status-message p, .status-message div { margin-bottom: 1em; word-wrap: break-word; }
        .status-message strong { color: #555; }
        .status-message a { display: inline-block; margin-top: 1em; color: #5cb85c; text-decoration: none; }
        .status-message a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <header>
        <div class="logo"><a href="index.php">ReverseWeb</a></div>
        <nav class="user-nav">
            <a href="login.html">Login</a>
            <a href="register.php">Create Account</a>
        </nav>
    </header>

    <main>
        <div class="status-message">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $name = $_POST['contact_name'] ?? 'Not specified';
                $email = $_POST['contact_email'] ?? 'Not specified';
                $subject = $_POST['contact_subject'] ?? 'Not specified';
                $message = $_POST['contact_message'] ?? 'Empty';

                echo "<h1>Message Received!</h1>";
                echo "<p>Thank you, " . htmlspecialchars($name) . ".</p>"; 
                echo "<p><strong>Your Email Address:</strong> " . htmlspecialchars($email) . "</p>";
                echo "<p><strong>Subject:</strong> " . htmlspecialchars($subject) . "</p>";
                echo "<p><strong>Your Message:</strong></p>";
                echo "<div>" . nl2br(htmlspecialchars($message)) . "</div>"; 

                echo "<br><hr><br>";
                echo "<p><a href='index.php'>Return to Homepage</a></p>";
                echo "<p><a href='contact.html'>Send a New Message</a></p>";

            } else {
                echo "<h1>Bad Request!</h1>";
                echo "<p>You are not authorized to access this page directly.</p>";
                echo "<p><a href='contact.html'>Return to Contact Form</a></p>";
            }
            ?>
        </div>
    </main>

    <footer>
        <a href="about.html">About Us</a>
        <a href="contact.html">Contact</a>
        <p>&copy; <?php echo date("Y"); ?> ReverseWeb</p>
    </footer>
</body>
</html>