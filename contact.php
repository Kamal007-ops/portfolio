<?php
// Define variables and initialize with empty values
$name = $email = $subject = $message = "";
$name_err = $email_err = $subject_err = $message_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter your name.";
    } else {
        $name = trim($_POST["name"]);
    }
    
    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email address.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email address.";
    } else {
        $email = trim($_POST["email"]);
    }
    
    // Validate subject
    if (empty(trim($_POST["subject"]))) {
        $subject_err = "Please enter a subject.";
    } else {
        $subject = trim($_POST["subject"]);
    }
    
    // Validate message
    if (empty(trim($_POST["message"]))) {
        $message_err = "Please enter your message.";
    } else {
        $message = trim($_POST["message"]);
    }
    
    // Check input errors before sending email
    if (empty($name_err) && empty($email_err) && empty($subject_err) && empty($message_err)) {
        $to = "pixelphotography@gmail.com"; // Your email address
        $headers = "From: " . $email;
        $email_subject = "Contact Form: " . $subject;
        $email_message = "Name: " . $name . "\n";
        $email_message .= "Email: " . $email . "\n";
        $email_message .= "Subject: " . $subject . "\n";
        $email_message .= "Message: \n" . $message;
        
        // Send email
        if (mail($to, $email_subject, $email_message, $headers)) {
            echo "Your message has been sent.";
        } else {
            echo "Sorry, there was an error sending your message.";
        }
    }
}
?>
