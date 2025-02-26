<?php 
require_once 'navbar.php';
require_once 'Controller\api_index.php';



?>
<!doctype html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<head>

	
	</head>

	
	<body>
		
	<form action="Register.php" method="POST">
    <section class="min-vh-75 d-flex align-items-center mt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="row g-3">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <label for="idNumber" class="form-label">ID Number</label>
                            <input type="text" id="idNumber" class="form-control" name="idNumber" required>

                            <label for="lName" class="form-label mt-3">Last Name</label>
                            <input type="text" id="lName" class="form-control" name="lName" required>

                            <label for="fName" class="form-label mt-3">First Name</label>
                            <input type="text" id="fName" class="form-control" name="fName" required>

                            <label for="mName" class="form-label mt-3">Middle Name</label>
                            <input type="text" id="mName" class="form-control" name="mName" required>

                            <label for="level" class="form-label mt-3">Course Level</label>
                            <select name="level" id="level" class="form-select">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" class="form-control" name="password" required>

                            <label for="confirmPassword" class="form-label mt-3">Repeat Password</label>
                            <input type="password" id="confirmPassword" class="form-control" name="confirmPassword" required>

                            <label for="email" class="form-label mt-3">Email</label>
                            <input type="email" id="email" class="form-control" name="email" required>

                            <label for="course" class="form-label mt-3">Course</label>
                            <select name="course" id="course" class="form-select">
                                <option value="BSIT">BSIT</option>
                                <option value="BSCS">BSCS</option>
                                <option value="ACT">ACT</option>
                            </select>

                            <label for="address" class="form-label mt-3">Address</label>
                            <input type="text" id="address" class="form-control" name="address" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a class="btn btn-danger" href="Login.php">Back</a>
                        <button class="btn btn-primary" type="submit" name="submitRegister">Register</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
</body>
</html>

<?php

if($_GET['num']==2){
  echo '<script>const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.onmouseenter = Swal.stopTimer;
      toast.onmouseleave = Swal.resumeTimer;
    }
  });
  Toast.fire({
    icon: "error",
    title: "Duplicate ID Number!"
  });</script>';
}
?>