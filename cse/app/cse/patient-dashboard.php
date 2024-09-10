<?php
session_start();
$email=$_SESSION['email'];
$uname=$_SESSION['user'];
$user=$_SESSION['user'];
include "dbconfig/db.php";

$query = mysqli_query($conn,"select * from patient where Email='$email'");
$rows = mysqli_fetch_assoc($query);
//$num=mysqli_num_rows($query);
if (mysqli_num_rows($query)) {
	
	$contact=$rows['Contact'];	
	$lname=$rows['lname'];
	$dob=$rows['dob'];
	$bg=$rows['bgroup'];
	$emergencynumber=$rows['emergencynumber'];
	$address=$rows['address'];
	$city=$rows['city'];
	$state=$rows['state'];
	$country=$rows['country'];
	$pcode=$rows['pincode'];
	$height=$rows['height'];
	$weight=$rows['weight'];	
} 

$sql1 = "Select * from appointments where PatientMail='$email'";
$result1=mysqli_query($conn, $sql1);
$num=mysqli_num_rows($result1);
$i=0;
while($row1 = mysqli_fetch_array($result1)){
	//echo $row1['Name'];
	$Docmail[$i]=$row1['DoctorMail'];
	
	$aid[$i]=$row1['AppointmentId'];
	$doa[$i]=$row1['DateOfAppointment'];
	$toa[$i]=railway($row1['Time']);
	$status[$i]=$row1['Status'];
	
	$sql = "Select * from doctor where Email='$Docmail[$i]'";
	$result=mysqli_query($conn, $sql);
	while($row = mysqli_fetch_array($result)){
		$Docname[$i]=$row['Name'];
		$special[$i]=$row['Special'];
		$dp[$i]=$row['Image'];
		//$patcountry[$i]=$row['country'];
		//$contact[$i]=$row['Contact'];
		
	}
	$i++;
}
function railway($str){	
	$hours = str_split($str, 2);
	$min=str_split($str, 3);
	$hour=$hours[0];
	$minutes=$min[1];
	$merid="AM";
	if($hour>12){
	$hour-=12;
	$merid="PM";
	}
	$time=$hour.':'.$minutes.' '.$merid;
	return $time;
	}
?>
<!DOCTYPE html> 
<html lang="en">
	
<!-- Docter/patient-dashboard.php  30 Nov 2019 04:12:16 GMT -->
<head>
		<meta charset="utf-8">
		<title>Patient dashboard</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
		
		<!-- Favicons -->
		<link href="assets/img/favicon.png" rel="icon">
		
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css">
		
		<!-- Fontawesome CSS -->
		<link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
		<link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
		
		<!-- Main CSS -->
		<link rel="stylesheet" href="assets/css/style.css">
		
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		
	
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
						<a href="index.php" class="navbar-brand logo">
							<img src="assets/img/logo.png" class="img-fluid" alt="Logo">
						</a>
					</div>
					<div class="main-menu-wrapper">
						<div class="menu-header">
							<a href="index.php" class="menu-logo">
								<img src="assets/img/logo.png" class="img-fluid" alt="Logo">
							</a>
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
								<p class="contact-header">Emergency Contact</p>
								<p class="contact-info-header"><?php echo $emergencynumber;?></p>
							</div>
						</li>
						
						<!-- User Menu -->
						<li class="nav-item dropdown has-arrow logged-item">
							<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
								<span class="user-img">
								                    <?php 
														$image = isset($rows['Image']) ? $rows['Image'] : ''; // Assurez-vous que $rows['Image'] n'est pas null
														$encodedImage = base64_encode($image);
														 echo '<img class="rounded-circle" src="data:image/jpeg;base64,' . $encodedImage . '" class="img-thumbnail" alt="' . htmlspecialchars($user) . '"/>';
													?>
								</span>
							</a>
							<div class="dropdown-menu dropdown-menu-right">
								<div class="user-header">
									<div class="avatar avatar-sm">
									    <?php echo '<img class="avatar-img rounded-circle" src="data:image/jpeg;base64,'.base64_encode($rows['Image'] ).'"  alt='.$user.'/>  ';?>
										
									</div>
									<div class="user-text">
										<h6><?php echo $uname;?></h6>
										<p class="text-muted mb-0">Patient</p>
									</div>
								</div>
								<a class="dropdown-item" href="patient-dashboard.php">Dashboard</a>
								<a class="dropdown-item" href="profile-settings.php">Profile Settings</a>
								
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
									<li class="breadcrumb-item"><a href="search.php">Home</a></li>
									<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title">Dashboard</h2>
						</div>
					</div>
				</div>
			</div>
			<!-- /Breadcrumb -->
			
			<!-- Page Content -->
			<div class="content">
				<div class="container-fluid">

					<div class="row">
						
						<!-- Profile Sidebar -->
						<div class="col-md-5 col-lg-4 col-xl-3 theiaStickySidebar">
							<div class="profile-sidebar">
								<div class="widget-profile pro-widget-content">
									<div class="profile-info-widget">
										<a href="#" class="booking-doc-img">
										           <?php 
														$image = isset($rows['Image']) ? $rows['Image'] : ''; // Assurez-vous que $rows['Image'] n'est pas null
														$encodedImage = base64_encode($image);
														 echo '<img class="rounded-circle" src="data:image/jpeg;base64,' . $encodedImage . '" class="img-thumbnail" alt="' . htmlspecialchars($user) . '"/>';
													?>
										</a>
										<div class="profile-det-info">
											<h3><?php echo $uname;?></h3>
											<div class="patient-details">
												<h5><i class="fas fa-ruler"></i><?php echo $height;?> cm</h5>
												<h5><i class="fas fa-weight"></i><?php echo $weight;?> kg</h5>
												<h5><i class="fas fa-birthday-cake"></i><?php echo $dob;?></h5>
												<h5 class="mb-0"><i class="fas fa-map-marker-alt"></i><?php echo $address;?>, <?php echo $city;?>, <?php echo $country;?></h5>
											</div>
										</div>
									</div>
								</div>
								<div class="dashboard-widget">
									<nav class="dashboard-menu">
										<ul>
											<li class="active">
												<a href="patient-dashboard.php">
													<i class="fas fa-columns"></i>
													<span>Dashboard</span>
												</a>
											</li>
											<li>
												<a href="search.php">
													<i class="fas fa-bookmark"></i>
													<span>Book Appointment</span>
												</a>
											</li>
											<li>
												<a href="profile-settings.php">
													<i class="fas fa-user-cog"></i>
													<span>Profile Settings</span>
												</a>
											</li>
											
											<li>
												<a href="change-password.php">
													<i class="fas fa-lock"></i>
													<span>Change Password</span>
												</a>
											</li>
											<li>
												<a href="logout.php">
													<i class="fas fa-sign-out-alt"></i>
													<span>Logout</span>
												</a>
											</li>
										</ul>
									</nav>
								</div>

							</div>
						</div>
						<!-- / Profile Sidebar -->
						
						<div class="col-md-7 col-lg-8 col-xl-9">
							<div class="card">
								<div class="card-body pt-0">
								
									<!-- Tab Menu -->
									<nav class="user-tabs mb-4">
										<ul class="nav nav-tabs nav-tabs-bottom nav-justified">
											<li class="nav-item">
												<a class="nav-link active" href="#pat_appointments" data-toggle="tab">Appointments</a>
											</li>
											
										</ul>
									</nav>
									
									<!-- /Tab Menu -->
									
									<!-- Tab Content -->
									<div class="tab-content pt-0">
										
										<!-- Appointment Tab -->
										<div id="pat_appointments" class="tab-pane fade show active">
										<div class="card card-table mb-0">
										<div class="card-body">
										<div class="table-responsive">
										<table class="table table-hover table-center mb-0">
										<thead>
											<tr>
												<th>Doctor</th>
												<th>Appointment ID</th>
												<th>Appointment Date</th>	
												<th>Appointment Time</th>		
												<th>Status</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
										<?php
											$i=0;
											while($i<$num){
												echo '<tr>
												<td>
													<h2 class="table-avatar">
														<a href="" class="avatar avatar-sm mr-2">
															<img class="rounded-circle" src="data:image/jpeg;base64,'.base64_encode($dp[$i]).'"  class="img-thumnail" alt="'.$Docname[$i].'"/>
														</a>
														<a >'.$Docname[$i].'<span>'.$special[$i].'</span></a>
													</h2>
												</td>
												<td> <span class="d-block text-info">'.$aid[$i].'</span></td>
												<td>'.$doa[$i].'</td>
												<td>'.$toa[$i].'</td>';
												
										if($status[$i]==1){
											echo '<td><span class="badge badge-pill bg-success-light">Confirm</span></td>		
											</tr>';
										}else if($status[$i]==-1){
											echo '<td><span class="badge badge-pill bg-danger-light">Cancelled</span></td>		
											</tr>';
										}else{
											echo '	<td><span class="badge badge-pill bg-warning-light">Pending</span></td>';
										}	
												
											$i++;
											}

											

											?>
											
											</tbody>
										</table>
										</div>
										</div>
										</div>
										</div>
										<!-- /Appointment Tab -->
										 <!-- Prescription Tab -->

									

										<div class="container mt-5">
    <nav class="user-tabs mb-4">
        <ul class="nav nav-tabs nav-tabs-bottom nav-justified">
            <li class="nav-item">
                <a class="nav-link active" href="#pat_Prescription" data-toggle="tab">Prescription</a>
            </li>
        </ul>
    </nav>

    <div id="pat_Prescription" class="tab-pane fade show active">
        <div class="card card-table mb-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-center mb-0">
                        <thead>
                            <tr>
                                <th>Patient ID</th>
                                <th>Doctor ID</th>
                                <th>Medication</th>
                                <th>Dosage</th>
                                <th>Frequency</th>
                                <th>Duration</th>
                                <th>Prescription Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            // Connexion à la base de données
                            $servername = "localhost";
                            $username = "root";
                            $password = "";
                            $dbname = "doc";

                            $conn = new mysqli($servername, $username, $password, $dbname);

                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            $sql = "SELECT patient_id, doctor_id, medication, dosage, frequency, duration, created_at FROM prescriptions";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>' . $row["patient_id"] . '</td>';
                                    echo '<td>' . $row["doctor_id"] . '</td>';
                                    echo '<td>' . $row["medication"] . '</td>';
                                    echo '<td>' . $row["dosage"] . '</td>';
                                    echo '<td>' . $row["frequency"] . '</td>';
                                    echo '<td>' . $row["duration"] . '</td>';
                                    echo '<td>' . $row["created_at"] . '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="7">No prescriptions found</td></tr>';
                            }

                            $conn->close();
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
										<!-- /Billing Tab -->
										
									</div>
									<!-- Tab Content -->
								</div>
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
								<div class="footer-widget footer-about">
									
									</div>
								</div>
								<!-- /Footer Widget -->
								
							</div>
							
							
								<!-- /Footer Widget -->
								
							</div>
							
						</div>
					</div>
				</div>
				<!-- /Footer Top -->
				
				<!-- Footer Bottom -->
                <div class="footer-bottom">
					<div class="container-fluid">
					
						<!-- Copyright -->
						<div class="copyright">
							<div class="row">
								<div class="col-md-6 col-lg-6">
									<div class="copyright-text">
										<p class="mb-0"><a href="templateshub.net"></a></p>
									</div>
								</div>
								<div class="col-md-6 col-lg-6">
								
									<!-- Copyright Menu -->
									<div class="copyright-menu">
										<ul class="policy-menu">
											<li><a href="term-condition.php">Terms and Conditions</a></li>
											<li><a href="privacy-policy.php">Policy</a></li>
										</ul>
									</div>
									<!-- /Copyright Menu -->
									
								</div>
							</div>
						</div>
						<!-- /Copyright -->
						
					</div>
				</div>
				<!-- /Footer Bottom -->
				
			</footer>
			<!-- /Footer -->
		   
		</div>
		<!-- /Main Wrapper -->
	  <script src="assets/js/removebanner.js"></script>
		<!-- jQuery -->
		<script src="assets/js/jquery.min.js"></script>
		
		<!-- Bootstrap Core JS -->
		<script src="assets/js/popper.min.js"></script>
		<script src="assets/js/bootstrap.min.js"></script>
		
		<!-- Sticky Sidebar JS -->
        <script src="assets/plugins/theia-sticky-sidebar/ResizeSensor.js"></script>
        <script src="assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js"></script>
		
		<!-- Custom JS -->
		<script src="assets/js/script.js"></script>
		
	</body>

<!-- Docter/patient-dashboard.php  30 Nov 2019 04:12:16 GMT -->
</html>