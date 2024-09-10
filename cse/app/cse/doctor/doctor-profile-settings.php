<?php
session_start();
$email = $_SESSION['email'];
include "../dbconfig/db.php";

// Fonction pour gérer le téléchargement de fichiers
function handleFileUpload($fileInputName, $columnName) {
    global $conn, $email;
    if (is_uploaded_file($_FILES[$fileInputName]["tmp_name"])) {
        // Vérification du type de fichier
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES[$fileInputName]["type"], $allowedTypes)) {
            // Vérification de la taille du fichier (limite à 2MB)
            if ($_FILES[$fileInputName]["size"] <= 2097152) {
                $file = addslashes(file_get_contents($_FILES[$fileInputName]["tmp_name"]));
                $query = "UPDATE doctor SET $columnName='$file' WHERE Email='$email'";
                if (mysqli_query($conn, $query)) {
                    echo '<script>alert("Fichier inséré avec succès dans la base de données.")</script>';
                } else {
                    echo '<script>alert("Erreur lors de l\'insertion du fichier : ' . mysqli_error($conn) . '")</script>';
                }
            } else {
                echo '<script>alert("Le fichier dépasse la taille maximale de 2MB.")</script>';
            }
        } else {
            echo '<script>alert("Type de fichier non autorisé.")</script>';
        }
    } else {
        echo '<script>alert("Aucun fichier sélectionné.")</script>';
    }
}

// Gestion du téléchargement de l'image
if (isset($_POST["insert"])) {
    handleFileUpload("image", "Image");
}

// Gestion du téléchargement du document de preuve
if (isset($_POST["insertproof"])) {
    handleFileUpload("imaged", "Proof");
}

// Récupération des détails du docteur
$query = mysqli_query($conn, "SELECT * FROM doctor WHERE Email='$email'");
$rows = mysqli_fetch_assoc($query);

if ($rows) {
    $name = isset($rows['Name']) ? htmlspecialchars($rows['Name']) : '';
    $contact = isset($rows['Contact']) ? htmlspecialchars($rows['Contact']) : '';
    $special = isset($rows['Special']) ? htmlspecialchars($rows['Special']) : '';
    $gender = isset($rows['Gender']) ? htmlspecialchars($rows['Gender']) : '';
    $dob = isset($rows['DOB']) ? htmlspecialchars($rows['DOB']) : '';
    $bio = isset($rows['BIO']) ? htmlspecialchars($rows['BIO']) : '';
    $service = isset($rows['Service']) ? htmlspecialchars($rows['Service']) : '';
    $clinicName = isset($rows['ClinicName']) ? htmlspecialchars($rows['ClinicName']) : '';
    $clinicAddress = isset($rows['ClinicAddress']) ? htmlspecialchars($rows['ClinicAddress']) : '';
    $city = isset($rows['City']) ? htmlspecialchars($rows['City']) : '';
    $state = isset($rows['State']) ? htmlspecialchars($rows['State']) : '';
    $country = isset($rows['Country']) ? htmlspecialchars($rows['Country']) : '';
    $pincode = isset($rows['Pincode']) ? htmlspecialchars($rows['Pincode']) : '';
}

// Gestion de la mise à jour des informations de profil
if (isset($_POST['save_changes'])) {
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $bio = $_POST['biography'];
    $service = $_POST['services'];
    $special = $_POST['specialist'];
    $clinicName = $_POST['clinic_name'];
    $clinicAddress = $_POST['clinic_address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $country = $_POST['country'];
    $pincode = $_POST['pincode'];

    $sql = "UPDATE doctor SET Gender=?, DOB=?, BIO=?, Service=?, Special=?, ClinicName=?, ClinicAddress=?, City=?, State=?, Country=?, Pincode=? WHERE Email=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Échec de la préparation : ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("ssssssssssss", $gender, $dob, $bio, $service, $special, $clinicName, $clinicAddress, $city, $state, $country, $pincode, $email);
    if ($stmt->execute()) {
        echo '<script>alert("Profil mis à jour avec succès.")</script>';
    } else {
        echo '<script>alert("Erreur lors de la mise à jour du profil : ' . htmlspecialchars($stmt->error) . '")</script>';
    }
    $stmt->close();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Doctor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <script src="../assets/js/removebanner.js"></script>
    <link href="../assets/img/favicon.png" rel="icon">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../assets/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="../assets/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css">
    <link rel="stylesheet" href="../assets/plugins/dropzone/dropzone.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>

<body>



    <div class="main-wrapper">
        <div>
            <span>
                <div class="translate" id="google_translate_element"></div>
                <script type="text/javascript">
                    function googleTranslateElementInit() {
                        new google.translate.TranslateElement({ pageLanguage: 'en' }, 'google_translate_element');
                    }
                </script>
                <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
            </span>
        </div>
        
        <div class="breadcrumb-bar">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-12 col-12">
                        <nav aria-label="breadcrumb" class="page-breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="doctor-dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Profile Settings</li>
                            </ol>
                        </nav>
                        <h2 class="breadcrumb-title">Profile Settings</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-5 col-lg-4 col-xl-3 theiaStickySidebar">
                        <div class="profile-sidebar">
                            <div class="widget-profile pro-widget-content">
                                <div class="profile-info-widget">
                                    <a href="#" class="booking-doc-img">
                                        
                                    <?php 
                                        $image = isset($rows['Image']) ? $rows['Image'] : ''; // Assurez-vous que $rows['Image'] n'est pas null
                                        $encodedImage = base64_encode($image);
                                        echo '<img src="data:image/jpeg;base64,' . $encodedImage . '" class="img-thumnail" />';
                                    ?>
                                    </a>
                                    <div class="profile-det-info">
                                        <h3><?php echo htmlspecialchars($name); ?></h3>
                                        <div class="patient-details">
                                            <h5 class="mb-0"><?php echo htmlspecialchars($special); ?></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dashboard-widget">
                                <nav class="dashboard-menu">
                                    <ul>
                                        <li>
                                            <a href="doctor-dashboard.php">
                                                <i class="fas fa-columns"></i>
                                                <span>Dashboard</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="appointments.php">
                                                <i class="fas fa-calendar-check"></i>
                                                <span>Appointments</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="my-patients.php">
                                                <i class="fas fa-user-injured"></i>
                                                <span>My Patients</span>
                                            </a>
                                        </li>
                                        <li class="active">
                                            <a href="doctor-profile-settings.php">
                                                <i class="fas fa-user-cog"></i>
                                                <span>Profile Settings</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="doctor-change-password.php">
                                                <i class="fas fa-lock"></i>
                                                <span>Change Password</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="../index.php">
                                                <i class="fas fa-sign-out-alt"></i>
                                                <span>Logout</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7 col-lg-8 col-xl-9">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Basic Information</h4>
                                <div class="row form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="change-avatar">
                                                <div class="profile-img">
                                                    <?php
                                                        $image = isset($rows['Image']) ? $rows['Image'] : ''; // Assurez-vous que $rows['Image'] n'est pas null
                                                        $encodedImage = base64_encode($image);
                                                        echo '<img src="data:image/jpeg;base64,' . $encodedImage . '" class="img-thumnail" />';
                                                    ?>
                                                </div>
                                                <form method="POST" enctype="multipart/form-data">
                                                    <div class="upload-img">
                                                        <div class="change-photo-btn">
                                                            <span><i></i> Select Photo</span>
                                                            <input type="file" class="upload" name="image" id="image">
                                                        </div>
                                                        <input type="submit" name="insert" class="btn btn-primary" value="Upload">
                                                    </div>
                                                </form>
                                                <form method="POST" enctype="multipart/form-data">
                                                    <div class="upload-img">
                                                        <div class="change-photo-btn">
                                                            <span><i></i> Select Proof Document</span>
                                                            <input type="file" class="upload" name="imaged" id="imaged">
                                                        </div>
                                                        <input type="submit" name="insertproof" class="btn btn-primary" value="Upload Proof">
                                                    </div>
                                                </form>
                                                <small class="form-text text-muted">Allowed JPG, GIF or PNG. Max size of 2MB</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($name); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($email); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Phone Number</label>
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($contact); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Specialist</label>
                                            <input type="text" class="form-control" name="specialist" value="<?php echo htmlspecialchars($special); ?>"readonly>
                                        </div>
                                    </div>
                                </div>
                                <form method="POST">
                                    <div class="row form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Gender</label>
                                                <select class="form-control select" name="gender">
                                                    <option value="Male" <?php if ($gender == "Male") echo "selected"; ?>>Male</option>
                                                    <option value="Female" <?php if ($gender == "Female") echo "selected"; ?>>Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Date of Birth</label>
                                                <input type="date" class="form-control" name="dob" value="<?php echo htmlspecialchars($dob); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Biography</label>
                                                <textarea class="form-control" name="biography"><?php echo htmlspecialchars($bio); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Services</label>
                                                <input type="text" class="form-control" name="services" value="<?php echo htmlspecialchars($service); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Clinic Name</label>
                                                <input type="text" class="form-control" name="clinic_name" value="<?php echo htmlspecialchars($clinicName); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Clinic Address</label>
                                                <input type="text" class="form-control" name="clinic_address" value="<?php echo htmlspecialchars($clinicAddress); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>City</label>
                                                <input type="text" class="form-control" name="city" value="<?php echo htmlspecialchars($city); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>State</label>
                                                <input type="text" class="form-control" name="state" value="<?php echo htmlspecialchars($state); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Country</label>
                                                <input type="text" class="form-control" name="country" value="<?php echo htmlspecialchars($country); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Pincode</label>
                                                <input type="text" class="form-control" name="pincode" value="<?php echo htmlspecialchars($pincode); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="submit-section">
                                        <button type="submit" name="save_changes" class="btn btn-primary submit-btn">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Education</h4>
                                <div class="education-info">
                                    <div class="row form-row education-cont">
                                        <div class="col-12 col-md-10 col-lg-11">
                                            <div class="row form-row">
                                                <div class="col-12 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label>Degree</label>
                                                        <input type="text" class="form-control" value="M.B.B.S">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label>College/Institute</label>
                                                        <input type="text" class="form-control" value="Nairobi University">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label>Year of Completion</label>
                                                        <input type="text" class="form-control" value="2010">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-2 col-lg-1">
                                            <a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="add-more">
                                    <a href="#" class="add-education"><i class="fa fa-plus-circle"></i> Add More</a>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Experience</h4>
                                <div class="experience-info">
                                    <div class="row form-row experience-cont">
                                        <div class="col-12 col-md-10 col-lg-11">
                                            <div class="row form-row">
                                                <div class="col-12 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label>Hospital Name</label>
                                                        <input type="text" class="form-control" value="Kenyatta National Hospital">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label>From</label>
                                                        <input type="text" class="form-control" value="2010">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label>To</label>
                                                        <input type="text" class="form-control" value="2015">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-2 col-lg-1">
                                            <a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="add-more">
                                    <a href="#" class="add-experience"><i class="fa fa-plus-circle"></i> Add More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="footer-bottom">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <div class="copyright-text">
                                <p class="mb-0">&copy; 2024 Doccure. All rights reserved.</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <div class="policy-menu">
                                <ul>
                                    <li><a href="term-condition.html">Terms and Conditions</a></li>
                                    <li><a href="privacy-policy.html">Policy</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    
    <script src="../assets/js/jquery-3.5.1.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js"></script>
    <script src="../assets/plugins/select2/js/select2.min.js"></script>
    <script src="../
