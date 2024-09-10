<?php
session_start();
$email = $_SESSION['email'];
$uname = $_SESSION['user'];
$name = $_SESSION['user'];
include "dbconfig/db.php";

// Afficher l'URL courante pour le débogage
$current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
echo "URL courante : $current_url<br>";

// Vérifiez si l'ID du patient est passé en paramètre
$patient_id = $_GET['patient_id'] ?? null;  // Récupérer l'ID du patient

if ($patient_id) {
    // Récupérer les détails du patient en utilisant l'ID
    $sql = "SELECT * FROM patient WHERE id='$patient_id'";
    $result = mysqli_query($conn, $sql);
    $patient_info = mysqli_fetch_assoc($result);

    if ($patient_info) {
        $selected_patient_email = $patient_info['Email'];
        echo "Email du patient récupéré : $selected_patient_email<br>";

        // Récupérer les détails du médecin connecté
        $query = mysqli_query($conn, "SELECT * FROM doctor WHERE Email='$email'");
        $rows = mysqli_fetch_assoc($query);
        $num = mysqli_num_rows($query);

        if ($num == 1) {
            $name = $rows['Name'];
            $contact = $rows['Contact'];
            $special = $rows['Special'];
            $dp = $rows['Image'];
        }

        // Récupérer les détails des rendez-vous de ce médecin
        $sql1 = "SELECT * FROM appointments WHERE DoctorMail='$email'";
        $result1 = mysqli_query($conn, $sql1);
        $num = mysqli_num_rows($result1);

        $patmail = [];
        $i = 0;

        // Collecte des emails des patients
        while ($row1 = mysqli_fetch_array($result1)) {
            $email = $row1['PatientMail'];
            if ($email !== null) {
                $patmail[$i] = $email;
                $i++;
            }
        }

        // Définir les médecins pour les liens de prescription
        $doctors = [];
        $query_doctors = mysqli_query($conn, "SELECT * FROM doctor");
        while ($row_doctor = mysqli_fetch_assoc($query_doctors)) {
            $doctors[] = [
                'doctor_id' => $row_doctor['DoctorID'],
                'doctor_name' => $row_doctor['Name']
            ];
        }

        // Extraction des informations du patient
        $pname = $patient_info['Name'];
        $pcontact = $patient_info['Contact'];
        $pdob = $patient_info['dob'];
        $patdp = $patient_info['Image'];
        $pbg = $patient_info['bgroup'];
        $paddress = $patient_info['address'];
        $pcity = $patient_info['city'];
        $pstate = $patient_info['state'];
        $pcountry = $patient_info['country'];
        $ppcode = $patient_info['pincode'];
        $age = getAge($pdob);
        $pdob = monthName($pdob);

    } else {
        echo "Aucun patient trouvé avec cet ID.";
        exit;
    }
} else {
    echo "ID du patient non spécifié.";
    exit;
}

function monthName($date) {
    list($day, $month, $year) = explode("/", $date);
    $dateObj = DateTime::createFromFormat('!m', $month);
    $monthName = $dateObj->format('F');
    $datewithname = $day . ', ' . $monthName . ' ' . $year;
    return $datewithname;
}

function getAge($birthday) {
    list($day, $month, $year) = explode("/", $birthday);
    $year_diff = date("Y") - $year;
    $month_diff = date("m") - $month;
    $day_diff = date("d") - $day;
    if ($day_diff < 0 && $month_diff == 0) $year_diff--;
    if ($day_diff < 0 && $month_diff < 0) $year_diff--;
    return $year_diff;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Patient Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link href="assets/img/favicon.png" rel="icon">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/html5shiv.min.js"></script>
    <script src="assets/js/respond.min.js"></script>
</head>
<body>
    <div class="main-wrapper">
        <header class="header">
            <nav class="navbar navbar-expand-lg header-nav">
                <div class="navbar-header">
                    <a id="mobile_btn" href="javascript:void(0);">
                        <span class="bar-icon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </a>
                    <a href="doctor/doctor-dashboard.php" class="navbar-brand logo">
                        <img src="assets/img/logo.png" class="img-fluid" alt="Logo">
                    </a>
                </div>
                <div>
                    <div>
                        <span>
                            <div class="translate" id="google_translate_element"></div>
                            <script type="text/javascript">
                                function googleTranslateElementInit() { new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');}
                            </script>
                            <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
                        </span>
                    </div>
                </div>
                <ul class="nav header-navbar-rht">
                    <li class="nav-item dropdown has-arrow logged-item">
                        <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                            <span class="user-img">
                                <?php echo '<img class="rounded-circle" src="data:image/jpeg;base64,'.base64_encode($dp).'" alt="'.htmlspecialchars($name, ENT_QUOTES, 'UTF-8').'"/>'; ?>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="user-header">
                                <div class="avatar avatar-sm">
                                    <?php echo '<img class="avatar-img rounded-circle" src="data:image/jpeg;base64,'.base64_encode($dp).'" alt="'.htmlspecialchars($name, ENT_QUOTES, 'UTF-8').'"/>'; ?>
                                </div>
                                <div class="user-text">
                                    <h6><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></h6>
                                    <p class="text-muted mb-0">Doctor</p>
                                </div>
                            </div>
                            <a class="dropdown-item" href="doctor-dashboard.php">Dashboard</a>
                            <a class="dropdown-item" href="doctor-profile-settings.php">Profile Settings</a>
                        </div>
                    </li>
                </ul>
            </nav>
        </header>

        <div class="breadcrumb-bar">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-12 col-12">
                        <nav aria-label="breadcrumb" class="page-breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="search.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Profile</li>
                            </ol>
                        </nav>
                        <h2 class="breadcrumb-title">Profile</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card widget-profile pat-widget-profile">
                    <div class="card-body">
                        <div class="pro-widget-content">
                            <div class="profile-info-widget">
                                <a href="../patient-profile.php" class="booking-doc-img">
                                    <?php
                                    // Préparer l'image
                                    $patdp = $patient_info['Image'] ?? null;
                                    $imageSrc = $patdp ? "data:image/jpeg;base64," . base64_encode($patdp) : 'path/to/default/image.jpg';
                                    ?>
                                    <img class="img-fluid" src="<?php echo htmlspecialchars($imageSrc, ENT_QUOTES, 'UTF-8'); ?>" class="img-thumbnail" alt="<?php echo htmlspecialchars($pname, ENT_QUOTES, 'UTF-8'); ?>"/>
                                </a>
                                <div class="profile-det-info">
                                    <h3><a><?php echo htmlspecialchars($pname, ENT_QUOTES, 'UTF-8'); ?></a></h3>
                                    <div class="patient-details">
                                        <h5><b>Patient Mail :</b> <?php echo htmlspecialchars($selected_patient_email, ENT_QUOTES, 'UTF-8'); ?></h5>
                                        <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($pcity, ENT_QUOTES, 'UTF-8'); ?>, <?php echo htmlspecialchars($pstate, ENT_QUOTES, 'UTF-8'); ?>,<br> <?php echo htmlspecialchars($pcountry, ENT_QUOTES, 'UTF-8'); ?></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="patient-info">
                            <ul>
                                <li>Phone <span><?php echo htmlspecialchars($pcontact, ENT_QUOTES, 'UTF-8'); ?></span></li>
                                <li>Age <span><?php echo htmlspecialchars($age, ENT_QUOTES, 'UTF-8'); ?></span></li>
                                <li>Blood Group <span><?php echo htmlspecialchars($pbg, ENT_QUOTES, 'UTF-8'); ?></span></li>
                            </ul>
                        </div>

                        <?php foreach ($doctors as $doc): ?>
                            <div class="doc-info-right">
                                <div class="clinic-booking">
                                    <a class="apt-btn" href="doctor/add_prescription.php?patient_id=<?php echo htmlspecialchars($patient_id, ENT_QUOTES, 'UTF-8'); ?>&doctor_id=<?php echo htmlspecialchars($doc['doctor_id'], ENT_QUOTES, 'UTF-8'); ?>">Ajouter une prescription</a>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        </div>

        <footer class="footer">
            <div class="footer-bottom">
                <div class="container-fluid">
                    <div class="copyright">
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="copyright-text">
                                    <p class="mb-0"><a href="templateshub.net"></a></p>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/moment.min.js"></script>
    <script src="assets/js/bootstrap-datetimepicker.min.js"></script>
    <script src="assets/plugins/theia-sticky-sidebar/ResizeSensor.js"></script>
    <script src="assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
