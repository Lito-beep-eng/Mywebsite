<<<<<<< HEAD
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

if(isset($_POST['submit'])){

    /* ================= PASSWORD CHECK ================= */
    if($_POST['password'] !== $_POST['confirm_password']){
        die(" Passwords do not match");
    }

    /* ================= STEP 1 ================= */
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $suffix = $_POST['suffix'];
    $gender = $_POST['gender'];
    $birthdate = $_POST['birthdate'];
    $birthplace = $_POST['birthplace'];
    $civilstatus = $_POST['civilstatus'];
    $nationality = $_POST['nationality'];

    /* ================= STEP 2 ================= */
    $house_no = $_POST['house_no'];
    $street = $_POST['street'];
    $purok = $_POST['purok'];
    $years_residency = $_POST['years_residency'];

    /* ================= STEP 3 ================= */
    $head_family = $_POST['head_family'];
    $relationship = $_POST['relationship'];
    $household_size = $_POST['household_size'];

    /* ================= STEP 4 ================= */
    $occupation = $_POST['occupation'];
    $employer = $_POST['employer'];
    $education = $_POST['education'];

    /* ================= STEP 5 ================= */
    $id_type = $_POST['id_type'];
    $id_number = $_POST['id_number'];

    /* ================= STEP 6 ================= */
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    /* ================= CHECK EXISTING USER ================= */
    $check = $conn->prepare(
        "SELECT id FROM residents WHERE username=? OR email=?"
    );
    $check->execute([$username, $email]);

    if($check->rowCount() > 0){
       echo "Username or email already exists. Please choose another.";
       exit();
    }

    /* ================= FILE UPLOAD ================= */

    if(!is_dir("uploads")){
        mkdir("uploads", 0777, true);
    }

    // ID IMAGE
    $id_image = "";
    if(!empty($_FILES['id_image']['name'])){
        $id_image = time() . "_" . $_FILES['id_image']['name'];
        move_uploaded_file(
            $_FILES['id_image']['tmp_name'],
            "uploads/" . $id_image
        );
    }

    // 2x2 PHOTO
    $photo = "";
    if(!empty($_FILES['photo']['name'])){
        $photo = time() . "_" . $_FILES['photo']['name'];
        move_uploaded_file(
            $_FILES['photo']['tmp_name'],
            "uploads/" . $photo
        );
    }

    /* ================= INSERT ================= */

    $sql = "INSERT INTO residents (
        firstname, middlename, lastname, suffix,
        gender, birthdate, birthplace, civilstatus, nationality,
        house_no, street, purok, years_residency,
        head_family, relationship, household_size,
        occupation, employer, education,
        id_type, id_number, id_image, photo,
        mobile, email, username, password
    ) VALUES (
        ?, ?, ?, ?,
        ?, ?, ?, ?, ?,
        ?, ?, ?, ?,
        ?, ?, ?,
        ?, ?, ?,
        ?, ?, ?, ?,
        ?, ?, ?, ?
    )";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        $firstname, $middlename, $lastname, $suffix,
        $gender, $birthdate, $birthplace, $civilstatus, $nationality,
        $house_no, $street, $purok, $years_residency,
        $head_family, $relationship, $household_size,
        $occupation, $employer, $education,
        $id_type, $id_number, $id_image, $photo,
        $mobile, $email, $username, $password
    ]);

    /* ================= SUCCESS ================= */
    
    header("Location: Landingpage.php?registered=1");
    echo "Registration successful!";
    exit();
}
=======
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

if(isset($_POST['submit'])){

    /* ================= PASSWORD CHECK ================= */
    if($_POST['password'] !== $_POST['confirm_password']){
        die(" Passwords do not match");
    }

    /* ================= STEP 1 ================= */
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $suffix = $_POST['suffix'];
    $gender = $_POST['gender'];
    $birthdate = $_POST['birthdate'];
    $birthplace = $_POST['birthplace'];
    $civilstatus = $_POST['civilstatus'];
    $nationality = $_POST['nationality'];

    /* ================= STEP 2 ================= */
    $house_no = $_POST['house_no'];
    $street = $_POST['street'];
    $purok = $_POST['purok'];
    $years_residency = $_POST['years_residency'];

    /* ================= STEP 3 ================= */
    $head_family = $_POST['head_family'];
    $relationship = $_POST['relationship'];
    $household_size = $_POST['household_size'];

    /* ================= STEP 4 ================= */
    $occupation = $_POST['occupation'];
    $employer = $_POST['employer'];
    $education = $_POST['education'];

    /* ================= STEP 5 ================= */
    $id_type = $_POST['id_type'];
    $id_number = $_POST['id_number'];

    /* ================= STEP 6 ================= */
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    /* ================= CHECK EXISTING USER ================= */
    $check = $conn->prepare(
        "SELECT id FROM residents WHERE username=? OR email=?"
    );
    $check->execute([$username, $email]);

    if($check->rowCount() > 0){
       echo "Username or email already exists. Please choose another.";
       exit();
    }

    /* ================= FILE UPLOAD ================= */

    if(!is_dir("uploads")){
        mkdir("uploads", 0777, true);
    }

    // ID IMAGE
    $id_image = "";
    if(!empty($_FILES['id_image']['name'])){
        $id_image = time() . "_" . $_FILES['id_image']['name'];
        move_uploaded_file(
            $_FILES['id_image']['tmp_name'],
            "uploads/" . $id_image
        );
    }

    // 2x2 PHOTO
    $photo = "";
    if(!empty($_FILES['photo']['name'])){
        $photo = time() . "_" . $_FILES['photo']['name'];
        move_uploaded_file(
            $_FILES['photo']['tmp_name'],
            "uploads/" . $photo
        );
    }

    /* ================= INSERT ================= */

    $sql = "INSERT INTO residents (
        firstname, middlename, lastname, suffix,
        gender, birthdate, birthplace, civilstatus, nationality,
        house_no, street, purok, years_residency,
        head_family, relationship, household_size,
        occupation, employer, education,
        id_type, id_number, id_image, photo,
        mobile, email, username, password
    ) VALUES (
        ?, ?, ?, ?,
        ?, ?, ?, ?, ?,
        ?, ?, ?, ?,
        ?, ?, ?,
        ?, ?, ?,
        ?, ?, ?, ?,
        ?, ?, ?, ?
    )";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        $firstname, $middlename, $lastname, $suffix,
        $gender, $birthdate, $birthplace, $civilstatus, $nationality,
        $house_no, $street, $purok, $years_residency,
        $head_family, $relationship, $household_size,
        $occupation, $employer, $education,
        $id_type, $id_number, $id_image, $photo,
        $mobile, $email, $username, $password
    ]);

    /* ================= SUCCESS ================= */
    
    header("Location: Landingpage.php?registered=1");
    echo "Registration successful!";
    exit();
}
>>>>>>> 3780024 (update)
?>