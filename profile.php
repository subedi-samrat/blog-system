<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit();
}

$conn = getConnection();
$error = null;
$success = null;

// Get user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Initialize update fields array
    $updates = [];
    $types = "";
    $params = [];

    // Username update (required)
    if (!empty($_POST['username'])) {
        $username = trim($_POST['username']);
        if ($username !== $user['username']) {
            // Check if username is already taken
            $check = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
            $check->bind_param("si", $username, $_SESSION['user_id']);
            $check->execute();
            if ($check->get_result()->num_rows > 0) {
                $error = "Username already taken";
            } else {
                $updates[] = "username = ?";
                $types .= "s";
                $params[] = $username;
            }
        }
    }

    // Email update (required)
    if (!empty($_POST['email'])) {
        $email = trim($_POST['email']);
        if ($email !== $user['email']) {
            // Check if email is already taken
            $check = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $check->bind_param("si", $email, $_SESSION['user_id']);
            $check->execute();
            if ($check->get_result()->num_rows > 0) {
                $error = "Email already taken";
            } else {
                $updates[] = "email = ?";
                $types .= "s";
                $params[] = $email;
            }
        }
    }

    // Full name update (optional)
    if (isset($_POST['full_name'])) {
        $full_name = trim($_POST['full_name']);
        if ($full_name !== $user['full_name']) {
            $updates[] = "full_name = ?";
            $types .= "s";
            $params[] = $full_name;
        }
    }

    // Bio update (optional)
    if (isset($_POST['bio'])) {
        $bio = trim($_POST['bio']);
        if ($bio !== $user['bio']) {
            $updates[] = "bio = ?";
            $types .= "s";
            $params[] = $bio;
        }
    }

    // Password update (optional)
    if (!empty($_POST['new_password'])) {
        if (empty($_POST['current_password'])) {
            $error = "Current password is required to set a new password";
        } elseif (!password_verify($_POST['current_password'], $user['password'])) {
            $error = "Current password is incorrect";
        } else {
            $password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            $updates[] = "password = ?";
            $types .= "s";
            $params[] = $password;
        }
    }

    // Profile image upload (optional)
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $upload_dir = 'uploads/profiles/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_ext = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
        $new_file_name = uniqid() . '_' . time() . '.' . $file_ext;
        $upload_path = $upload_dir . $new_file_name;

        $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array($file_ext, $allowed_types)) {
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                // Delete old profile image if exists
                if ($user['profile_image'] && file_exists($user['profile_image'])) {
                    unlink($user['profile_image']);
                }
                $updates[] = "profile_image = ?";
                $types .= "s";
                $params[] = $upload_path;
            }
        }
    }

    // Process update if there are changes and no errors
    if (!empty($updates) && !$error) {
        // Add user ID to parameters
        $types .= "i";
        $params[] = $_SESSION['user_id'];

        // Construct and execute update query
        $sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            $success = "Profile updated successfully";
            
            // Update session username if it was changed
            if (isset($username)) {
                $_SESSION['username'] = $username;
            }

            // Refresh user data
            $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
        } else {
            $error = "Failed to update profile: " . $conn->error;
        }
    } elseif (empty($updates) && !$error) {
        $success = "No changes to update";
    }
}

// Get user's recent activity
$stmt = $conn->prepare("
    SELECT p.*, COUNT(c.id) as comment_count 
    FROM posts p 
    LEFT JOIN comments c ON p.id = c.post_id 
    WHERE p.user_id = ? 
    GROUP BY p.id 
    ORDER BY p.created_at DESC 
    LIMIT 5
");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$recent_posts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$page_title = "Profile - " . $user['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .profile-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: var(--white);
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
        }

        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-info h1 {
            margin-bottom: 0.5rem;
        }

        .profile-form {
            margin-top: 2rem;
        }

        .recent-activity {
            margin-top: 3rem;
        }

        .activity-card {
            padding: 1rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="container">
        <div class="profile-container">
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="profile-header">
                <div class="profile-image">
                    <?php if ($user['profile_image'] && file_exists($user['profile_image'])): ?>
                        <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" 
                             alt="Profile picture">
                    <?php else: ?>
                        <img src="https://placehold.co/150x150/2563eb/ffffff?text=<?php echo substr($user['username'], 0, 1); ?>" 
                             alt="Profile picture">
                    <?php endif; ?>
                </div>
                <div class="profile-info">
                    <h1><?php echo htmlspecialchars($user['full_name']); ?></h1>
                    <p>@<?php echo htmlspecialchars($user['username']); ?></p>
                    <p><?php echo htmlspecialchars($user['bio'] ?? ''); ?></p>
                </div>
            </div>

            <form method="POST" enctype="multipart/form-data" class="profile-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" 
                           value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" 
                           value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" rows="4"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="profile_image">Profile Image</label>
                    <input type="file" id="profile_image" name="profile_image" accept="image/*">
                </div>

                <div class="form-group">
                    <label for="current_password">Current Password (required for password change)</label>
                    <input type="password" id="current_password" name="current_password">
                </div>

                <div class="form-group">
                    <label for="new_password">New Password (leave blank to keep current)</label>
                    <input type="password" id="new_password" name="new_password">
                </div>

                <button type="submit" class="btn-primary">Update Profile</button>
            </form>

            <section class="recent-activity">
                <h2>Recent Activity</h2>
                <?php if (empty($recent_posts)): ?>
                    <p>No recent activity</p>
                <?php else: ?>
                    <?php foreach ($recent_posts as $post): ?>
                        <div class="activity-card">
                            <h3>
                                <a href="post.php?slug=<?php echo htmlspecialchars($post['slug']); ?>">
                                    <?php echo htmlspecialchars($post['title']); ?>
                                </a>
                            </h3>
                            <div class="post-meta">
                                <span><?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                                <span><?php echo number_format($post['views']); ?> views</span>
                                <span><?php echo number_format($post['comment_count']); ?> comments</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>