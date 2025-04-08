<?php
require_once '../../includes/navbar_student.php';
require_once '../../api/api_student.php';

// Check for messages
$successMessage = '';
$errorMessage = '';
$uploadError = '';

if(isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

if(isset($_SESSION['error_message'])) {
    $errorMessage = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

if(isset($_SESSION['upload_error'])) {
    $uploadError = $_SESSION['upload_error'];
    unset($_SESSION['upload_error']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Profile Update</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px 15px; background: #0284c7; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .success { color: green; background: #e7f3e7; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .error { color: red; background: #f3e7e7; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <h1>Simple Profile Update</h1>
    
    <?php if(!empty($successMessage)): ?>
    <div class="success"><?php echo $successMessage; ?></div>
    <?php endif; ?>
    
    <?php if(!empty($errorMessage)): ?>
    <div class="error"><?php echo $errorMessage; ?></div>
    <?php endif; ?>
    
    <?php if(!empty($uploadError)): ?>
    <div class="error"><?php echo $uploadError; ?></div>
    <?php endif; ?>
    
    <form action="../../api/profile_update.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="idNumber">ID Number</label>
            <input type="text" id="idNumber" name="idNumber" value="<?php echo $_SESSION['id_number']; ?>" readonly>
        </div>
        
        <div class="form-group">
            <label for="lName">Last Name</label>
            <input type="text" id="lName" name="lName" value="<?php echo $_SESSION['lname']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="fName">First Name</label>
            <input type="text" id="fName" name="fName" value="<?php echo $_SESSION['fname']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="mName">Middle Name</label>
            <input type="text" id="mName" name="mName" value="<?php echo $_SESSION['mname']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo $_SESSION['email']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="courseLevel">Year Level</label>
            <select id="courseLevel" name="courseLevel" required>
                <option value="1" <?php echo ($_SESSION['yearLevel'] == 1) ? 'selected' : ''; ?>>1</option>
                <option value="2" <?php echo ($_SESSION['yearLevel'] == 2) ? 'selected' : ''; ?>>2</option>
                <option value="3" <?php echo ($_SESSION['yearLevel'] == 3) ? 'selected' : ''; ?>>3</option>
                <option value="4" <?php echo ($_SESSION['yearLevel'] == 4) ? 'selected' : ''; ?>>4</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="course">Course</label>
            <select id="course" name="course" required>
                <option value="BSCS" <?php echo ($_SESSION['course'] == 'BSCS') ? 'selected' : ''; ?>>Bachelor of Science in Computer Science</option>
                <option value="BSIT" <?php echo ($_SESSION['course'] == 'BSIT') ? 'selected' : ''; ?>>Bachelor of Science in Information Technology</option>
                <option value="BSIS" <?php echo ($_SESSION['course'] == 'BSIS') ? 'selected' : ''; ?>>Bachelor of Science in Information System</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" id="address" name="address" value="<?php echo $_SESSION['address']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="profile_image">Profile Image</label>
            <input type="file" id="profile_image" name="profile_image" accept="image/*">
            <p>Current image: <?php echo $_SESSION['profile_image']; ?></p>
            <?php if (!empty($_SESSION['profile_image'])): ?>
                <img src="../../assets/images/<?php echo $_SESSION['profile_image']; ?>" width="100">
            <?php endif; ?>
        </div>
        
        <button type="submit" name="submit">Update Profile</button>
    </form>
</body>
</html>