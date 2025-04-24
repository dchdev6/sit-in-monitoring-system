<?php
include __DIR__ . '/../backend/backend_admin.php';


loginAdmin();
//Object Student
class Student
{
  // Properties (attributes)
  public  $id;
  public  $name;
  public  $records;

  // Constructor method

  public function __construct($id, $name, $records)
  {
    $this->id = $id;
    $this->name = $name;
    $this->records = $records;
  }
}
//Delete Student
if (isset($_POST["deleteStudent"])) {
  $id = $_POST['idNum'];

  if (delete_student($id)) {
    echo '<script>alert("Delete Successful");</script>';
    echo '<script>window.location.href = "Students.php";</script>';
    exit();
  } else {
    echo '<script>alert("Delete Unsuccessful");</script>';
    echo '<script>window.location.href = "Students.php";</script>';
    exit();
  }
}


if (isset($_GET["search"])) {
  $search = $_GET["searchBar"];

  //Search Student Method
  $retrieve = search_student($search);

  if ($retrieve->num_rows > 0) {

    $user = $retrieve->fetch_assoc();
    $record = retrieve_student_session($user['id_number']);

    $student = new Student($user["id_number"], $user["firstName"] . " " . $user["middleName"] . " " . $user["lastName"], $record["session"]);


    $displayModal = true;
  } else {
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
              title: "No student found!"
            });</script>';
  }
}

// get the post records
if (isset($_POST["sitIn"])) {

  $idNum = $_POST['studentID'];
  $purpose = $_POST['purpose'];
  $lab = $_POST['lab'];
  $login = date("h:i:sa");

  $sesions = retrieve_student_session($idNum);


  if ($sesions["session"] == 0) {
    echo '<script>Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Student Session is 0!",
  
    });</script>';
  } else {

    //Check if the student is currently sit in
    $check = check_student_active($idNum);

    if ($check["sit_id"] != null) {
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
        title: "Student currently sit-in!"
      });</script>';
    } else {

      if (student_sit_in($idNum, $purpose, $lab, $login)) {
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
              title: "Sit-in successfully!"
            });</script>';
      }
    }
  }
}

//Edit Admin

if (isset($_POST["edit"])) {
  $_SESSION["editNum"] = $_POST['idNum'];
  echo '<script>';
  echo 'window.location.href = "Edit.php";';
  echo '</script>';
}

//Logout 



if (isset($_POST["logout"])) {
  session_start();

  $id = $_POST['idNum'];
  $sitId = $_POST['sitId'];
  $log = date("H:i:s");
  $logout = date('Y-m-d');
  $ses = $_POST["session"];
  $sitlab = $_POST["sitLab"];
  $newSession = max(0, $ses - 1);

  if (student_logout($id, $sitId, $log, $logout, $newSession)) {
      echo "<script>
          alert('Logout successful!');
          window.location.href = '../view/admin/viewrecords.php';
      </script>";
      exit();
  } else {
      echo "<script>
          alert('Logout failed. Check database logs.');
          window.history.back();
      </script>";
  }
}







if (isset($_POST["submitEdit"])) {
  $idNum = $_POST['idNumber'];
  $last_Name = $_POST['lName'];
  $first_Name = $_POST['fName'];
  $middle_Name = $_POST['mName'];
  $course_Level = $_POST['courseLevel'];
  $email = $_POST['email'];
  $course = $_POST['course'];
  $address = $_POST['address'];



  if (edit_student_admin($idNum, $last_Name, $first_Name, $middle_Name, $course_Level, $email, $course, $address)) {

    echo "<script>Swal.fire({
    title: 'Notification',
    text: 'Edit Profile Successfull',
    icon: 'success',
    showConfirmButton: false,
    timer: 1500
  });</script>";
  } else {

    echo "<script>Swal.fire({
    title: 'Notification',
    text: 'Error! Duplicate ID Number',
    icon: 'error',
    showConfirmButton: false,
    timer: 1500
  });</script>";
  }
}


if (isset($_POST["dateSubmit"])) {
  $date = $_POST["date"];
  $sql = get_date_report(filter_date($date));
} else {
  $sql = get_date_report(reset_date());
}
if (isset($_POST['resetSubmit'])) {

  $sql = get_date_report(reset_date());
}



// Register
if (isset($_POST["submitRegister"])) {
  // Enable detailed error reporting for debugging
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  
  // Start session if not already started
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
  
  // Get and sanitize form data
  $idNum = isset($_POST['idNumber']) ? trim($_POST['idNumber']) : '';
  $last_Name = isset($_POST['lName']) ? trim($_POST['lName']) : '';
  $first_Name = isset($_POST['fName']) ? trim($_POST['fName']) : '';
  $middle_Name = isset($_POST['mName']) ? trim($_POST['mName']) : '';
  $course_Level = isset($_POST['level']) ? trim($_POST['level']) : '';
  $passWord = isset($_POST['password']) ? $_POST['password'] : '';
  $email = isset($_POST['email']) ? trim($_POST['email']) : '';
  $course = isset($_POST['course']) ? trim($_POST['course']) : '';
  $address = isset($_POST['address']) ? trim($_POST['address']) : '';
  
  // Check for required fields
  if (empty($idNum) || empty($last_Name) || empty($first_Name) || 
      empty($course_Level) || empty($passWord) || empty($course)) {
    // If AJAX request, return error as JSON
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
      echo json_encode(['success' => false, 'message' => 'Missing required fields']);
      exit;
    } else {
      $_SESSION['registration_error'] = 'All required fields must be filled out.';
      header('Location: ../view/admin/students.php');
      exit;
    }
  }
  
  // Try to add the student
  $result = add_student($idNum, $last_Name, $first_Name, $middle_Name, $course_Level, $passWord, $email, $course, $address);
  
  if ($result === true) {
    // Success!
    $_SESSION['registration_success'] = true;
    
    // If AJAX request, return success JSON
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
      echo json_encode(['success' => true]);
      exit;
    } else {
      header('Location: ../view/admin/students.php');
      exit;
    }
  } else {
    // Failed
    $errorMessage = is_string($result) ? $result : 'Unable to add student. The ID may already be in use.';
    $_SESSION['registration_error'] = $errorMessage;
    
    // If AJAX request, return error as JSON
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
      echo json_encode(['success' => false, 'message' => $errorMessage]);
      exit;
    } else {
      header('Location: ../view/admin/students.php');
      exit;
    }
  }
}


if (isset($_POST['reset_password'])) {
  $new_password = $_POST['new_password'];
  $id = $_SESSION['id_number'];

  if (reset_password($new_password, $id)) {
    echo "<script>Swal.fire({
        title: 'Notification',
        text: 'Password Reset!',
        icon: 'success',
        showConfirmButton: false,
        timer: 1500
      });</script>";
  } else {
    echo "<script>Swal.fire({
        title: 'Notification',
        text: 'Error! Password did not change',
        icon: 'error',
        showConfirmButton: false,
        timer: 1500
      });";
  }
}
if (isset($_POST['post_announcement'])) {
  $message = $_POST['announcement_text'];
  $admin_name = $_SESSION['admin_name'];
  $date = date('Y-M-d');

  if (post_announcement($message, $admin_name, $date)) {
    echo "<script>Swal.fire({
        title: 'Notification',
        text: 'Announcement Posted!',
        icon: 'success',
        showConfirmButton: false,
        timer: 1500
      });</script>";
  }
}

if (isset($_POST['labSubmit'])) {
  $lab_final = "lab_" . $_POST['lab'];
  $current_lab = $_POST['lab'];
  $data = retrieve_pc($lab_final);
}
if (isset($_POST['submitAvail'])) {
  $pc1 = $_POST['pc'];
  $lab = $_POST['filter_lab'];


  $concat = ""; // Initialize an empty string to store concatenated values

  for ($i = 0; $i < count($pc1); $i++) {
    $concat .= $pc1[$i];

    // Add a comma after each element except for the last one
    if ($i < count($pc1) - 1) {
      $concat .= ",";
    }
  }


  if (available_pc($concat, $lab)) {
    echo "<script>Swal.fire({
        title: 'Notification',
        text: 'PC Available!',
        icon: 'success',
        showConfirmButton: false,
        timer: 1500
      });</script>";
  }
}
if (isset($_POST['submitDecline'])) {
  $pc1 = $_POST['pc'];
  $lab = $_POST['filter_lab'];


  $concat = ""; // Initialize an empty string to store concatenated values

  for ($i = 0; $i < count($pc1); $i++) {
    $concat .= $pc1[$i];

    // Add a comma after each element except for the last one
    if ($i < count($pc1) - 1) {
      $concat .= ",";
    }
  }
  if (used_pc($concat, $lab)) {
    echo "<script>Swal.fire({
        title: 'Notification',
        text: 'PC Not Available!',
        icon: 'success',
        showConfirmButton: false,
        timer: 1500
      });</script>";
  }
}

if(isset($_POST['accept_reservation'])){
  $reservation_id = $_POST['reservation_id'];
  $pc_number = $_POST['pc_number'];
  $lab = $_POST['lab'];
  $id_number = $_POST['id_number'];

  if(approve_reservation($reservation_id, $pc_number,$lab,$id_number )){
    echo "<script>Swal.fire({
      title: 'Notification',
      text: 'Approve Reservation!',
      icon: 'success',
      showConfirmButton: false,
      timer: 1500
    });</script>";
  }
}

if(isset($_POST['deny_reservation'])){
  $reservation_id = $_POST['reservation_id'];
  $pc_number = $_POST['pc_number'];
  $lab = $_POST['lab'];
  $id_number = $_POST['id_number'];

  if(decline_reservation($reservation_id, $pc_number,$lab,$id_number )){
    echo "<script>Swal.fire({
      title: 'Notification',
      text: 'Decline Reservation!',
      icon: 'success',
      showConfirmButton: false,
      timer: 1500
    });</script>";
  }
}



?>




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">



  <title>CCS | Home</title>

</head>

<body>



  <!-- Search Student Modal -->
<form action="Admin.php" method="GET">
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content rounded-lg shadow-lg border-0">
                <div class="modal-header bg-primary-50 border-0">
                    <h5 class="modal-title text-primary-800 font-semibold" id="exampleModalLabel">
                        <i class="fas fa-search me-2"></i>Search Student
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <h6 class="font-medium text-gray-700 mb-4 text-center">Enter Student ID or Name</h6>
                    
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        </div>
                        <input 
                            type="text" 
                            name="searchBar" 
                            placeholder="Search by ID number or name..." 
                            class="form-control bg-white border border-gray-300 text-gray-900 text-lg rounded-md pl-10 py-3 w-full focus:ring-primary-500 focus:border-primary-500"
                            autofocus
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 opacity-70">
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">Enter to search</span>
                        </div>
                    </div>
                    
                    <div class="mt-3 text-xs text-gray-500 px-1">
                        <p><i class="fas fa-info-circle mr-1"></i> Search by student ID number for exact match or name for related results.</p>
                    </div>
                </div>
                
                <div class="modal-footer flex justify-center border-0 bg-gray-50 rounded-b-lg">
                    <button type="button" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md transition duration-200 flex items-center" data-dismiss="modal">
                        <i class="fas fa-times-circle mr-2"></i>Cancel
                    </button>
                    <button type="submit" name="search" class="px-4 py-2 bg-[#0ea5e9] hover:bg-[#0284c7] text-white rounded-md transition duration-200 flex items-center shadow-sm">
                        <i class="fas fa-search mr-2"></i>Search Student
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    // Add animation when modal appears
    document.addEventListener('DOMContentLoaded', function() {
        const searchModal = document.getElementById('exampleModal');
        if (searchModal) {
            searchModal.addEventListener('show.bs.modal', function () {
                const modalContent = this.querySelector('.modal-content');
                modalContent.classList.add('animate__animated', 'animate__fadeInDown', 'animate__faster');
                
                // Focus the search input when modal opens
                setTimeout(() => {
                    this.querySelector('input[name="searchBar"]').focus();
                }, 300);
            });
            
            searchModal.addEventListener('hidden.bs.modal', function () {
                const modalContent = this.querySelector('.modal-content');
                modalContent.classList.remove('animate__animated', 'animate__fadeInDown', 'animate__faster');
            });
        }
    });
</script>


<!-- Modal -->
<form action="Admin.php" method="POST">
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-lg shadow-lg border-0">
                <div class="modal-header bg-primary-50 border-0">
                    <h5 class="modal-title text-primary-800 font-semibold" id="exampleModalLongTitle">
                        <i class="fas fa-laptop-code me-2"></i>Sit In Form
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <h6 class="font-medium text-gray-700 mb-4 text-center">Student Information</h6>
                    
                    <div class="space-y-3">
                        <div class="form-group row align-items-center">
                            <label for="id" class="col-sm-4 col-form-label text-sm font-medium text-gray-700">ID Number:</label>
                            <div class="col-sm-8">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    </div>
                                    <input id="id" name="studentID" type="text" value="<?php echo $student->id ?>" readonly 
                                        class="form-control bg-gray-50 border border-gray-300 text-gray-900 rounded-md pl-10 py-2" />
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group row align-items-center">
                            <label for="name" class="col-sm-4 col-form-label text-sm font-medium text-gray-700">Student Name:</label>
                            <div class="col-sm-8">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    </div>
                                    <input id="name" name="studentName" type="text" value="<?php echo $student->name ?>" readonly 
                                        class="form-control bg-gray-50 border border-gray-300 text-gray-900 rounded-md pl-10 py-2" />
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group row align-items-center">
                            <label for="purposes" class="col-sm-4 col-form-label text-sm font-medium text-gray-700">Purpose:</label>
                            <div class="col-sm-8">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    </div>
                                    <select name="purpose" id="purposes" 
                                        class="form-control bg-white border border-gray-300 text-gray-900 rounded-md pl-10 py-2 appearance-none">
                                        <option value="C-Programming">C Programming</option>
                                        <option value="Java Programming">Java Programming</option>
                                        <option value="C# Programming">C# Programming</option>
                                        <option value="Php Programming">PHP Programming</option>
                                        <option value="ASP.Net Programming">ASP.NET Programming</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group row align-items-center">
                            <label for="lab" class="col-sm-4 col-form-label text-sm font-medium text-gray-700">Lab:</label>
                            <div class="col-sm-8">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    </div>
                                    <select name="lab" id="lab" 
                                        class="form-control bg-white border border-gray-300 text-gray-900 rounded-md pl-10 py-2 appearance-none">
                                        <option value="524">524</option>
                                        <option value="526">526</option>
                                        <option value="528">528</option>
                                        <option value="530">530</option>
                                        <option value="542">542</option>
                                        <option value="Mac">Mac Laboratory</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group row align-items-center">
                            <label for="remaining" class="col-sm-4 col-form-label text-sm font-medium text-gray-700">Sessions Left:</label>
                            <div class="col-sm-8">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    </div>
                                    <input id="remaining" type="text" value="<?php echo $student->records ?>" readonly 
                                        class="form-control bg-gray-50 border border-gray-300 text-gray-900 rounded-md pl-10 py-2" />
                                    
                                    <?php if ($student->records <= 3): ?>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium <?php echo $student->records > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'; ?>">
                                            <i class="fas <?php echo $student->records > 0 ? 'fa-exclamation-triangle mr-1' : 'fa-times-circle mr-1'; ?>"></i>
                                            <?php echo $student->records > 0 ? 'Low' : 'None'; ?>
                                        </span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php if ($student->records <= 3): ?>
                                <p class="mt-1 text-xs text-gray-500 italic">
                                    <?php echo $student->records > 0 
                                        ? 'Student is low on available sessions.' 
                                        : 'Student has no available sessions left.'; ?>
                                </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer flex justify-center border-0 bg-gray-50 rounded-b-lg">
                    <button type="button" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md transition duration-200 flex items-center" data-bs-dismiss="modal">
                        <i class="fas fa-times-circle mr-2"></i>Cancel
                    </button>
                    <button type="submit" name="sitIn" class="px-4 py-2 bg-[#0ea5e9] hover:bg-[#0284c7] text-white rounded-md transition duration-200 flex items-center shadow-sm" <?php echo $student->records <= 0 ? 'disabled' : ''; ?>>
                        <i class="fas fa-sign-in-alt mr-2"></i>Proceed with Sit-In
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    // Add animation when modal appears
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('exampleModalCenter');
        if (modal) {
            modal.addEventListener('show.bs.modal', function () {
                const modalContent = this.querySelector('.modal-content');
                modalContent.classList.add('animate__animated', 'animate__fadeInDown', 'animate__faster');
            });
            
            modal.addEventListener('hidden.bs.modal', function () {
                const modalContent = this.querySelector('.modal-content');
                modalContent.classList.remove('animate__animated', 'animate__fadeInDown', 'animate__faster');
            });
        }
    });
</script>






  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.6/js/dataTables.bootstrap5.js"></script>
  <script src="https://cdn.datatables.net/2.0.6/js/dataTables.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.dataTables.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>


</body>

</html>
<script>
  <?php if ($displayModal) : ?>
    $(document).ready(function() {
      $('#exampleModalCenter').modal('show');
    });
  <?php endif; ?>
</script>

<?php
loginAdmin();
?>