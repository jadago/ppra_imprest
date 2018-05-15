<?php
include('check_permission.php');
$error = "";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
 <?php include('includes/meta_description.php'); ?>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
    </head>

    <body>

        <!-- Navbar -->
        <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container-fluid">
                <?php include('includes/header.php'); ?>
            </div>
        </div>
        <!-- /navbar -->


        <!-- Page header -->
        <?php include('includes/page_header.php'); ?>
        <!-- /page header -->


        <!-- Page container -->
        <div class="page-container container-fluid">

            <!-- Sidebar -->
            <?php include('includes/sidemenu.php'); ?>
            <!-- /sidebar -->


            <!-- Page content -->
            <div class="page-content">

                <!-- Page title -->
                <div class="page-title">
                    <h5><i class="fa fa-warning"></i> Un-retired Imprest Per Staff</h5>
                </div>
                <!-- put image here -->
                <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                <!-- Footer -->
                <div class="footer">
                    <?php include('includes/footer.php'); ?>
                </div>
                <!-- /footer -->
            </div>
        </div>

        <!-- /modal with table -->
  <?php
	require_once('includes/db_conn.php');
	$graph = "SELECT imprest_id,COUNT(imprest_id) AS alltotal,users.department AS dept_id,departments.department_name AS deptName FROM imprest 
	LEFT JOIN users ON imprest.addedby = users.user_id
	LEFT JOIN departments ON users.department=departments.department_id WHERE (imprest.status=5 AND imprest.stage < 4)
	GROUP BY department_id  ORDER BY alltotal DESC";
	$querygraphx = mysqli_query($con,$graph);
	$querygraphy = mysqli_query($con,$graph);
	
	?>




        <!-- /page content -->

        <!-- page container -->
       <script>

$(function () {
    var chart = Highcharts.chart('container', {

        title: {
            text: 'Un-retired Imprest Per Division'
        },

        subtitle: {
            text: ''
        },
      yAxis: {
            title: {
                text: 'Number of un-retired Imprest'
            }

        },
		 tooltip: {
            headerFormat: '<span style="font-size:11px">Total un-retired imprest</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span><b>{point.y}<br/>'
        },
        xAxis: {
            categories: [
			<?php 
			while($arraygraphx = mysqli_fetch_array($querygraphx))
	{
	    
	     
			echo "'".$arraygraphx['deptName']."'";?>,<?php 
			}
			?>
			]
        },

        series: [{
            type: 'column',
            colorByPoint: true,
            data: [<?php 
			while($arraygraphy = mysqli_fetch_array($querygraphy))
	{
			echo $arraygraphy['alltotal']?>,<?php 
			}
			?>],
            showInLegend: false
        }]

    });


    $('#plain').click(function () {
        chart.update({
            chart: {
                inverted: false,
                polar: false
            },
            subtitle: {
                text: 'Plain'
            }
        });
    });

    $('#inverted').click(function () {
        chart.update({
            chart: {
                inverted: true,
                polar: false
            },
            subtitle: {
                text: 'Inverted'
            }
        });
    });

    $('#polar').click(function () {
        chart.update({
            chart: {
                inverted: false,
                polar: true
            },
            subtitle: {
                text: 'Polar'
            }
        });
    });

});
</script>

    </body>
</html>
