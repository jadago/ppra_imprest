<?php
include('check_permission.php');
$error = "";
$ok = "";
if (isset($_POST['Submit'])) {
    require_once('includes/db_conn.php');
	 //var_dump($_POST);
    $retired_date = date('Y-m-d');
    $addedby = isset($_POST['addedby']) ? $_POST['addedby'] : '';
    $imprest_id = isset($_POST['imprest_id']) ? $_POST['imprest_id'] : '';
   
	//varibles for extra items to retire
	 
	 $d = isset($_POST['d']) ? $_POST['d'] : '';
	 $r = isset($_POST['r']) ? $_POST['r'] : ''; //for receipt
	 $a = isset($_POST['a']) ? $_POST['a'] : ''; // for amount

    for ( $i=1; $i < $_POST['totalusers']; $i++)
	{
        require_once('includes/db_conn.php');
		$amount=isset($_POST[amount.$i])?$_POST[amount.$i]:'';
		$receipt=isset($_POST[receipt.$i])?$_POST[receipt.$i]:'';
                 $item = isset($_POST[item_id.$i]) ? $_POST[item_id.$i] : '';
		
$insert="INSERT INTO imprest_retirement (imprest_id,receipt,amount,addedby,added_date,description,item_id) VALUES('$imprest_id','$receipt','$amount','$addedby','$retired_date','','$item')";
$query=mysqli_query($con,$insert);
		
	    if($query)
		{
		//do something
		} 
		else $error = "Something went wrong ".$_POST['user'.$i];
	  
	}//close for loop
        
        //start inserting new items of retirement
		
         $number = count($d); 
          if($number > 0)
          {
              for($j=0; $j<$number; $j++)  
      {  
           if(trim($d[$j] != ''))  
           {   
        $insert_data = "INSERT INTO imprest_retirement (imprest_id,receipt,amount,addedby,added_date,description) VALUES('$imprest_id','$r[$j]','$a[$j]','$addedby','$retired_date','$d[$j]')";
        $querydata = mysqli_query($con, $insert_data); 
           } 
              
          }//end of for
          }//end of if number > 0
          
          
          //update the imprest status to stage = 2
          $u = "UPDATE imprest SET stage='2' WHERE imprest_id='$imprest_id'";
          $queryu = mysqli_query($con,$u);
	
	header('Location:imprest_retirement_view.php');
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include('includes/meta_description.php'); ?>
          <style>
            .entry:not(:first-of-type)
            {
                margin-top: 10px;
            }

            .glyphicon
            {
                font-size: 12px;
            }

            td {
                padding:2px;   
            }
        </style>
                <style>
            .select3 {
                border: 1px solid #DDD;
                border-radius: 5px;
                box-shadow: 0 0 0px #888;
                color: #666;
                float: left;
                padding: 8px 5px 5px 10px;
                width: 32%;
                outline: none;
            }
            .remove{
                background: #C76868;
                color: #FFF;
                font-weight: bold;
                font-size: 21px;
                border: 0;
                cursor: pointer;
                display: inline-block;
                padding: 4px 9px;
                vertical-align: top;
                line-height: 100%;   
            }
            .addfields{
                background: green;
                color: #FFF;
                font-weight: bold;
                font-size: 21px;
                border: 0;
                cursor: pointer;
                display: inline-block;
                padding: 4px 9px;
                vertical-align: top;
                line-height: 100%;   
            }
        </style>
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
                <?php
                $id = isset($_GET['id']) ? $_GET['id'] : '';
                $get = "SELECT * FROM imprest WHERE imprest_id='$id'";
                $query = mysqli_query($con, $get);
                $array = mysqli_fetch_array($query);

//get user details
                $user = "SELECT * FROM users WHERE user_id='" . $array['staff_id'] . "'";
                $queryU = mysqli_query($con, $user);
                $arrayU = mysqli_fetch_array($queryU);
                ?>
                <!-- Page title -->
                <div class="page-title">
                    <h5><i class="fa fa-warning"></i><a href="imprest_retirement_view.php"><font color="white">All Imprest</font></a></h5>
                </div>
                <!-- /page title -->

                <!-- Form validation -->
                <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    <div class="row">
                        <div class="col-md-6">

                            <table class="table table-bordered">


                                <tbody>
                                    <tr>
                                        <td width="30%" >Activity Start Date</td>
                                        <td width="70%" bgcolor="white"><?php echo date("d M Y", strtotime($array['leaving_date'])); ?></td>

                                    </tr>
                                    <tr>
                                        <td>Activity End Date</td>
                                        <td bgcolor="white"><?php echo date("d M Y", strtotime($array['return_date'])); ?></td>

                                    </tr>
                                    <tr>
                                        <td>Expected Retire Date</td>
                                        <td bgcolor="white"><?php echo date("d M Y", strtotime($array['return_date'])); ?></td>

                                    </tr>
                                    <tr>
                                        <td>Purpose</td>
                                        <td bgcolor="white"><?php echo $array['purpose']; ?></td>

                                    </tr>
                                    <tr>
                                        <td>Outstanding Amount</td>
                                        <td bgcolor="yellow"><?php
                                            $balance = "SELECT imprest_id,SUM(amount) AS Oamount FROM imprest_item WHERE imprest_id IN(SELECT imprest_id FROM imprest WHERE addedby='" . $arraylogUser['user_id'] . "' AND status=5 AND stage=1)";
                                            $queryb = mysqli_query($con, $balance);
                                            $arrayb = mysqli_fetch_array($queryb);
                                            echo number_format($arrayb['Oamount'], 0);
                                            ?></td>

                                    </tr>
                                </tbody>
                            </table>


                        </div>

                        <div class="col-md-6">

                            <table class="table table-bordered">

                                <tbody>
                                    <tr>
                                        <td width="30%">Requested By</td>
                                        <td width="70%" bgcolor="white"><?php echo $arrayU['firstname'] . " " . $arrayU['lastname']; ?></td>

                                    </tr>
                                    <tr>
                                        <td>Entered By</td>
                                        <td bgcolor="white"><?php echo $arrayU['firstname'] . " " . $arrayU['lastname']; ?></td>

                                    </tr>
                                    <tr>
                                        <td>Last updated At</td>
                                        <td bgcolor="white"><?php echo date("d M Y", strtotime($array['date_requested'])); ?></td>

                                    </tr>
                                    <tr>
                                        <td>Approved</td>
                                        <td bgcolor="white">
                                            <?php
                                            $ap = "SELECT * FROM imprest WHERE imprest_id='$id' AND status > 3";
                                            $query_ = mysqli_query($con, $ap);
                                            $array_ = mysqli_fetch_array($query_);
                                            if ($array_['status'] > 3) {
                                                ?>
                                                <span class="label label-success">Yes</span>
                                                <?php
                                            } else {
                                                ?>
                                                <span class="label label-danger">No</span>
                                                <?php
                                            }
                                            ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>Requested Amount</td>
                                        <td bgcolor="white">
                                            <?php
                                            require_once('includes/db_conn.php');
                                            $tot = "SELECT SUM(amount) AS tot FROM imprest_item WHERE imprest_id='" . $array['imprest_id'] . "'";
                                            $queryT = mysqli_query($con, $tot);
                                            $arrayT = mysqli_fetch_array($queryT);
// echo $array['imprest_id'];
                                            echo number_format($arrayT['tot']);
                                            ?>
                                        </td>

                                    </tr>

                                </tbody>
                            </table>


                        </div>


                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-md-12"> 
                            <table class="table table-bordered">
                                <thead>
                                    <tr bgcolor="#3399CC">
                                        <th width="8%">S/N</th>
                                        <th width="17%">Item Name</th>
                                        <th width="27%">Description</th>
                                        <th width="11%">Amount Paid</th>
                                        <th width="14%">Receipt #</th>
                                        <th width="23%">Amount Retired</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $counter = 1;
                                    $getitem = "SELECT * FROM imprest_item WHERE imprest_id='" . $array['imprest_id'] . "'";
                                    $query = mysqli_query($con, $getitem);
                                    while ($result = mysqli_fetch_array($query)) {


                                        // get item name
                                        $item_name = "SELECT * FROM item_category WHERE item_id='" . $result['item_name'] . "'";
                                        $queryA = mysqli_query($con, $item_name);
                                        $arrayA = mysqli_fetch_array($queryA);
                                        ?>
                                        <tr>
                                            <td><?php echo $counter; ?><input type="hidden" name="item_id<?php echo $counter;?>" value="<?php echo $result['item_id'];?>"></td>
                                            <td><?php echo $arrayA['name']; ?></td>
                                            <td><?php echo $result['description']; ?></td>
                                            <td><?php echo number_format($result['amount'], 0); ?></td>
                                            <td><input type="text" class="form-control" name="receipt<?php echo $counter; ?>"></td>
                                            <td><input type="number" class="form-control" name="amount<?php echo $counter; ?>" required></td>
                                        </tr>
                                        <?php
                                        $counter++;
                                    }
                                    ?>  
                                    <input name="totalusers" type="hidden" value="<?php echo $counter; ?>" />
                                        <tr bgcolor="#999999">
                                          <td colspan="6">Add Other Retirement Items</td>
                                        </tr>
                                        <tr>
                                          <td colspan="6"><div class="row">
                        <div class="col-md-12"> 
                        <div ng-app="myapp" ng-controller="MainCtrl"> 
                                <fieldset  ng-repeat="column in columns">
                                    <div class="row" >
                                        <div class="col-md-12">
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" name="d[]" placeholder="Description">
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" name="r[]" placeholder="Receipt">
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" name="a[]" placeholder="Amount">
                                            </div>
                                            <div class="col-md-3">
                                                <button type="button" class="addfields" ng-click="addNewColumn()">+</button>
                                                <button type="button" class="remove"  ng-click="removeColumn($index)">x</button>
                                            </div>         
                                        </div>
                                    </div>
                                    <br/>
                                    <br/>
                                </fieldset>
                            </div>
                            <br/>
                            <br/>
                        </div>
                        </div></td>
                                        </tr>
                                        
                                    <tr>
                                        <td></td>
                                        <td><div class="col-sm-10">
                                                <input type="submit" value="Save & Exit" class="btn btn-primary" name="Submit">
                                                <input name="addedby" type="hidden" value="<?php echo $arraylogUser['user_id']; ?>" />
                                                <input name="imprest_id" type="hidden" value="<?php echo $id; ?>" />
                                            </div></td>
                                    </tr>
                                </tbody>
                            </table>
                      </div>

                    </div>
                    <!-- Modal with table -->

                  
                </form>
                <!-- /form validation -->

                <!-- /modal with table -->
                <!-- Footer -->
                <div class="footer">
                    <?php include('includes/footer.php'); ?>
                </div>
                <!-- /footer -->
            </div>
        </div>

        <!-- /modal with table -->
 <script type="text/javascript">
            $(function ()
            {
                $(document).on('click', '.btn-add', function (e)
                {
                    e.preventDefault();

                    var controlForm = $(this).closest('table'),
                            currentEntry = $(this).parents('tr:first'),
                            newEntry = $(currentEntry.clone()).appendTo(controlForm);

                    newEntry.find('input').val('');
                    controlForm.find('tr:not(:last) .btn-add')
                            .removeClass('btn-add').addClass('btn-remove')
                            .removeClass('btn-success').addClass('btn-danger')
                            .html('<span class="glyphicon glyphicon-minus gs"></span>');
                }).on('click', '.btn-remove', function (e)
                {
                    $(this).parents('tr:first').remove();

                    e.preventDefault();
                    return false;
                });
            });

        </script>
  <script>
            angular.module("myapp", [])

                    .controller("MainCtrl", function ($scope)
                    {
                        //$scope.title = "Hellow Mr. Justine";

                        $scope.columns = [{colId: 'col1'}];
                        $scope.addNewColumn = function () {
                            var newItemNo = $scope.columns.length + 1;
                            $scope.columns.push({'colId': 'col' + newItemNo});
                        };
                        //to remove
                        $scope.removeColumn = function (index) {
                            // remove the row specified in index
                            $scope.columns.splice(index, 1);
                            // if no rows left in the array create a blank array
                            if ($scope.columns.length() === 0 || $scope.columns.length() == null) {
                                alert('no rec');
                                $scope.columns.push = [{"colId": "col1"}];
                            }


                        };
                        //end of removal function
                    });

        </script>

    </body>
</html>
