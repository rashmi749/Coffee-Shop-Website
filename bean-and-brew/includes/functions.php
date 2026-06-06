<?php
// ============================================
// Bean & Brew Cafe - Helper Functions
// ============================================

/**
 * Sanitize input
 */
function sanitize($data) {
    global $conn;
    return $conn->real_escape_string(htmlspecialchars(strip_tags(trim($data))));
}

/**
 * Redirect
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Check admin login
 */
function isLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

/**
 * Require admin login - redirect if not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: " . SITE_URL . "/admin/login.php");
        exit();
    }
}

/**
 * Flash messages
 */
function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        $type = $flash['type'];
        $message = $flash['message'];
        $bgClass = ($type === 'success') ? 'alert-success' : 'alert-danger';
        return "<div class='alert $bgClass'>$message</div>";
    }
    return '';
}

/**
 * Format price in LKR
 */
function formatPrice($price) {
    return 'Rs. ' . number_format((float)$price, 2);
}

/**
 * Upload image
 */
function uploadImage($fileInput, $destination, $existingImage = null) {
    if (!isset($_FILES[$fileInput]) || $_FILES[$fileInput]['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'No file uploaded.'];
    }

    $file = $_FILES[$fileInput];

    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'File too large (max 5MB).'];
    }

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ALLOWED_EXTENSIONS)) {
        return ['success' => false, 'message' => 'Invalid file type.'];
    }

    $newFilename = 'img_' . time() . '_' . uniqid() . '.' . $extension;
    $uploadPath = $destination . $newFilename;

    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        if ($existingImage && $existingImage !== 'default.jpg' && file_exists($destination . $existingImage)) {
            unlink($destination . $existingImage);
        }
        return ['success' => true, 'filename' => $newFilename];
    } else {
        return ['success' => false, 'message' => 'Upload failed. Check folder permissions.'];
    }
}

/**
 * Delete image file
 */
function deleteImage($filePath, $filename) {
    if ($filename && $filename !== 'default.jpg') {
        $fullPath = $filePath . $filename;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}

/**
 * Get image URL
 */
function getImageUrl($imagePath, $folder = 'menu') {
    if (empty($imagePath) || $imagePath === 'default.jpg') {
        return 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=600&q=80';
    }
    if (strpos($imagePath, 'http') === 0) {
        return $imagePath;
    }
    return UPLOAD_URL . $folder . '/' . $imagePath;
}

/**
 * Default image per category
 */
function getDefaultCategoryImage($category) {
    $defaults = [
        'coffee' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=600&q=80',
        'tea'    => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=600&q=80',
        'cakes'  => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&q=80',
        'snacks' => 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=600&q=80',
    ];
    return $defaults[$category] ?? $defaults['coffee'];
}

/**
 * Get menu item by ID
 */
function getMenuItem($id) {
    global $conn;
    $id = (int)$id;
    $result = $conn->query("SELECT * FROM menu_items WHERE id = $id");
    return $result->fetch_assoc();
}

/**
 * Count pending orders
 */
function countPendingOrders() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) as c FROM orders WHERE order_status = 'pending'");
    return $result->fetch_assoc()['c'];
}

/**
 * Count unread contacts
 */
function countUnreadContacts() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) as c FROM contacts WHERE is_read = 0");
    return $result->fetch_assoc()['c'];
}
?>