<?php include 'db_connect.php' ?>
<?php session_start(); ?>
<?php 
// extract($_POST);
if(isset($id)){
	$qry = $conn->query("SELECT * FROM file_charges where id=".$_POST['id']);
	foreach($qry->fetch_array() as $k => $val){
		$$k = $val;
	}
}
$loan = $conn->query("SELECT l.*,concat(b.firstname,' ',b.lastname)as name, b.contact_no, b.address from loan_list l inner join borrowers b on b.id = l.borrower_id where l.id = ".$_POST['loan_id']);
foreach($loan->fetch_array() as $k => $v){
	$meta[$k] = $v;
}

$total_file_charge = $meta['amount']*2/100;	//added
$file_charges = $conn->query("SELECT * from file_charges where loan_id =".$_POST['loan_id']);
$sum_paid = 0;
while($f = $file_charges->fetch_assoc()){
	$sum_paid += $f['amount'];
}
$file_charge = $total_file_charge - $sum_paid;
?>
<div class="col-lg-12">
<hr>
<div class="row">
	<div class="col-md-5">
		<div class="form-group">
			<label for="">Payee</label>
			<input name="payee" class="form-control" required="" value="<?php echo isset($payee) ? $payee : (isset($meta['name']) ? $meta['name'] : '') ?>">
		</div>
	</div>
	
</div>
<hr>
<div class="row">
	<div class="col-md-5">
		<p><small>Total File Charge: <b><?php echo number_format($total_file_charge,2) ?></b></small></p>
		<p><small>Remaining File Charge: <b><?php echo number_format($file_charge,2) ?></b></small></p>
	</div>
	<div class="col-md-5">
		<div class="form-group">
			<label for="">Amount</label>
			<input type="number" name="amount" step="any" min="0" max="<?php echo number_format($file_charge,2) ?>" class="form-control text-right" required="" value="<?php echo isset($amount) ? $amount : '' ?>">
			<input type="hidden" name="loan_id" value="<?php echo $_POST['loan_id'] ?>">
			<input type="hidden" name="collect_by" value="<?php echo $_SESSION['login_name'] ?>">
		</div>
	</div>
</div>
</div>