<?php

require_once '../api/api_index.php';
require_once '../includes/navbar.php';
	
	
?>
<!doctype html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
	</head>

	<body>

    <section class="min-vh-75 d-flex align-items-center mt-5 ">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-75">
            <!-- Left Side: Text -->
            <div class="col-md-6">
                <h1>Sit-in <span class="text-primary">Monitoring</span> System</h1>
                <p class="lead">Track and manage sit-in sessions efficiently. Ensure proper attendance, monitor participants, and maintain organized records with ease!</p>
            </div>


            <!-- Right Side: Login Form -->
            <div class="col-md-4 d-flex justify-content-end">
                <div class="w-100" style="max-width: 400px;">
                    <form action="login.php" method="POST">
                        <div class="form-outline mb-3">
                            <label for="inputEmail" class="form-label">Username</label>
                            <input type="text" class="form-control" id="inputEmail" name="idNum" required>
                        </div>
                        <div class="form-outline mb-3">
                            <label for="inputPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="inputPassword" name="password" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="checkbox">
                            <label class="form-check-label" for="checkbox">Remember Me</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" name="submit">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>

<?php

// Session Notification

	if($_GET['num']==1){
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
			icon: "success",
			title: "Register Successfuly!"
		  });</script>';
	}
	//Session check
?>