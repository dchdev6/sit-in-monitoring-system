<?php

require_once '../asset/navbar_student.php';
require_once '../../controller/api_student.php';
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
                             src="<?php echo !empty($_SESSION['profile_image']) ? '../../images/' . $_SESSION['profile_image'] : '../../images/default-profile.jpg'; ?>" 
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
                            <input type="text" value="<?php echo $_SESSION["yearLevel"]; ?>" id="courseLevel" class="form-control" name="courseLevel" required>

                            <label for="course" class="form-label mt-3">Course</label>
                            <input type="text" value="<?php echo $_SESSION["course"]; ?>" id="course" class="form-control" name="course" required>

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
