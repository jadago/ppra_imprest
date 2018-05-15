<?php 
$error="";
include('check_permission.php');
include('sms_config.php');
if(isset($_POST['Submit']))
{

require_once('includes/db_conn.php');


										$getitem = "SELECT * FROM imprest WHERE imprest_id > 0 AND status=5 AND stage < 4 ";
									
                                        $query = mysqli_query($con, $getitem);
                                        while ($result = mysqli_fetch_array($query)) {
                                            //get users
                                            $users = "SELECT * FROM users WHERE user_id='".$result['staff_id']."'";
                                            $query1 = mysqli_query($con,$users);
                                            $array1 = mysqli_fetch_array($query1);
                                            
                                           // get total amount
                                            $amount = "SELECT SUM(amount) AS tamount FROM imprest_item WHERE imprest_id='".$result['imprest_id']."'";
                                            $queryA = mysqli_query($con,$amount);
                                            $arrayA = mysqli_fetch_array($queryA);
                                            
                                            //get voucher details
                                            $v = "SELECT * FROM imprest_voucher WHERE imprest_id='".$result['imprest_id']."'";
                                            $queryv = mysqli_query($con,$v);
                                            $arrayv = mysqli_fetch_array($queryv);
											// sms content part
                                            $name = $array1['firstname'];
                                            $phone = $array1['mobile'];
											$amount = $arrayA['tamount'];


// You have phone numbers then send messages 
       $message = "Dear ".$name.", you are reminded to retire your imprest of Tsh.".$amount.", PPRA:";
       $username="ipsdemo";
	   $password = "ips**?ips2013";
	   $sender = "TIB PREMIER";
	   $fullname = $array1['firstname'];
	   $error="Sms has been sent successfully";
	   MyFunction::send_sms($phone,$sender,$username,$password,$message,$fullname);
}


	
	
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include('includes/meta_description.php');?>
<link rel="stylesheet" type="text/css" href="tcal.css" />
<script type="text/javascript" src="tcal.js"></script>
<script type="text/javascript">
function validateForm()
{
 return confirm("Please confirm that you want to send sms to un-retired imprest holder")
}
</script>
</head>

<body>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" onSubmit="return validateForm()">
    <!-- Navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid">
           <?php include('includes/header.php');?>
        </div>
    </div>
    <!-- /navbar -->


    <!-- Page header -->
     <?php include('includes/page_header.php');?>
    <!-- /page header -->


    <!-- Page container -->
    <div class="page-container container-fluid">
    	
    	<!-- Sidebar -->
       <?php include('includes/sidemenu.php');?>
        <!-- /sidebar -->

    
        <!-- Page content -->
        <div class="page-content">

            <!-- Page title -->
        	<div class="page-title">
                <h5><i class="fa fa-warning"></i> Un-retired notifications</h5>
            </div>
            <!-- /page title -->
                
            <!-- Form validation -->
                        <div class="row">
                <div class="col-md-12">
                
                    <!-- Default datatable inside panel -->
                  <div class="panel panel-info">
                    
                      <div class="panel-heading">
                        <h6 class="panel-title">sms notifications to un-retired imprest holder</h6>
                      </div>
					    <table class="table table-bordered " width="80%">
                                    
                                    <tbody>
<tr>
  <td>&nbsp;</td>
  <td><?php echo $error;?></td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
                                      
<tr>
  <td width="3%">&nbsp;</td>
  <td> <input type="submit" name="Submit" value="Click Here to send" class="btn btn-primary"></td>
  </tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
                                    </tbody>
                      </table>
                  </div>
                    <!-- /default datatable inside panel -->

                </div>
            </div>
            <!-- /form validation -->
			
			            <!-- Modal with table -->
           
            <!-- /modal with table -->
			<!-- Footer -->
            <div class="footer">
                <?php include('includes/footer.php');?>
            </div>
            <!-- /footer -->
                    </div>
                </div>

            <!-- /modal with table -->


            

    
        <!-- /page content -->

    <!-- page container -->
</form>
</body>
</html>
