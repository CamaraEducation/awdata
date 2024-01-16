<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link type="image/png" sizes="96x96" rel="icon" href="assets/favicon.ico">
	<title>Camara Education | Client usage dashboard</title>

	<!-- Theme style -->
	<link rel="stylesheet" href="css/adminlte.min.css">
	<link rel="stylesheet" href="css/Chart.min.css">
	<link rel="stylesheet" href="css/datatable.css">
	
</head>
<body class="hold-transition sidebar-mini">
	<?php 
	$conn = mysqli_connect("localhost", "ccnms_user", "Camara2004!", "portal");
	?>
	<div class="wrapper">

		<!-- Navbar -->
		<nav class="main-header navbar navbar-fixed navbar-expand navbar-dark navbar-dark" style="margin-left: 0px;">
			<!-- Left navbar links -->
			<a class="navbar-brand" href="index.php"style="margin-left: 0px;padding-left: 10px;">Camara Education Clients usage dashboard</a>

			<ul class="navbar-nav ml-auto">
				<!-- Messages Dropdown Menu -->
				<form method="post">
					<button class="btn-success btn" type="submit" name="export" style="margin-right: 20px;">EXPORT ALL DATA</button>
					<!-- <a href="server_info.php" class="btn-success btn" style="margin-right: 20px;">Update server</a> -->
				</form>
			
				<img src="assets/img/logo.png" alt="" class="brand-image" height="45" style="padding-right: 10px;">
			</ul>
		</nav>
		<!-- /.navbar -->


		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper" style="margin-left: 0px;">
			<!-- Content Header (Page header) -->
			<div class="content-header" style="padding-bottom: 0px;">
				<div class="container-fluid">
					<div class="row ">		
			            <div class="col-lg-6">
			              <div class="card">
			                <div class="card-body">
			                  <h3 >Date Range</h3>
			                  <form class="mb-0" method="post">
			                    <table border="0" class="table table-bordered">
			                      <tr>
			                        <th>Date From</th>
			                        <th>Date to</th>
			                        <th>Action</th>
			                      </tr>
			                      <tr>
			                        <td><input type="date" name="fdate" class="form-control" id="fdate" value="<?php $date1 = strtotime("yesterday"); echo date('Y-m-d', $date1); ?>"></td>
			                        <td><input type="date" name="tdate" class="form-control" id="tdate" value="<?php $date2 = strtotime("today"); echo date('Y-m-d', $date2); ?>"></td>
			                        <td><button class="btn-success btn" type="submit" name="submit">Generate Data</button></td>
			                      </tr>
			                    </table>
			                  </form>
			                </div>
			              </div>
			            </div><!-- /.col -->
			            <div class="col-lg-6">
								<div class="card card-outline">
									<div class="card-body">
										<h3 >General Usage Report</h3>
										<table class="table table-bordered" border="0">
											<tr>
												<th>Number of Clients</th>
												<th>Total Up Time (Hr)</th>
												<th>Total Idel Time (Hr)</th>
												<th>Total Active Time (Hr)</th>
											</tr>
											<?php
												$total_sql = "SELECT COUNT(DISTINCT devicename) as 'Clients', SUM(duration)/60 as 'Gross time', (SELECT DISTINCT SUM(duration)/60 from aw_usage where cstatus = 'afk' ) as 'Idel time', (SELECT DISTINCT SUM(duration)/60 from aw_usage where cstatus = 'not-afk' ) as 'Active time' from aw_usage";
												$total_sql_result = mysqli_query($conn, $total_sql);
												while($row = mysqli_fetch_assoc($total_sql_result)) {?>
										    <tr>
												<td><?php echo $row["Clients"]; ?></td>
												<td><?php echo decimal_to_time($row["Gross time"]); ?></td>
												<td><?php echo decimal_to_time($row["Idel time"]); ?></td>
												<td><?php echo decimal_to_time($row["Active time"]); ?></td>
											</tr>
											<?php }?>
										</table>
									</div>
								</div>
							</div><!-- /.col -->
						
					</div><!-- /.row -->
				</div><!-- /.container-fluid -->
			</div>
			<!-- /.content-header -->
			<?php
			if(isset($_POST["export"])){
				require_once('report.php');
			}  
			?>
			<!-- Main content -->
			<div class="content">
				<div class="container-fluid">
					<div class="row">
						<?php
						if(ISSET($_POST['submit'])){
							$date1 = date('Y-m-d', strtotime($_POST['fdate']));
							$date2 = date('Y-m-d', strtotime($_POST['tdate']));
							$date3 =$date2;
							$date2 = date('Y-m-d', strtotime($date2 . ' +1 day'));
							$datediff = strtotime($date2) - strtotime($date1);
							$datediff = abs(round($datediff / 86400));
							$i=1;
							?>
							<div class="col-lg-6">
				                <div class="card card-dark card-outline" style="height: 390px;">
				                  <div class="card-header">
				                    <h5 class="m-0">Chart of Monthly Usage Distribution (Active Time)</h5>
				                  </div>
				                  <div class="card-body">
				                    <h6></h6>
				                    <?php
										$active_sql = "select DATE_FORMAT(u.datetimeadded, '%b') as Month, sum(u.duration)/3600 as 'Active Time (Hrs)' from aw_usage u WHERE u.cstatus = 'not-afk' and datetimeadded between '$date1' and '$date2' group by Month";
										$active_sql_result = mysqli_query($conn, $active_sql);
										while($row = mysqli_fetch_assoc($active_sql_result)) {
				                      $Month[] = $row['Month'];
				                      $activetime[] = $row['Active Time (Hrs)'];
				                    }
				                    ?>
					                    <canvas id="myChart" style="position: relative; height:20vh; width:35vw"></canvas>
					                  </div>
				                </div><!-- /.card -->
				              </div><!-- End First row of data displayed -->
							<div class="col-lg-6">
				                <div class="card card-dark card-outline" style="height: 390px;">
				                  <div class="card-header">
				                    <h5 class="m-0">Chart of Total Active Usage duration per Client</h5>
				                  </div>
				                  <div class="card-body">
				                    <h6></h6>
				                    <?php
										$active_client_sql = "SELECT DISTINCT devicename, SUM(duration)/3600 as 'active_time' from aw_usage where cstatus = 'not-afk' and datetimeadded between '$date1' and '$date2' GROUP BY devicename";
										$active_client_sql_result = mysqli_query($conn, $active_client_sql);
										while($row = mysqli_fetch_assoc($active_client_sql_result)) {
				                      $devicename[] = $row['devicename'];
				                      $active_time[] = $row['active_time'];
				                    }
				                    ?>
					                    <canvas id="myChart1" style="position: relative; height:20vh; width:35vw"></canvas>
					                  </div>
				                </div><!-- /.card -->
				              </div><!-- End First row of data displayed -->

				             <div class="col-lg-6">
								<div class="card card-outline">
									<div class="card-body">
										<h3 >Total active usage time per client</h3>
										<?php $i = 1; ?>
										<table  class="table table-bordered table-hover">
											<thead>
												<tr>
													<th>#</th>
													<th>Device Name (Hr)</th>
													<th>Total Active Time (Hr)</th>
												</tr>
											</thead>
										<tbody>
											<?php
												$active_sql = "SELECT DISTINCT devicename, SUM(duration)/60 as 'active_time' from aw_usage where cstatus = 'not-afk' and datetimeadded between '$date1' and '$date2' GROUP BY devicename";
												$active_sql_result = mysqli_query($conn, $active_sql);
												while($row = mysqli_fetch_assoc($active_sql_result)) {?>
										    <tr>
												<td><?php echo $i; ?></td>
												<td><?php echo $row["devicename"]; ?></td>
												<td><?php echo decimal_to_time($row["active_time"]); ?></td>
											</tr>
											<?php $i++; }?>
										</tbody>
										</table>
									</div>
								</div>
							</div><!-- /.col -->
							<div class="col-lg-6">
								<div class="card card-outline">
									<div class="card-body">
										<h3 >Total idel usage time per client</h3>
										<?php $i = 1; ?>
										<table class="table table-bordered" border="0">
											<tr>
												<th>#</th>
												<th>Total Up Time (Hr)</th>
												<th>Total Idel Time (Hr)</th>
											</tr>
											<?php
												$idel_sql = "SELECT DISTINCT devicename, SUM(duration)/60 as 'idel_time' from aw_usage where cstatus = 'afk' and datetimeadded between '$date1' and '$date2' GROUP BY devicename";
												$idel_sql_result = mysqli_query($conn, $idel_sql);
												while($row = mysqli_fetch_assoc($idel_sql_result)) {?>
										    <tr>
												<td><?php echo $i; ?></td>
												<td><?php echo $row["devicename"]; ?></td>
												<td><?php echo decimal_to_time($row["idel_time"]); ?></td>
											</tr>
											<?php $i++; }?>
										</table>
									</div>
								</div>
							</div><!-- /.col -->
							<!-- <div class="col-lg-6"></div> -->
							<div class="col-lg-6">
								<div class="card card-outline">
									<div class="card-body">
										<h3 >Total applications used</h3>
										<?php $i = 1; ?>
										<table id="example" class="table table-bordered table-hover">
											<thead>
												<tr>
													<th>#</th>
													<th>Application</th>
													<th>Active Time (Hr)</th>
												</tr>
											</thead>
											<tbody>
											<?php
												// $top_application_sql = "SELECT app, sum(duration)/60 as max_duration from aw_application GROUP BY app ORDER by max_duration DESC";
												$top_application_sql = "SELECT replace(app, 'portal', 'EdPortal') AS 'Application', CONCAT( IF(SUM(duration) >= 3600, CONCAT(FLOOR(SUM(duration)/3600), ' hrs, '), ''), CONCAT(FLOOR(SUM(duration) % 60), ' mins') ) AS 'Usage Time' FROM aw_application u WHERE app not in ('unknown', 'aw-qt', 'Update-manager', 'Cinnamon-session' , 'cinnamon-settings applets', 'Cinnamon-settings.py', 'Lock_screen.py', 'Nemo-desktop','Nm-applet', 'Polkit-gnome-authentication-agent-1','Nm-connection-editor', 'cinnamon-settings startup','cinnamon-settings network', 'cinnamon-settings panel', 'TigerVNC Viewer', 'Epoptes' ) and datetimeadded between '$date1' and '$date2' GROUP BY u.app ORDER BY SUM(duration) DESC limit 30";
												$top_application_sql_result = mysqli_query($conn, $top_application_sql);
												while($row = mysqli_fetch_assoc($top_application_sql_result)) {?>
										    <tr>
												<td><?php echo $i; ?></td>
												<td><?php echo $row["Application"]; ?></td>
												<td><?php echo $row["Usage Time"]; ?></td>
											</tr>
											<?php $i++; }?>
											</tbody>
										</table>
									</div>
								</div>
							</div><!-- /.col -->

							<div class="col-lg-6">
				                <div class="card card-dark card-outline" style="height: 700px;">
				                  <div class="card-header">
				                    <h5 class="m-0">Chart of Total Applications Usage </h5>
				                  </div>
				                  <div class="card-body">
				                    <h6></h6>
				                    <?php
										$app_sql = "SELECT replace(app, 'portal', 'EdPortal') AS 'Application', sum(duration)/3600 AS 'Usage Time' FROM aw_application u WHERE app not in ('unknown', 'aw-qt', 'Update-manager', 'Cinnamon-session' , 'cinnamon-settings applets', 'Cinnamon-settings.py', 'Lock_screen.py', 'Nemo-desktop' ) and datetimeadded between '$date1' and '$date2' GROUP BY u.app ORDER BY SUM(duration) DESC limit 10";
										$app_sql_result = mysqli_query($conn, $app_sql);
										while($row = mysqli_fetch_assoc($app_sql_result)) {
				                      $app[] = $row['Application'];
				                      $app_active_time[] = $row['Usage Time'];
				                    }
				                    ?>
					                    <canvas id="myChart2" style="position: relative; height:50vh; width:35vw"></canvas>
					                  </div>
				                </div><!-- /.card -->
				              </div><!-- End First row of data displayed -->
							<div class="col-lg-6">
								<div class="card card-outline">
									<div class="card-body">
										<h3 >Total resources used</h3>
										<?php $i = 1; ?>
										<table class="table table-bordered" border="0">
											<tr>
												<th>#</th>
												<th>Resources(Hr)</th>
												<th>Active Time (Hr)</th>
											</tr>
											<?php
												$top_application_sql = "SELECT title, SUM(duration)/60 as max_duration from aw_application WHERE title != '' and datetimeadded between '$date1' and '$date2' GROUP BY title ORDER by max_duration DESC LIMIT 10";
												$top_application_sql_result = mysqli_query($conn, $top_application_sql);
												while($row = mysqli_fetch_assoc($top_application_sql_result)) {?>
										    <tr>
												<td><?php echo $i; ?></td>
												<td><?php echo $row["title"]; ?></td>
												<td><?php echo decimal_to_time($row["max_duration"]); ?></td>
											</tr>
											<?php $i++; }?>
										</table>
									</div>
								</div>
							</div><!-- /.col -->

						<?php }else{ ?>
							


							<div class="col-lg-6">
				                <div class="card card-dark card-outline" style="height: 390px;">
				                  <div class="card-header">
				                    <h5 class="m-0">Chart of Monthly Usage Distribution (Active Time)</h5>
				                  </div>
				                  <div class="card-body">
				                    <h6></h6>
				                    <?php
										$active_sql = "select DATE_FORMAT(u.datetimeadded, '%b') as Month, sum(u.duration)/3600 as 'Active Time (Hrs)' from aw_usage u WHERE u.cstatus = 'not-afk' group by Month";
										$active_sql_result = mysqli_query($conn, $active_sql);
										while($row = mysqli_fetch_assoc($active_sql_result)) {
				                      $Month[] = $row['Month'];
				                      $activetime[] = $row['Active Time (Hrs)'];
				                    }
				                    ?>
					                    <canvas id="myChart" style="position: relative; height:20vh; width:35vw"></canvas>
					                  </div>
				                </div><!-- /.card -->
				              </div><!-- End First row of data displayed -->
							<div class="col-lg-6">
				                <div class="card card-dark card-outline" style="height: 390px;">
				                  <div class="card-header">
				                    <h5 class="m-0">Chart of Total Active Usage duration per Client</h5>
				                  </div>
				                  <div class="card-body">
				                    <h6></h6>
				                    <?php
										$active_client_sql = "SELECT DISTINCT devicename, SUM(duration)/3600 as 'active_time' from aw_usage where cstatus = 'not-afk' GROUP BY devicename";
										$active_client_sql_result = mysqli_query($conn, $active_client_sql);
										while($row = mysqli_fetch_assoc($active_client_sql_result)) {
				                      $devicename[] = $row['devicename'];
				                      $active_time[] = $row['active_time'];
				                    }
				                    ?>
					                    <canvas id="myChart1" style="position: relative; height:20vh; width:35vw"></canvas>
					                  </div>
				                </div><!-- /.card -->
				              </div><!-- End First row of data displayed -->

				             <div class="col-lg-6">
								<div class="card card-outline">
									<div class="card-body">
										<h3 >Total active usage time per client</h3>
										<?php $i = 1; ?>
										<table  class="table table-bordered table-hover">
											<thead>
												<tr>
													<th>#</th>
													<th>Device Name (Hr)</th>
													<th>Total Active Time (Hr)</th>
												</tr>
											</thead>
										<tbody>
											<?php
												$active_sql = "SELECT DISTINCT devicename, SUM(duration)/60 as 'active_time' from aw_usage where cstatus = 'not-afk' GROUP BY devicename";
												$active_sql_result = mysqli_query($conn, $active_sql);
												while($row = mysqli_fetch_assoc($active_sql_result)) {?>
										    <tr>
												<td><?php echo $i; ?></td>
												<td><?php echo $row["devicename"]; ?></td>
												<td><?php echo decimal_to_time($row["active_time"]); ?></td>
											</tr>
											<?php $i++; }?>
										</tbody>
										</table>
									</div>
								</div>
							</div><!-- /.col -->
							<div class="col-lg-6">
								<div class="card card-outline">
									<div class="card-body">
										<h3 >Total idel usage time per client</h3>
										<?php $i = 1; ?>
										<table class="table table-bordered" border="0">
											<tr>
												<th>#</th>
												<th>Total Up Time (Hr)</th>
												<th>Total Idel Time (Hr)</th>
											</tr>
											<?php
												$idel_sql = "SELECT DISTINCT devicename, SUM(duration)/60 as 'idel_time' from aw_usage where cstatus = 'afk' GROUP BY devicename";
												$idel_sql_result = mysqli_query($conn, $idel_sql);
												while($row = mysqli_fetch_assoc($idel_sql_result)) {?>
										    <tr>
												<td><?php echo $i; ?></td>
												<td><?php echo $row["devicename"]; ?></td>
												<td><?php echo decimal_to_time($row["idel_time"]); ?></td>
											</tr>
											<?php $i++; }?>
										</table>
									</div>
								</div>
							</div><!-- /.col -->
							<!-- <div class="col-lg-6"></div> -->
							<div class="col-lg-6">
								<div class="card card-outline">
									<div class="card-body">
										<h3 >Total applications used</h3>
										<?php $i = 1; ?>
										<table id="example" class="table table-bordered table-hover">
											<thead>
												<tr>
													<th>#</th>
													<th>Application</th>
													<th>Active Time (Hr)</th>
												</tr>
											</thead>
											<tbody>
											<?php
												// $top_application_sql = "SELECT app, sum(duration)/60 as max_duration from aw_application GROUP BY app ORDER by max_duration DESC";
												$top_application_sql = "SELECT replace(app, 'portal', 'EdPortal') AS 'Application', CONCAT( IF(SUM(duration) >= 3600, CONCAT(FLOOR(SUM(duration)/3600), ' hrs, '), ''), CONCAT(FLOOR(SUM(duration) % 60), ' mins') ) AS 'Usage Time' FROM aw_application u WHERE app not in ('unknown', 'aw-qt', 'Update-manager', 'Cinnamon-session' , 'cinnamon-settings applets', 'Cinnamon-settings.py', 'Lock_screen.py', 'Nemo-desktop','Nm-applet', 'Polkit-gnome-authentication-agent-1','Nm-connection-editor', 'cinnamon-settings startup','cinnamon-settings network', 'cinnamon-settings panel', 'TigerVNC Viewer', 'Epoptes' ) GROUP BY u.app ORDER BY SUM(duration) DESC limit 30";
												$top_application_sql_result = mysqli_query($conn, $top_application_sql);
												while($row = mysqli_fetch_assoc($top_application_sql_result)) {?>
										    <tr>
												<td><?php echo $i; ?></td>
												<td><?php echo $row["Application"]; ?></td>
												<td><?php echo $row["Usage Time"]; ?></td>
											</tr>
											<?php $i++; }?>
											</tbody>
										</table>
									</div>
								</div>
							</div><!-- /.col -->

							<div class="col-lg-6">
				                <div class="card card-dark card-outline" style="height: 700px;">
				                  <div class="card-header">
				                    <h5 class="m-0">Chart of Total Applications Usage</h5>
				                  </div>
				                  <div class="card-body">
				                    <h6></h6>
				                    <?php
										$app_sql = "SELECT replace(app, 'portal', 'EdPortal') AS 'Application', sum(duration)/3600 AS 'Usage Time' FROM aw_application u WHERE app not in ('unknown', 'aw-qt', 'Update-manager', 'Cinnamon-session' , 'cinnamon-settings applets', 'Cinnamon-settings.py', 'Lock_screen.py', 'Nemo-desktop' ) GROUP BY u.app ORDER BY SUM(duration) DESC limit 10";
										$app_sql_result = mysqli_query($conn, $app_sql);
										while($row = mysqli_fetch_assoc($app_sql_result)) {
				                      $app[] = $row['Application'];
				                      $app_active_time[] = $row['Usage Time'];
				                    }
				                    ?>
					                    <canvas id="myChart2" style="position: relative; height:50vh; width:35vw"></canvas>
					                  </div>
				                </div><!-- /.card -->
				              </div><!-- End First row of data displayed -->
							<div class="col-lg-6">
								<div class="card card-outline">
									<div class="card-body">
										<h3 >Total resources used</h3>
										<?php $i = 1; ?>
										<table class="table table-bordered" border="0">
											<tr>
												<th>#</th>
												<th>Resources(Hr)</th>
												<th>Active Time (Hr)</th>
											</tr>
											<?php
												$top_application_sql = "SELECT title, SUM(duration)/60 as max_duration from aw_application WHERE title != '' GROUP BY title ORDER by max_duration DESC LIMIT 10";
												$top_application_sql_result = mysqli_query($conn, $top_application_sql);
												while($row = mysqli_fetch_assoc($top_application_sql_result)) {?>
										    <tr>
												<td><?php echo $i; ?></td>
												<td><?php echo $row["title"]; ?></td>
												<td><?php echo decimal_to_time($row["max_duration"]); ?></td>
											</tr>
											<?php $i++; }?>
										</table>
									</div>
								</div>
							</div><!-- /.col -->


							

						<?php } 
										
						?>

					</div><!-- /.row -->

				</div><!-- /.container-fluid -->

			</div><!-- /.content -->
		</div><!-- /.content-wrapper -->
		<!-- <div class="copyright py-4 text-center text-wite bg-dark">
			<div class=""><small>Copyright &copy; Camara Education <script>document.write(new Date().getFullYear())</script></small></div>
		</div> -->
	</div>
	<!-- ./wrapper -->

	<!-- REQUIRED SCRIPTS -->
	<script src="js/Chart.min.js"></script>
	<script src="js/chartjs_plugin.js"></script>

	<script src="js/datatable.js"></script>
	<script src="js/table_jqyery.js"></script>
	<!-- jQuery -->
	<script src="js/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="js/adminlte.min.js"></script>
	

	<script>
    var ctx = document.getElementById('myChart');
    var myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: <?php echo json_encode($Month) ?>,
        datasets: [{
          label: 'Monthly Usage Distribution (Active Time)',
          data: <?php echo json_encode($activetime) ?>,
          fill: false,
          backgroundColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(130, 188, 0, 1)',
            'rgba(55, 92, 122, 1)',
            'rgba(255, 196, 31, 1)',
            'rgba(75, 192, 192, 1)'
            ],
          borderColor: "#82bc00",
          borderWidth: 3,
          tension: 0.1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            title: true
          }
        }
      }
    });

    var ctx = document.getElementById('myChart1');
    var myChart1 = new Chart(ctx, {
      type: 'line',
      data: {
        labels: <?php echo json_encode($devicename) ?>,
        datasets: [{
          label: 'Total Active Usage duration per Client',
          data: <?php echo json_encode($active_time) ?>,
          fill: false,
          backgroundColor: [
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(130, 188, 0, 1)',
            'rgba(55, 92, 122, 1)',
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(255, 196, 31, 1)',
            'rgba(75, 192, 192, 1)'
            ],
          
          borderColor: "#82bc00",
          borderWidth: 3,
          tension: 0.1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            title: true
          }
        }
      }
    });

    var chrt = document.getElementById("myChart2").getContext("2d");
      var myChart2 = new Chart(chrt, {
         type: 'pie',
         data: {
            labels: <?php echo json_encode($app) ?>,
            datasets: [{
               label: "online tutorial subjects",
               data: <?php echo json_encode($app_active_time) ?>,
               backgroundColor: [
                "#FF6384",
                "#63FF84",
                "#84FF63",
                "#8463FF",
                "#6384FF",
                "#36a2eb",
                "#ff6384",
                "#cc65fe",
                "#ffce56"
            ],
               hoverOffset: 5
            }],
         },
         options: {
            responsive: false,
         },
      });
</script>

	<?php 
	function decimal_to_time($decimal) {
		$hours = floor($decimal / 60);
		$minutes = floor($decimal % 60);
		$seconds = $decimal - (int)$decimal;
		$seconds = round($seconds * 60);
		return str_pad($hours, 2, "0", STR_PAD_LEFT) . ":" . str_pad($minutes, 2, "0", STR_PAD_LEFT) . ":" . str_pad($seconds, 2, "0", STR_PAD_LEFT);
	}
	?>

</body>
</html>