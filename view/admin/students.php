<?php
include '../../includes/navbar_admin.php';

$listPerson = retrieve_students();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Information</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <!-- DataTables CSS -->
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <!-- SweetAlert2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  <!-- Custom Styles -->
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    h1 {
      color: #007bff;
      font-weight: 600;
      margin-top: 2rem;
    }

    .btn-primary {
      background-color: #007bff;
      border: none;
      padding: 0.5rem 1.5rem;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
      background-color: #0d3a6e;
    }

    .btn-danger {
      background-color: #dc3545;
      border: none;
      padding: 0.5rem 1.5rem;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }

    .btn-danger:hover {
      background-color: #bb2d3b;
    }

    .table {
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .table thead th {
      background-color: #144c94;
      color: white;
      font-weight: 600;
    }

    .table tbody tr {
      transition: background-color 0.3s ease;
    }

    .table tbody tr:hover {
      background-color: #f1f1f1;
    }

    .actions {
      display: flex;
      gap: 0.5rem;
      justify-content: center;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1 class="text-center mb-4">Students Information</h1>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-center gap-3 mb-4">
      <a class="btn btn-primary" href="Add.php">
        <i class="fas fa-plus me-2"></i>Add Students
      </a>
      <button class="btn btn-danger" id="resetButton">
        <i class="fas fa-sync-alt me-2"></i>Reset All Session
      </button>
    </div>

    <!-- Table -->
    <div class="table-responsive">
      <table id="example" class="table table-striped table-hover">
        <thead>
          <tr>
            <th>ID Number</th>
            <th>Name</th>
            <th>Year Level</th>
            <th>Course</th>
            <th>Remaining Session</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($listPerson as $person) : ?>
            <tr>
              <td><?php echo $person['id_number']; ?></td>
              <td><?php echo $person['firstName'] . " " . $person['middleName'] . ". " . $person['lastName']; ?></td>
              <td><?php echo $person['yearLevel']; ?></td>
              <td><?php echo $person['course']; ?></td>
              <td><?php echo $person['session']; ?></td>
              <td>
                <div class="actions">
                  <form action="Admin.php" method="POST" class="d-inline">
                    <input type="hidden" name="idNum" value="<?php echo $person['id_number']; ?>" />
                    <button type="submit" name="edit" class="btn btn-primary btn-sm">
                      <i class="fas fa-edit"></i>
                    </button>
                  </form>
                  <form action="Students.php" method="POST" class="d-inline delete-form">
                    <input type="hidden" name="idNum" value="<?php echo $person['id_number']; ?>" />
                    <button type="submit" name="deleteStudent" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this student?')">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // Initialize DataTable
    new DataTable('#example');

    // Reset Session Confirmation
    document.getElementById("resetButton").addEventListener("click", function() {
      Swal.fire({
        title: "Do you want to reset the session?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Reset",
        cancelButtonText: "Cancel",
      }).then((result) => {
        if (result.isConfirmed) {
          resetSession();
        }
      });
    });

    function resetSession() {
      fetch("Students.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: "reset=true",
        })
        .then((response) => {
          if (response.ok) {
            Swal.fire("Session Reset!", "", "success");
          } else {
            Swal.fire("Error", "Failed to reset session", "error");
          }
        })
        .catch(() => {
          Swal.fire("Error", "Failed to reset session", "error");
        });
    }
  </script>
</body>

</html>