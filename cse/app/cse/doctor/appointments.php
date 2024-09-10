<?php
session_start();
$email=$_SESSION['email'];
$name=$_SESSION['user'];
include "../dbconfig/db.php";
$query = mysqli_query($conn,"select * from doctor where Email='$email'");
$rows = mysqli_fetch_assoc($query);
//$num=mysqli_num_rows($query);
if (mysqli_num_rows($query)) {
	
	$dcontact=$rows['Contact'];	
	$special=$rows['Special'];
	$dp=$rows['Image'];
		
} 
if(isset($_POST['accept'])){
	$id=$_POST['accept'];
	$sql = "UPDATE appointments SET Status='1' WHERE AppointmentId='$id'";
	$result=mysqli_query($conn,$sql);

}else if(isset($_POST['reject'])){
	$id=$_POST['reject'];
	$sql = "UPDATE appointments SET Status='-1' WHERE AppointmentId='$id'";
	$result=mysqli_query($conn,$sql);
}
$sql1 = "Select * from appointments where DoctorMail='$email'";
$result1=mysqli_query($conn, $sql1);
$num=mysqli_num_rows($result1);
$i=0;
while($row1 = mysqli_fetch_array($result1)){
	//echo $row1['Name'];
	$patmail[$i]=$row1['PatientMail'];
	$temp=$row1['PatientMail'];
	$aid[$i]=$row1['AppointmentId'];
	$doa[$i]=$row1['DateOfAppointment'];
	$status[$i]=$row1['Status'];
	
	$sql = "Select * from patient where Email='$patmail[$i]'";
	$result=mysqli_query($conn, $sql);
	while($row = mysqli_fetch_array($result)){
		$patname[$i]=$row['Name'];
		$patcountry[$i]=$row['country'];
		$contact[$i]=$row['Contact'];
		$patdp[$i]=$row['Image'];
		
	}
	$i++;
}

$i=0;
while($i<$num){
	echo $patmail[$i].'<br>';
	echo $aid[$i].'<br>';
	echo $patname[$i].'<br>';
	echo $patcountry[$i].'<br>';
	echo $contact[$i].'<br>';
	$i++;
}
?>
<!DOCTYPE html> 
<html lang="en">
	
<!-- Docter/appointments.php  30 Nov 2019 04:12:09 GMT -->
<head>
		<meta charset="utf-8">
		<title>appointments</title>
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
		<style>button {
    background-color: Transparent;
    background-repeat:no-repeat;
    border: none;
    cursor:pointer;
    overflow: hidden;
    outline:none;
		}
	</style>

	 
	
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
						
							
						
							
							
						</ul>
					</div>		 
					<ul class="nav header-navbar-rht">
						<li class="nav-item contact-item">
							<div class="header-contact-img">
								<i class="far fa-hospital"></i>							
							</div>
							<div class="header-contact-detail">
								<p class="contact-header">Contact</p>
								<p class="contact-info-header"><?php echo $dcontact;?></p>
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
										<?php echo '<img class="avatar-img rounded-circle" src="data:image/jpeg;base64,'.base64_encode($dp).'"   alt='.$name.'>  ';?>
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
									<li class="breadcrumb-item active" aria-current="page">Appointments</li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title">Appointments</h2>
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
										<a href="#" class="booking-doc-img">
											
										<?php 
											 $image = isset($dp) ? $rows['Image'] : ''; // Assurez-vous que $rows['Image'] n'est pas null
											 $encodedImage = base64_encode($image);
											 echo '<img src="data:image/jpeg;base64,' .$encodedImage . '" class="img-thumnail"  alt='.$name.'/>';

										?>
										</a>
										<div class="profile-det-info">
											<h3><?php echo $name;?></h3>
											
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
											<li class="active">
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
							<div class="appointments">
							<form method="POST" action="appointments.php">
								<!-- Appointment List -->
	<?php	
	$i=0;
	while($i<$num){
	echo '<div class="appointment-list">
			<div class="profile-info-widget">
				<a href="../patient-profile.php" class="booking-doc-img">
				<img class="img-fluid"" src="data:image/jpeg;base64,'.base64_encode($patdp[$i]).'"   alt="'.$patname[$i].'"/>
				</a>
				<div class="profile-det-info">
					<h3><a href="../patient-profile.php">'.$patname[$i].'</a></h3>
					<a>'.$aid[$i].'
					<div class="patient-details">
						<h5><i class="far fa-clock"></i>'.$doa[$i].'</h5>
						<h5><i class="fas fa-map-marker-alt"></i> , '.$patcountry[$i].'</h5>
						<h5><i class="fas fa-envelope"></i>'.$patmail[$i].'</h5>
						<h5 class="mb-0"><i class="fas fa-phone"></i>'.$contact[$i].'</h5>
					</div>
				</div>
			</div>';
if($status[$i]==1){
	echo '<div class="appointment-action">				
				<a href="javascript:void(0);" class="btn btn-sm bg-success-light" >					
					<i class="fas fa-check" style="color:green;"> Accepted</i>
				</a>
				
			</div>
		</div>';
}else if($status[$i]==-1){
	echo '<div class="appointment-action">				
				
				<a href="javascript:void(0);" class="btn btn-sm bg-danger-light">
				<i class="fas fa-times" style="color:red;"> Cancelled</i>					
				</a>
			</div>
		</div>';
}else{
			echo '<div class="appointment-action">				
				<a href="javascript:void(0);" class="btn btn-sm bg-success-light" >					
					<button class="fas fa-check" style="color:green;" name="accept" value="'.$aid[$i].'"> Accept</button>
				</a>
				<a href="javascript:void(0);" class="btn btn-sm bg-danger-light">
				<button class="fas fa-times" style="color:red;" name="reject" value="'.$aid[$i].'"> Cancel</button>					
				</a>
			</div>
		</div>';
}
		$i++;
	}
		?>
		
		</form>
								<!-- /Appointment List -->
								<!-- <i class="fas fa-check" onclick="fund()"></i> Accept -->
								
								
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
		
		<!-- Appointment Details Modal -->
		<div class="modal fade custom-modal" id="appt_details">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Appointment Details</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<ul class="info-details">
							<li>
								<div class="details-header">
									<div class="row">
										<div class="col-md-6">
											<span class="title">#APT0001</span>
											<span class="text">21 Oct 2019 10:00 AM</span>
										</div>
										<div class="col-md-6">
											<div class="text-right">
												<button type="button" class="btn bg-success-light btn-sm" id="topup_status">Completed</button>
											</div>
										</div>
									</div>
								</div>
							</li>
							<li>
								<span class="title">Status:</span>
								<span class="text">Completed</span>
							</li>
							<li>
								<span class="title">Confirm Date:</span>
								<span class="text">29 Jun 2019</span>
							</li>
							<li>
								<span class="title">Paid Amount</span>
								<span class="text">Rs 450</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- /Appointment Details Modal -->
	  <script src="../assets/js/removebanner.js"></script>
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

<!-- Docter/appointments.php  30 Nov 2019 04:12:09 GMT -->
</html>