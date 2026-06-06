<?php
// ============================================
// Bean & Brew Cafe - ADMIN LOGIN (WORKING)
// ============================================

session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'bean_and_brew');
if ($conn->connect_error) {
    die("Database error. Run setup.php first.");
}
$conn->set_charset("utf8mb4");

// If already logged in go to dashboard
if (isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// Handle login
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter username and password.';
    } else {
        $stmt = $conn->prepare("SELECT id, username, password, full_name FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                // SUCCESS
                session_regenerate_id(true);
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_name'] = $user['full_name'];
                $_SESSION['admin_username'] = $user['username'];
                
                header("Location: index.php");
                exit();
            } else {
                $error = 'Wrong password!';
            }
        } else {
            $error = 'Username not found!';
        }
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Bean & Brew Cafe</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #1a0e05;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Background Pattern */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80"><circle cx="40" cy="40" r="2" fill="rgba(228,214,169,0.03)"/></svg>') repeat;
            background-size: 80px 80px;
        }

        /* Animated coffee beans */
        .bean {
            position: absolute;
            font-size: 2rem;
            opacity: 0.06;
            animation: floatBean 20s linear infinite;
        }
        .bean:nth-child(1) { left: 10%; animation-delay: 0s; }
        .bean:nth-child(2) { left: 30%; animation-delay: 5s; }
        .bean:nth-child(3) { left: 50%; animation-delay: 10s; }
        .bean:nth-child(4) { left: 70%; animation-delay: 15s; }
        .bean:nth-child(5) { left: 90%; animation-delay: 3s; }

        @keyframes floatBean {
            0% { transform: translateY(100vh) rotate(0deg); }
            100% { transform: translateY(-100px) rotate(720deg); }
        }

        /* Login Card */
        .login-card {
            background: #ffffff;
            padding: 50px 40px;
            border-radius: 24px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.4);
            position: relative;
            z-index: 10;
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Logo */
        .login-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 35px;
        }

        .login-logo i {
            font-size: 2.5rem;
            color: #D4A843;
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .login-logo span {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #1a0e05;
        }

        /* Title */
        .login-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-title h2 {
            font-family: 'Playfair Display', serif;
            color: #1a0e05;
            font-size: 1.6rem;
            margin-bottom: 5px;
        }

        .login-title p {
            color: #8B7D6B;
            font-size: 0.88rem;
        }

        /* Error Message */
        .error-box {
            background: #fff2f2;
            border: 1px solid #ffcdd2;
            border-left: 4px solid #e53935;
            color: #c62828;
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: shake 0.5s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-5px); }
            40% { transform: translateX(5px); }
            60% { transform: translateX(-3px); }
            80% { transform: translateX(3px); }
        }

        /* Form Group */
        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #1a0e05;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .input-box {
            position: relative;
        }

        .input-box i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #8B7D6B;
            font-size: 0.95rem;
            transition: 0.3s;
        }

        .input-box input {
            width: 100%;
            padding: 15px 16px 15px 48px;
            border: 2px solid #E4D6A9;
            border-radius: 12px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            color: #1a0e05;
            background: #FDF8ED;
            transition: 0.3s;
            outline: none;
        }

        .input-box input:focus {
            border-color: #8B6F47;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(139, 111, 71, 0.1);
        }

        .input-box input:focus + i,
        .input-box input:focus ~ i {
            color: #8B6F47;
        }

        /* Password toggle */
        .pwd-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #8B7D6B;
            cursor: pointer;
            background: none;
            border: none;
            font-size: 0.95rem;
            padding: 5px;
        }

        .pwd-toggle:hover {
            color: #8B6F47;
        }

        /* Login Button */
        .login-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #1a0e05 0%, #2d1f12 100%);
            color: #E4D6A9;
            border: none;
            border-radius: 12px;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            letter-spacing: 0.5px;
            margin-top: 5px;
        }

        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(26, 14, 5, 0.3);
        }

        .login-btn:active {
            transform: translateY(-1px);
        }

        /* Loading state */
        .login-btn.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        /* Back link */
        .back-link {
            text-align: center;
            margin-top: 25px;
        }

        .back-link a {
            color: #8B6F47;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .back-link a:hover {
            color: #D4A843;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-card {
                padding: 35px 25px;
            }
            .login-logo span {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Floating beans -->
    <div class="bean">☕</div>
    <div class="bean">☕</div>
    <div class="bean">☕</div>
    <div class="bean">☕</div>
    <div class="bean">☕</div>

    <div class="login-card">
        <!-- Logo -->
        <div class="login-logo">
            <i class="fas fa-mug-hot"></i>
            <span>Bean & Brew</span>
        </div>

        <!-- Title -->
        <div class="login-title">
            <h2>Admin Panel</h2>
            <p>Sign in to manage your cafe</p>
        </div>

        <!-- Error -->
        <?php if (!empty($error)): ?>
        <div class="error-box">
            <i class="fas fa-exclamation-circle"></i>
            <span><?php echo htmlspecialchars($error); ?></span>
        </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" action="" id="loginForm">
            <div class="form-group">
                <label for="username">
                    <i class="fas fa-user" style="margin-right:5px;"></i> Username
                </label>
                <div class="input-box">
                    <i class="fas fa-user"></i>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           placeholder="Enter your username" 
                           required 
                           autofocus
                           autocomplete="username"
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock" style="margin-right:5px;"></i> Password
                </label>
                <div class="input-box">
                    <i class="fas fa-lock"></i>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           placeholder="Enter your password" 
                           required
                           autocomplete="current-password">
                    <button type="button" class="pwd-toggle" onclick="togglePassword()">
                        <i class="fas fa-eye" id="pwdIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="login-btn" id="loginBtn">
                <i class="fas fa-sign-in-alt"></i>
                <span>Sign In</span>
            </button>
        </form>

        <!-- Back Link -->
        <div class="back-link">
            <a href="../">
                <i class="fas fa-arrow-left"></i> Back to Website
            </a>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            var pwd = document.getElementById('password');
            var icon = document.getElementById('pwdIcon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                pwd.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        // Loading state on submit
        document.getElementById('loginForm').addEventListener('submit', function() {
            var btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing in...';
        });
    </script>
</body>
</html>