<?php
header("Content-Type: application/msword");
header("Content-Disposition: attachment;Filename=imprest_retirement.doc");
include('check_permission.php');
?>
<table width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="31%" align="left"><font size="-2">PPF Tower, 8th Floor<br />
    Ohio Street/Garden Avenue<br/>P.O.BOX 49,<br /><b>Dar es Salaam</b>,<br/> Tanzania</font> </td>
    <td width="34%" align="right"><img src="C:\xampp\htdocs\pmis2015\imprest_ppra\img\logo.jpg" width="320" height="71" /></td>
    <td width="35%"><font size="-2">Tel: +255 22 2133466, 2121236/7<br />
    Fax: +255 22 2121238<br/>E-mail: <u>ceo@ppra.go.tz</u><br /><b>Website: www.ppra.go.tz</b></font></td>
  </tr>
  <tr>
    <td colspan="3" bgcolor="#CCCCCC" align="center"><font size="+1">RETIREMENT OF SAFARI IMPREST</font></td>
  </tr>
  <tr>
    <td colspan="3"><font size="-1"><b>Ref. No.:.................................................................................................</b></font></td>
  </tr>
   <?php
   require_once('includes/db_conn.php');
                            $id2 = $_GET['eid'];
							echo $id2;
                            $get = "SELECT * FROM imprest WHERE imprest_id='$id2'";
                            $query = mysqli_query($con, $get);
                            $array = mysqli_fetch_array($query);

                            //get user details
                            $user = "SELECT * FROM users WHERE user_id='" . $array['staff_id'] . "'";
                            $queryU = mysqli_query($con, $user);
                            $arrayU = mysqli_fetch_array($queryU);
							//get department name
							$dep = "SELECT * FROM departments WHERE department_id='".$arrayU['department']."'";
							$queryD = mysqli_query($con,$dep);
							$arrayD = mysqli_fetch_array($queryD);
                            ?>
  <tr>
    <td colspan="3"><font size="-1"><p>A:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>To be completed by the applicant (in  Duplicate)</strong></p></font></td>
  </tr>
  <tr>
    <td colspan="3"><font size="-1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. Name  as per payroll: <b><?php echo $arrayU['firstname'] . " " . $arrayU['lastname']; ?></b></font></td>
  </tr>
  <tr>
    <td colspan="3"><font size="-1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. Designation :&nbsp;<b><?php echo $arrayU['designation']; ?></b>&nbsp;&nbsp;&nbsp;3.Department: <b><?php echo $arrayD['department_name']; ?></b></font></td>
  </tr>
  <tr>
    <td colspan="3"><font size="-1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. Check Number..........................................5.Salary Scale: <b><?php echo $arrayU['salary']; ?></b></font></td>
  </tr>
  <tr>
    <td colspan="3"><font size="-1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;6. Date of Commencement of Safari:&nbsp;<b><?php echo date("d M Y", strtotime($array['leaving_date'])); ?></b>&nbsp;&nbsp;&nbsp;7.Terminated on:<b><?php echo date("d M Y", strtotime($array['return_date'])); ?></b></font></td>
  </tr>
  <tr>
    <td colspan="3"><font size="-1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;7.	Imprest No….…….of  20..……/20……… for Tshs:&nbsp;<b>...................</b></font></td>
  </tr>
  <tr>
    <td colspan="3"><font size="-1"><p>B:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>Officer’s Certificate</strong></p></font></td>
  </tr>
  <tr>
    <td colspan="3"><font size="-1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I certify that I traveled to <b><?php echo $array['place'];?></b> where I stayed for ....nights.I am therefore entitled to subsistence allowance of Tshs..............plus incidental expenses of Tshs.........</font></td>
  </tr>
  <tr>
    <td colspan="3"><table width="100%"  style="border-collapse: collapse;">
      <tr bgcolor="#CCCCCC">
        <td width="6%" style="border: 1px solid black;"><font size="-1">No</font></td>
        <td width="29%" style="border: 1px solid black;"><font size="-1">Item Name</font></td>
        <td width="34%" style="border: 1px solid black;"><font size="-1">Receipt No</font></td>
        <td width="31%" style="border: 1px solid black;"><font size="-1">Amount</font></td>
      </tr>
     <?php
                                    $counter = 1;
									$tot=0;
                                    $getitem = "SELECT * FROM  imprest_retirement WHERE imprest_id='" . $array['imprest_id'] . "'";
                                    $query = mysqli_query($con, $getitem);
                                    while ($result = mysqli_fetch_array($query)) {


                                        // get item name
                                        $item_name = "SELECT * FROM imprest_item WHERE imprest_id='" . $result['imprest_id'] . "' AND item_id='".$result['item_id']."'";
                                        $queryA = mysqli_query($con, $item_name);
                                        $arrayA = mysqli_fetch_array($queryA);
                                        
                                        //get item_category
                                        $io = "SELECT * FROM item_category WHERE item_id='".$arrayA['item_name']."'";
                                        $queryi = mysqli_query($con,$io);
                                        $arrayi = mysqli_fetch_array($queryi );
                                        //check imprest table
                                        
                                        ?>
 
      <tr>
        <td style="border: 1px solid black;" align="center"><font size="-1"><?php echo $counter."."; ?></font></td>
        <td style="border: 1px solid black;"><font size="-1"><?php if($arrayi['name']) echo $arrayi['name'];else echo $result['description']; ?></font></td>
        <td style="border: 1px solid black;"><font size="-1"><?php echo $result['receipt'];?></font></td>
        <td style="border: 1px solid black;"><font size="-1"><?php echo number_format($result['amount'],0);?></font></td>
      </tr>
      <?php
	  $tot = $tot + $result['amount'];
	  $counter++;
	  }
	  
	  ?>
      <tr>
        <td colspan="3" style="border: 1px solid black;" align="right"><font size="-1">Total Expenditures&nbsp;&nbsp;</font></td>
        <td style="border: 1px solid black;"><font size="-1"><font size="-1"><b>
          <?php $all = $tot;echo number_format($all);?>
        </font></td>
      </tr>
      <tr>
        <td colspan="3" style="border: 1px solid black;" align="right"><font size="-1">Imprest Received&nbsp;&nbsp;</font></td>
        <td style="border: 1px solid black;"><font size="-1">
          <?php
                                            require_once('includes/db_conn.php');
                                            $tot = "SELECT SUM(amount) AS tot FROM imprest_item WHERE imprest_id='" . $array['imprest_id'] . "'";
                                            $queryT = mysqli_query($con, $tot);
                                            $arrayT = mysqli_fetch_array($queryT);
// echo $array['imprest_id'];
                                            echo number_format($arrayT['tot']);
                                            ?>
        </font></td>
      </tr>
      <tr>
        <td colspan="3" style="border: 1px solid black;" align="right"><font size="-1">Excess/Claim&nbsp;&nbsp;</font></td>
        <td style="border: 1px solid black;"><font size="-1"><?php echo number_format($arrayT['tot']-$all);?></font></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="3"><font size="-1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Date:</b>........................................................<b>Signature....................................</b></font></td>
  </tr>
  <tr>
    <td colspan="3"><font size="-1">C:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>Authorizing Officer’s Certificate</strong></font></td>
  </tr>
  <tr>
    <td colspan="3"><font size="-1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.I certify that Mr/Mrs/Ms <b><?php echo $arrayU['firstname'] . " " . $arrayU['lastname']; ?></b> travelled to <b><?php echo $array['place'];?></b>  where he/she stayed for .......... nights.Therefore authorize payment of his/her claim to the extent of Tshs...........</font></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size="-1"><b>Date:</b>........................................................<b>Signature.....................................</b></font></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
</table>

