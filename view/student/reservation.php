<?php
require_once '../asset/navbar_student.php';


 // Establish database connection
 $con = mysqli_connect('localhost', 'root', '', 'ccs_system');
 if ($con === false) {
     die("Error: Could not connect to the database. " . mysqli_connect_error());
 }

 if (isset($_POST["submitReserve"])) {
     $programming = $_POST["purpose"];
     $selected_lab = $_POST["lab"];
     // Sanitize and escape user input
     $lab = mysqli_real_escape_string($con, $selected_lab);
     // Construct the SQL query safely
     $sentence = "lab_" . $lab;
     $sqlTable = "SELECT pc_id FROM student_pc WHERE `$sentence` = '1'";
     // Execute the query
     $result = mysqli_query($con, $sqlTable);
 }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reservation</title>



</head>

<body>

<div class="container">
    <h4 class="text-center my-4">Reservation</h4>

    <form action="Reservation.php" method="POST">
        <div class="row g-3">
            <!-- Left Column -->
            <div class="col-md-6">
                <label for="id" class="form-label">ID Number</label>
                <input id="id" name="id_number" type="text" value="<?php echo $_SESSION['id_number'] ?>" readonly class="form-control">

                <label for="name" class="form-label mt-3">Student Name</label>
                <input id="name" name="studentName" type="text" value="<?php echo  $_SESSION['name'] ?>" readonly class="form-control">

                <label for="purposes" class="form-label mt-3">Purpose</label>
                <select name="purpose" id="purposes" class="form-select" required>
                    <option value="C Programming" <?php if($programming == "C Programming") echo 'selected'; ?>>C Programming</option>
                    <option value="Java Programming" <?php if($programming == "Java Programming") echo 'selected'; ?>>Java Programming</option>
                    <option value="C# Programming" <?php if($programming == "C# Programming") echo 'selected'; ?>>C# Programming</option>
                    <option value="Php Programming" <?php if($programming == "Php Programming") echo 'selected'; ?>>Php Programming</option>
                    <option value="ASP.Net Programming" <?php if($programming == "ASP.Net Programming") echo 'selected'; ?>>ASP.Net Programming</option>
                </select>

                <label for="lab" class="form-label mt-3">Lab</label>
                <select name="lab" id="lab" class="form-select" required>
                    <option value="524" <?php if($selected_lab == "524") echo 'selected'; ?>>524</option>
                    <option value="526" <?php if($selected_lab == "526") echo 'selected'; ?>>526</option>
                    <option value="528" <?php if($selected_lab == "528") echo 'selected'; ?>>528</option>
                    <option value="530" <?php if($selected_lab == "530") echo 'selected'; ?>>530</option>
                    <option value="542" <?php if($selected_lab == "542") echo 'selected'; ?>>542</option>
                    <option value="Mac" <?php if($selected_lab == "Mac") echo 'selected'; ?>>Mac Laboratory</option>
                </select>
            </div>

            <!-- Right Column -->
            <div class="col-md-6">
                <?php if ($result): ?>
                    <label for="pc_number" class="form-label">Available PC</label>
                    <select name="pc_number" id="pc_number" class="form-select">
                        <?php foreach($result as $row): ?>
                            <option value="<?php echo $row['pc_id']; ?>"><?php echo $row['pc_id']; ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>

                <label for="time" class="form-label">Time In</label>
                <input class="form-control" type="time" id="time" name="time" required>

                <label for="date" class="form-label mt-3">Date</label>
                <input class="form-control" type="date" id="date" name="date" required>

                <label for="remaining" class="form-label mt-3">Remaining Session</label>
                <input id="remaining" type="text" value="<?php echo $_SESSION['remaining'] ?>" readonly class="form-control">
            </div>
        </div>

        <!-- Buttons -->
        <div class="d-flex justify-content-end mt-4">
            <button type="submit" name="submitReserve" class="btn btn-primary me-2">Submit</button>
            <button type="submit" name="reserve_user" class="btn btn-success">Reserve</button>
        </div>
    </form>
</div>


    <script>
        function updateHiddenField() {
            var selectedLab = document.getElementById("lab").value;
            document.getElementById("lab2").value = selectedLab;
        }
    </script>


</body>

</html>