<?php
mysqli_report(MYSQLI_REPORT_OFF);
$conn = mysqli_connect("localhost", "root", "", "student_db");
if (!$conn) {
    die("Connection failed");
}
$msg = "";
$edit = null;
if (isset($_POST['add_student'])) {
    $roll = $_POST['roll'];
    $name = $_POST['name'];
    $year = $_POST['year'];
    $stmt = mysqli_prepare($conn, "INSERT INTO students (roll_no, name, academic_year) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iss", $roll, $name, $year);
    if (mysqli_stmt_execute($stmt)) {
        $msg = "Student added";
    } 
}
if (isset($_POST['add_subject'])) {
    $roll = $_POST['roll'];
    $subject = $_POST['subject'];
    $stmt = mysqli_prepare($conn, "INSERT INTO subjects (roll_no, subject_name) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "is", $roll, $subject);
    if (mysqli_stmt_execute($stmt)) {
        $msg = "Subject added";
    } 
}
if (isset($_POST['update_student'])) {
    $old_roll = $_POST['old_roll'];
    $roll = $_POST['roll'];
    $name = $_POST['name'];
    $year = $_POST['year'];
    $stmt = mysqli_prepare($conn, "UPDATE students SET roll_no = ?, name = ?, academic_year = ? WHERE roll_no = ?");
    mysqli_stmt_bind_param($stmt, "issi", $roll, $name, $year, $old_roll);
    if (mysqli_stmt_execute($stmt)) {
        $msg = "Student updated";
    } 
}
if (isset($_GET['delete'])) {
    $roll = $_GET['delete'];
    $stmt = mysqli_prepare($conn, "DELETE FROM students WHERE roll_no = ?");
    mysqli_stmt_bind_param($stmt, "i", $roll);
    mysqli_stmt_execute($stmt);
    $msg = "Student deleted";
}
if (isset($_GET['edit'])) {
    $roll = $_GET['edit'];
    $stmt = mysqli_prepare($conn, "SELECT * FROM students WHERE roll_no = ?");
    mysqli_stmt_bind_param($stmt, "i", $roll);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $edit = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE html>
<html >
<head>
    <title>Student Management System</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 6px 10px; }
        .msg { color: green; }
    </style>
</head>
<body >
<h2 style="text-align: center;">Student Management System</h2>
<?php if ($edit) { ?>
    <h3>Edit Student</h3>
    <form method="post" action="index.php" >
        <input type="hidden" name="old_roll" value="<?php echo $edit['roll_no']; ?>">
        Roll No: <input type="number" name="roll" value="<?php echo $edit['roll_no']; ?>" required><br><br>
        Name: <input type="text" name="name" value="<?php echo htmlspecialchars($edit['name']); ?>" required><br><br>
        Academic Year: <input type="text" name="year" value="<?php echo htmlspecialchars($edit['academic_year']); ?>" required><br><br>
        <input type="submit" name="update_student" value="Update Student">
        <a href="index.php">Cancel</a>
    </form>
<?php } else { ?>
    <h3>Add Student</h3>
    <form method="post" action="index.php">
        Roll No: <input type="number" name="roll" required><br><br>
        Name: <input type="text" name="name" required><br><br>
        Academic Year: <input type="text" name="year" placeholder="3rd year" required><br><br>
        <input type="submit" name="add_student" value="Add Student">
    </form>
<?php } ?>


<h3>Add Subject</h3>
<form method="post" action="index.php">
    Roll No: <input type="number" name="roll" required><br><br>
    Subject: <input type="text" name="subject" required><br><br>
    <input type="submit" name="add_subject" value="Add Subject">
</form>
<h3>All Students</h3>
<table style="text-align: center;">
    <tr>
        <th>Roll No</th>
        <th>Name</th>
        <th>Subjects</th>
        <th>Academic Year</th>
        <th>Action</th>
    </tr>
    <?php
    $sql = "SELECT students.roll_no, students.name, students.academic_year,
            GROUP_CONCAT(subjects.subject_name SEPARATOR ', ') AS subjects
            FROM students
            LEFT JOIN subjects ON students.roll_no = subjects.roll_no
            GROUP BY students.roll_no, students.name, students.academic_year
            ORDER BY students.roll_no";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $subject = $row['subjects'];
        if ($subject == "") {
            $subject = "-";
        }
    ?>
    <tr>
        <td><?php echo $row['roll_no']; ?></td>
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td><?php echo htmlspecialchars($subject); ?></td>
        <td><?php echo htmlspecialchars($row['academic_year']); ?></td>
        <td>
            <a href="index.php?edit=<?php echo $row['roll_no']; ?>">Edit</a> |
            <a href="index.php?delete=<?php echo $row['roll_no']; ?>" onclick="return confirm('Delete this student?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>
</body>
</html>
