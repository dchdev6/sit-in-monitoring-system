<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../includes/navbar_student.php';
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

    <!-- ✅ Form submits directly to api_student.php -->
    <form action="../../api/api_student.php" method="POST">
        <div class="row g-3">
            <!-- Left Column -->
            <div class="col-md-6">
                <label for="id" class="form-label">ID Number</label>
                <input id="id" name="id_number" type="text" value="<?php echo $_SESSION['id_number'] ?? ''; ?>" readonly class="form-control">

                <label for="name" class="form-label mt-3">Student Name</label>
                <input id="name" name="studentName" type="text" value="<?php echo $_SESSION['name'] ?? ''; ?>" readonly class="form-control">

                <label for="purposes" class="form-label mt-3">Purpose</label>
                <select name="purpose" id="purposes" class="form-select" required>
                    <option value="C Programming">C Programming</option>
                    <option value="Java Programming">Java Programming</option>
                    <option value="C# Programming">C# Programming</option>
                    <option value="Php Programming">Php Programming</option>
                    <option value="ASP.Net Programming">ASP.Net Programming</option>
                </select>

                <label for="lab" class="form-label mt-3">Lab</label>
                <select name="lab" id="lab" class="form-select" required>
                    <option value="524">524</option>
                    <option value="526">526</option>
                    <option value="528">528</option>
                    <option value="530">530</option>
                    <option value="542">542</option>
                    <option value="Mac">Mac Laboratory</option>
                </select>
            </div>

            <!-- Right Column -->
            <div class="col-md-6">
                <label for="time" class="form-label mt-3">Time In</label>
                <input class="form-control" type="time" id="time" name="time" required>

                <label for="date" class="form-label mt-3">Date</label>
                <input class="form-control" type="date" id="date" name="date" required>

                <label for="remaining" class="form-label mt-3">Remaining Session</label>
                <input id="remaining" type="text" value="<?php echo $_SESSION['remaining'] ?? ''; ?>" readonly class="form-control">
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-flex justify-content-end mt-4">
            <button type="submit" name="reserve_user" class="btn btn-success">Reserve</button>
        </div>
    </form>
</div>

</body>
</html>
