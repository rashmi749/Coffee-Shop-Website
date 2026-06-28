<?php
// ============================================
// Bean & Brew Cafe - Contact Form Handler
// ============================================
require_once 'includes/config.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone'] ?? '');
    $subject = sanitize($_POST['subject']);
    $message = sanitize($_POST['message']);
    
    // Validate
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        setFlash('error', 'Please fill in all required fields.');
        redirect(SITE_URL . '/pages/contact.php');
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        setFlash('error', 'Please enter a valid email address.');
        redirect(SITE_URL . '/pages/contact.php');
    }
    
    // Insert into database
    $stmt = $conn->prepare("INSERT INTO contacts (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);
    
    if ($stmt->execute()) {
        setFlash('success', 'Thank you! Your message has been sent successfully. We\'ll get back to you soon.');
    } else {
        setFlash('error', 'Something went wrong. Please try again later.');
    }
    
    $stmt->close();
    redirect(SITE_URL . '/pages/contact.php');
} else {
    redirect(SITE_URL . '/pages/contact.php');
}
?>