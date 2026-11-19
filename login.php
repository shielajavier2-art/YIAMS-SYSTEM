

<?php
session_start();

// If user is already logged in, send them to their dashboard
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'captain') {
        header("Location: admin-dashboard.php");
    } else {
        header("Location: youth-dashboard.php");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BARANGAY 22</title>
    
    <!-- Fixed Google Fonts link -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <style>   * { margin: 0; padding: 0; box-sizing: border-box; }
      
body {
    font-family: 'Inter', sans-serif;
     min-height: 100vh;
            position: relative;
            isolation: isolate;
            overflow: hidden;

            display: flex;
            align-items: center;
            justify-content: center;
    background:
                linear-gradient(
                    115deg,
                    rgba(152, 162, 176, 0.88),
                    rgba(78, 115, 164, 0.72),
                    rgba(136, 178, 231, 0.88)
                ),
                url("youth_logo.jpg");
               background-size: cover, 800px;
               background-position: center;
               background-repeat: no-repeat;
        }


        .login-card {
            background: transparent;
            width: 100%;
            max-width: 420px;
            padding: 40px 32px;
            border-radius: 14px;
            box-shadow: 0 8px 30px rgba(121, 32, 32, 0.08);
            background-image: url("barangay-seal.png");
               background-position: center;
               background-repeat: no-repeat;  
               background-blend-mode: lighten;
               justify-content: center;
               align-items: center; 
             

            
        }

        .card-top-label {
            text-transform: uppercase;
            color: #010101;
            text-align: center;
            margin-bottom: 12px;
            font-weight: 500;
            letter-spacing: 2px;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            color: #1a2b42;
            text-align: center;
            margin-bottom: 12px;
        }

        .divider {
            width: 150px;
            height: 1px;
            background: #151002;
            margin: 0 auto 32px;
        }

        .error-text {
            color: #cc0000;
            text-align: center;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 22px;
        }

        label {
            display: block;
            font-size: 15px;
            color: #0c0b0b;
            margin-bottom: 8px;
            font-weight: 500;
        }

        input, select {
            width: 100%;
            padding: 15px 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            background: #f8f8f8;
            outline: none;
        }

        input:focus, select:focus {
            border-color: #080807;
            background: #fff;
        }

        input::placeholder {
            color: #999;
        }

        .show-password-container {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
            cursor: pointer;
        }

        .show-password-container input {
            width: auto;
            margin: 0;
        }

        .show-password-container span {
            font-size: 14px;
            color: #140101;
        }

        .login-btn {
            width: 100%;
            padding: 16px;
            background: #0f1c2e;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 17px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 20px;
            transition: 0.3s;
        }

        .login-btn:hover {
            background: #1a2b42;
            transform: translateY(-1px);
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #180303;
            font-size: 14px;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 20px;
            }

            .login-card {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-card">
        <p class="card-top-label">Barangay Services</p>
        <h1>ROLE SELECTION</h1>
        <div class="divider"></div>

        <?php if (isset($_GET['error'])): ?>
            <p class="error-text">
                <?php
                if ($_GET['error'] === 'notfound') {
                    echo "❌ Account not found! Check username/email.";
                } elseif ($_GET['error'] === 'wrongpass') {
                    echo "❌ Incorrect password! Try again.";
                } elseif ($_GET['error'] === 'role') {
                    echo "❌ Invalid account role!";
                }
                ?>
            </p>
      <?php endif; ?>
      

<form action="login-process.php" method="POST">

    <div class="form-group">
        <label for="role">Select Role</label>
        <select id="role" name="role" required>
            <option value="">-- Select your role --</option>
            <option value="youth">Youth</option>
            <option value="admin">Admin / Captain</option>
        </select>
    </div>


        <form action="login-process.php" method="POST">
            <div class="form-group">
                <label for="userCred">Username / Email</label>
                <input
                    type="text"
                    id="userCred"
                    name="userCred"
                    placeholder="Enter your username or email"
                    required
                >
            </div>

           

            <div class="form-group">
                <label for="userPass">Password</label>
                <input
                    type="password"
                    id="userPass"
                    name="password"
                    placeholder="Enter your password"
                   
                >

                <label class="show-password-container">
                    <input type="checkbox" onclick="togglePassword()">
                    <span>Show Password</span>
                </label>
            </div>

            <button type="submit" class="login-btn">
                LOG IN
            </button>

            <div class="back-link">
                <a href="javascript:history.back()">
                    ← Go Back
                </a>
            </div>
        </form>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('userPass');
            password.type = password.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>