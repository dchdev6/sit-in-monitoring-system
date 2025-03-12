<?php
session_start();
error_log("Session Profile Image: " . $_SESSION["profile_image"]);

require_once '../../includes/navbar_student.php';

$announce = view_announcement(); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <style>
    .card-body {
      overflow-y: auto;
    }
  </style>
</head>

<body>

<div class="container">
    <h4 class="text-center my-4">Dashboard</h4>

    <div class="row g-4">
        <!-- Student Information Card -->
        <div class="col-md-4 d-flex">
            <div class="card h-75 w-100 d-flex flex-column">
                <h5 class="card-header text-white text-center bg-primary">Student Information</h5>
                <div class="card-body flex-grow-1 text-center">
                    
                <img class="img-fluid rounded-circle border border-3 shadow-sm mb-3" 
     style="width: 150px; height: 150px; object-fit: cover;" 
     src="<?php echo '../../assets/images/' . ($_SESSION['profile_image'] ?? 'default-profile.jpg'); ?>" 
     alt="Profile Picture">
<?php error_log("Session Profile Image: " . $_SESSION["profile_image"]); ?>




                    <p class="mb-1"><strong>Name:</strong> <?php echo $_SESSION['name']; ?></p>
                    <p class="mb-1"><strong>Course:</strong> <?php echo $_SESSION['course']; ?></p>
                    <p class="mb-1"><strong>Year:</strong> <?php echo $_SESSION['yearLevel']; ?></p>
                    <p class="mb-1"><strong>Email:</strong> <?php echo $_SESSION['email']; ?></p>
                    <p class="mb-1"><strong>Address:</strong> <?php echo $_SESSION['address']; ?></p>
                    <p class="mb-1"><strong>Remaining Sessions:</strong> <?php echo $_SESSION['remaining']; ?></p>
                </div>
            </div>
        </div>

        <!-- Announcement Card -->
        <div class="col-md-4 d-flex">
            <div class="card h-75 w-100 d-flex flex-column">
                <h5 class="card-header text-white bg-primary"><i class="fa-solid fa-bullhorn"></i> Announcement</h5>
                <div class="card-body flex-grow-1">
                    <?php if (!empty($announce)) : ?>
                        <?php foreach ($announce as $row) : ?>
                            <p><strong><?php echo $row['admin_name'] . " | " . $row['date']; ?></strong></p>
                            <div class="card bg-light p-2">
                                <p><?php echo $row['message']; ?></p>
                            </div>
                            <hr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p>No announcements available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>


        <!-- Rules and Regulations Card -->
        <div class="col-md-4 d-flex">
            <div class="card h-75 w-100 d-flex flex-column">
                <h5 class="card-header text-white bg-primary">Rules and Regulations</h5>
                <div class="card-body flex-grow-1">
                    <h5 class="text-center"><strong>University of Cebu</strong></h5>
                    <p class="text-center"><strong>COLLEGE OF INFORMATION & COMPUTER STUDIES</strong></p>
                    <p><strong>LABORATORY RULES AND REGULATIONS</strong></p>
                    <ul>
                        <li>Maintain silence and discipline in the lab.</li>
                        <li>No games, unauthorized browsing, or downloads.</li>
                        <li>Do not tamper with computer settings or files.</li>
                        <li>Respect computer time limits.</li>
                        <li>No eating, drinking, or smoking inside the lab.</li>
                        <li>Follow instructor seating arrangements.</li>
                    </ul>
                    <p><strong>Disciplinary Actions:</strong></p>
                    <ul>
                        <li>First Offense: Suspension from lab sessions.</li>
                        <li>Second Offense: Heavier disciplinary action.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
  if (<?php echo isset($_SESSION['login_success']) && $_SESSION['login_success'] ? 'true' : 'false'; ?>) {
    Swal.fire({
      title: "Successful Login!",
      text: "Welcome! <?php echo $_SESSION["name"]; ?>",
      icon: "success"
    });
    <?php $_SESSION['login_success'] = false; // Reset the flag ?>
  }
</script>

</body>

</html>