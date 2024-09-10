<?php
session_start();
$email=$_SESSION['email'];
$uname=$_SESSION['user'];
$name=$_SESSION['user'];
include "../dbconfig/db.php";
$query = mysqli_query($conn,"select * from doctor where Email='$email'");
	$rows = mysqli_fetch_assoc($query);
	$num=mysqli_num_rows($query);
	if ($num == 1) {
		$name=$rows['Name'];
		$contact=$rows['Contact'];
		$special=$rows['Special'];
		$dp=$rows['Image'];
	} 
$sql1 = "Select * from appointments where DoctorMail='$email'";
$result1=mysqli_query($conn, $sql1);
$num=mysqli_num_rows($result1);
$i=0;
while($row1 = mysqli_fetch_array($result1)){
	//echo $row1['Name'];
	$patmail[$i]=$row1['PatientMail'];	
	$i++;
}
$patients=[];
$k=0;
for($i=0;$i<$num;$i++){
    if(!in_array($patmail[$i], $patients)){
        $patients[$k]=$patmail[$i];
        $k++;
    }
}

$num=count($patients);

$i=0;
while($i<$num){
    $sql = "SELECT * FROM patient WHERE Email='$patients[$i]'";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_array($result)){
        $pname[$i] = $row['Name'];
        $pcountry[$i] = $row['country'];
        $pcontact[$i] = $row['Contact'];
        $pdob[$i] = $row['dob'];
        $patdp[$i] = $row['Image'];
        $pbg[$i] = $row['bgroup'];        
        $paddress[$i] = $row['address'];
        $pcity[$i] = $row['city'];
        $pstate[$i] = $row['state'];
        $pcountry[$i] = $row['country'];
        $ppcode[$i] = $row['pincode'];    
        $age[$i] = getAge($pdob[$i]);
        $pdob[$i] = monthName($pdob[$i]);
        // Ajout de l'identifiant du patient
        $patient_id[$i] = $row['id']; // Assurez-vous que 'PatientID' est le nom correct de la colonne dans votre base de données
    }
    $i++;
}


function monthName($date){
	list($day, $month, $year) = explode("/", $date);
	$dateObj   = DateTime::createFromFormat('!m', $month);
	$monthName = $dateObj->format('F');
	$datewithname=$day.', '.$monthName.' '.$year;
	return $datewithname;

}
function getAge($birthday){
	list($day, $month, $year) = explode("/", $birthday);
	$year_diff  = date("Y") - $year;
	$month_diff = date("m") - $month;
	$day_diff   = date("d") - $day;
	if ($day_diff < 0 && $month_diff==0) $year_diff--;
	if ($day_diff < 0 && $month_diff < 0) $year_diff--;
	return $year_diff;
   }
?>
<!DOCTYPE html> 
<html lang="en">
	
<!-- Docter/my-patients.php  30 Nov 2019 04:12:09 GMT -->
<head>
		<meta charset="utf-8">
		<title>Doctor</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
		 <script src="../assets/js/removebanner.js"></script>
		<!-- Favicons -->
		<link href="../assets/img/favicon.png" rel="icon">
		
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
		
		<!-- Fontawesome CSS -->
		<link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
		<link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
		
		<!-- Main CSS -->
		<link rel="stylesheet" href="../assets/css/style.css">
		
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->
	
	</head>
	<body>

		<!-- Main Wrapper -->
		<div class="main-wrapper">
		
			<!-- Header -->
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
						
					</div>
					<div class="main-menu-wrapper">
						<div class="menu-header">
							
							<a id="menu_close" class="menu-close" href="javascript:void(0);">
								<i class="fas fa-times"></i>
							</a>
						</div>
						
					</div>	
					<div>
						<span>
							<div class="translate" id="google_translate_element"></div>

							<script type="text/javascript">
								function googleTranslateElementInit() {  new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');}
							</script>
							<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
						</span>
					</div>	 
					<ul class="nav header-navbar-rht">
						<li class="nav-item contact-item">
							<div class="header-contact-img">
								<i class="far fa-hospital"></i>							
							</div>
							<div class="header-contact-detail">
								<p class="contact-header">Contact</p>
								<p class="contact-info-header"><?php echo $contact;?></p>
							</div>
						</li>
						
						<!-- User Menu -->
					<li class="nav-item dropdown has-arrow logged-item">
							<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
								<span class="user-img">
								        <?php 
											 $image = isset($dp) ? $rows['Image'] : ''; // Assurez-vous que $rows['Image'] n'est pas null
											 $encodedImage = base64_encode($image);
											 echo '<img src="data:image/jpeg;base64,' .$encodedImage . '" class="img-thumnail"  alt='.$name.'/>';

										?>
								</span>
							</a>
							<div class="dropdown-menu dropdown-menu-right">
								<div class="user-header">
									<div class="avatar avatar-sm">
										<?php echo '<img class="avatar-img rounded-circle" src="data:image/jpeg;base64,'.base64_encode($dp).'"   alt='.$name.'/>  ';?>
									</div>
									<div class="user-text">
										<h6><?php echo $name;?></h6>
										<p class="text-muted mb-0">Doctor</p>
									</div>
								</div>
								<a class="dropdown-item" href="doctor-dashboard.php">Dashboard</a>
								<a class="dropdown-item" href="doctor-profile-settings.php">Profile Settings</a>
								
							</div>
						</li>
						<!-- /User Menu -->
						
					</ul>
				</nav>
			</header>
			<!-- /Header -->
			
			<!-- Breadcrumb -->
			<div class="breadcrumb-bar">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-md-12 col-12">
							<nav aria-label="breadcrumb" class="page-breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="doctor-dashboard.php">Home</a></li>
									<li class="breadcrumb-item active" aria-current="page">My Patients</li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title">My Patients</h2>
						</div>
					</div>
				</div>
			</div>
			<!-- /Breadcrumb -->
			
			<!-- Page Content -->
			<div class="content">
				<div class="container-fluid">

					<div class="row">
						<div class="col-md-5 col-lg-4 col-xl-3 theiaStickySidebar">
						
							<!-- Profile Sidebar -->
							<div class="profile-sidebar">
								<div class="widget-profile pro-widget-content">
									<div class="profile-info-widget">
										<a href="" class="booking-doc-img">
										<?php 
											 $image = isset($dp) ? $rows['Image'] : ''; // Assurez-vous que $rows['Image'] n'est pas null
											 $encodedImage = base64_encode($image);
											 echo '<img src="data:image/jpeg;base64,' .$encodedImage . '" class="img-thumnail"  alt='.$name.'/>';

										?>
										</a>
										<div class="profile-det-info">
											<h3><?php echo $uname;?></h3>
											
											<div class="patient-details">
												<h5 class="mb-0"><?php echo $special;?></h5>
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
											<li class="active">
												<a href="my-patients.php">
													<i class="fas fa-user-injured"></i>
													<span>My Patients</span>
												</a>
											</li>
											
											
											<li>
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
							<!-- /Profile Sidebar -->
							
						</div>
						<div class="col-md-7 col-lg-8 col-xl-9">						
							<div class="row row-grid">
								<?php
								$i=0;
								while($i<$num){
									echo '<div class="col-md-6 col-lg-4 col-xl-3">
										<div class="card widget-profile pat-widget-profile">
											<div class="card-body">
												<div class="pro-widget-content">
													<div class="profile-info-widget">
														<a href="../patient-profile.php?patient_id='.$patient_id[$i].'" class="booking-doc-img">
															<img class="img-fluid" src="data:image/jpeg;base64,'.base64_encode($patdp[$i]).'" class="img-thumnail" alt="'.$pname[$i].'"/>
														</a>
														<div class="profile-det-info">
															<h3><a>'.$pname[$i].'</a></h3>
															<div class="patient-details">
																<h5><b>Patient Mail :</b>'.$patients[$i].'</h5>
																<h5 class="mb-0"><i class="fas fa-map-marker-alt"></i>'.$pcity[$i].','.$pstate[$i].',<br>'.$pcountry[$i].'</h5>
															</div>
														</div>
													</div>
												</div>
												<div class="patient-info">
													<ul>
														<li>Phone <span>'.$pcontact[$i].'</span></li>
														<li>Age <span>'.$age[$i].'</span></li>
														<li>Blood Group <span>'.$pbg[$i].'</span></li>
														<li>Patient ID <span>'.$patient_id[$i].'</span></li> 
													</ul>
												</div>
											</div>
										</div>
									</div>';
									$i++;
								}
								?>

							</div>

						</div>
					</div>

				</div>

			</div>		
			<!-- /Page Content -->
   
			<!-- Footer -->
			<footer class="footer">
				
				<!-- Footer Top -->
				<div class="footer-top">
					<div class="container-fluid">
						<div class="row">
							<div class="col-lg-3 col-md-6">
							
								<!-- Footer Widget -->
								
								<!-- /Footer Widget -->
								
							</div>
							
							
								<!-- /Footer Widget -->
								
							</div>
							
						</div>
					</div>
				</div>
				<!-- /Footer Top -->
				
				<!-- Footer Bottom -->
                
				<!-- /Footer Bottom -->
				
			</footer>
			<!-- /Footer -->
		   
		</div>
		<!-- /Main Wrapper -->
	  
		<!-- jQuery -->
		<script src="../assets/js/jquery.min.js"></script>
		
		<!-- Bootstrap Core JS -->
		<script src="../assets/js/popper.min.js"></script>
		<script src="../assets/js/bootstrap.min.js"></script>
		
		<!-- Sticky Sidebar JS -->
        <script src="../assets/plugins/theia-sticky-sidebar/ResizeSensor.js"></script>
        <script src="../assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js"></script>
		
		<!-- Custom JS -->
		<script src="../assets/js/script.js"></script>
		
	</body>

<!-- Docter/my-patients.php  30 Nov 2019 04:12:09 GMT -->
</html>