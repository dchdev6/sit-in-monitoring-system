<?php

require_once '../../includes/navbar_student.php';
require_once '../../api/api_student.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>

<!-- ✅ FIXED: Changed action to api_student.php to update both profile & image -->
<form action="../../Controller/api_student.php" method="POST" enctype="multipart/form-data">
    <section class="vh-75 d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="text-center fw-bold mt-5 mb-4">Edit Profile</h2>

                    <div class="text-center mb-3">
                        <!-- Profile Picture Preview -->
                        <img id="profilePreview" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;" 
                             src="<?php echo !empty($_SESSION['profile_image']) ? '../../assets/images/' . $_SESSION['profile_image'] : '../../images/default-profile.jpg'; ?>" 
                             alt="Profile Picture">
                    </div>

                    <div class="mb-3 text-center">
                        <label for="profileImage" class="form-label">Upload Profile Picture</label>
                        <input type="file" class="form-control" id="profileImage" name="profile_image" accept="image/*" onchange="previewImage(event)">
                        <!-- Hidden field to retain existing image if no new file is uploaded -->
                        <input type="hidden" name="existing_profile_image" value="<?php echo $_SESSION['profile_image']; ?>">
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="idNumber" class="form-label">ID Number</label>
                            <input type="text" value="<?php echo $_SESSION["id_number"]; ?>" id="idNumber" class="form-control" name="idNumber" readonly>
                            <input type="hidden" name="idNumber" value="<?php echo $_SESSION['id_number']; ?>">

                            <label for="lName" class="form-label mt-3">Last Name</label>
                            <input type="text" value="<?php echo $_SESSION["lname"]; ?>" id="lName" class="form-control" name="lName" required>

                            <label for="fName" class="form-label mt-3">First Name</label>
                            <input type="text" value="<?php echo $_SESSION["fname"]; ?>" id="fName" class="form-control" name="fName" required>

                            <label for="mName" class="form-label mt-3">Middle Name</label>
                            <input type="text" value="<?php echo $_SESSION["mname"]; ?>" id="mName" class="form-control" name="mName" required>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" value="<?php echo $_SESSION["email"]; ?>" id="email" class="form-control" name="email" required>

                            <label for="courseLevel" class="form-label mt-3">Course Level</label>
                                <select name="courseLevel" id="courseLevel" class="form-select" required>
                                    <option value="1" <?php echo ($_SESSION["yearLevel"] == 1) ? 'selected' : ''; ?>>1</option>
                                    <option value="2" <?php echo ($_SESSION["yearLevel"] == 2) ? 'selected' : ''; ?>>2</option>
                                    <option value="3" <?php echo ($_SESSION["yearLevel"] == 3) ? 'selected' : ''; ?>>3</option>
                                    <option value="4" <?php echo ($_SESSION["yearLevel"] == 4) ? 'selected' : ''; ?>>4</option>
                                </select>

                                <label for="course" class="form-label mt-3">Course</label>
                                    <select name="course" id="course" class="form-select" required>
                                        <option value="BSCS" <?php echo ($_SESSION["course"] == 'BSCS') ? 'selected' : ''; ?>>Bachelor of Science in Computer Science</option>
                                        <option value="BSIT" <?php echo ($_SESSION["course"] == 'BSIT') ? 'selected' : ''; ?>>Bachelor of Science in Information Technology</option>
                                        <option value="BSIS" <?php echo ($_SESSION["course"] == 'BSIS') ? 'selected' : ''; ?>>Bachelor of Science in Information System</option>
                                        <option value="BSP" <?php echo ($_SESSION["course"] == 'BSP') ? 'selected' : ''; ?>>Bachelor of Science in Psychology</option>
                                        <option value="BSBA" <?php echo ($_SESSION["course"] == 'BSBA') ? 'selected' : ''; ?>>Bachelor of Science in Business Administration</option>
                                        <option value="BSN" <?php echo ($_SESSION["course"] == 'BSN') ? 'selected' : ''; ?>>Bachelor of Science in Nursing</option>
                                        <option value="BSM" <?php echo ($_SESSION["course"] == 'BSM') ? 'selected' : ''; ?>>Bachelor of Science in Midwifery</option>
                                        <option value="BAB" <?php echo ($_SESSION["course"] == 'BAB') ? 'selected' : ''; ?>>Bachelor of Arts in Broadcasting</option>
                                        <option value="BAC" <?php echo ($_SESSION["course"] == 'BAC') ? 'selected' : ''; ?>>Bachelor of Arts in Communication</option>
                                        <option value="BADC" <?php echo ($_SESSION["course"] == 'BADC') ? 'selected' : ''; ?>>Bachelor of Arts in Development Communication</option>
                                        <option value="BAJ" <?php echo ($_SESSION["course"] == 'BAJ') ? 'selected' : ''; ?>>Bachelor of Arts in Journalism</option>
                                        <option value="BAMC" <?php echo ($_SESSION["course"] == 'BAMC') ? 'selected' : ''; ?>>Bachelor of Arts in Mass Communication</option>
                                    </select>

                            <label for="address" class="form-label mt-3">Address</label>
                            <input type="text" value="<?php echo $_SESSION["address"]; ?>" id="address" class="form-control" name="address" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4 mb-5">
                        <button class="btn btn-primary" type="submit" name="submit">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>

<script>
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function() {
        var output = document.getElementById('profilePreview');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>
</html>
