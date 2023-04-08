<?php include 'db_connect.php' ?>

<div class="container-fluid" style="margin-bottom: 58px;">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<large class="card-title">
					<b>Pending Payment List</b>
				</large>

			</div>
			<div class="card-body" style="overflow-x:auto;">
				<table class="table table-bordered" id="loan-list">
					<colgroup>
						<col width="5%">
						<col width="15%">
						<col width="20%">
						<col width="25%">
						<col width="20%">
						<col width="15%">
					</colgroup>
					<thead>
						<tr>
							<th class="text-center text-white">#</th>
							<th class="text-center text-white">Loan Reference No</th>
							<th class="text-center text-white">Borrower</th>
							<th class="text-center text-white">Address</th>
							<th class="text-center text-white">Contact No</th>
							<th class="text-center text-white">Pending Amount</th>
						</tr>
					</thead>
					<tbody>
						<?php
							
							$i=1;
							
							$qry = $conn->query("(SELECT DISTINCT l.id, l.ref_no, concat(b.firstname,' ',b.lastname)as borrower, b.address, b.contact_no, l.daily_amount from borrowers b JOIN loan_list l ON b.id=l.borrower_id WHERE l.status=2
							EXCEPT
							SELECT DISTINCT l.id, l.ref_no, concat(b.firstname,' ',b.lastname)as borrower, b.address, b.contact_no, l.daily_amount from borrowers b JOIN loan_list l ON b.id=l.borrower_id JOIN payments p on l.id=p.loan_id WHERE l.status=2 AND date(p.date_created)=date(CURRENT_DATE))
							UNION
							SELECT DISTINCT l.id, l.ref_no, concat(b.firstname,' ',b.lastname)as borrower, b.address, b.contact_no, l.daily_amount from borrowers b JOIN loan_list l ON b.id=l.borrower_id JOIN payments p on l.id=p.loan_id WHERE l.status=2 AND date(p.date_created)=date(CURRENT_DATE) AND l.daily_amount>(SELECT sum(amount) from payments p2 where l.id=p2.loan_id and date(p2.date_created)=date(CURRENT_DATE))");
							while($row = $qry->fetch_assoc()):
								$payments = $conn->query("SELECT * from payments where date(date_created)=date(CURRENT_DATE) and loan_id =".$row['id']);
								$paid = $payments->num_rows;
								$offset = $paid > 0 ? " offset $paid ": "";
								$sum_paid = 0;
								while($p = $payments->fetch_assoc()){
									$sum_paid += ($p['amount'] - $p['penalty_amount']);
								}
							$pending = $row['daily_amount'] - $sum_paid;
						 ?>
						 
						 <tr>
						 	
						 	<td class="text-center"><?php echo $i++ ?></td>
						 	<td>
						 		<?php echo $row['ref_no'] ?>
						 	</td>
						 	<td>
						 		<?php echo $row['borrower'] ?>
						 	</td>
						 	<td>
                                <?php echo $row['address'] ?>
						 	</td>
						 	<td class="text-center">
                                <?php echo $row['contact_no'] ?>
						 	</td>
						 	<td class="text-center">
						 		<?php echo number_format($pending,2) ?>
						 	</td>
						 </tr>

						<?php endwhile; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<style>
	td p {
		margin:unset;
	}
	td img {
	    width: 8vw;
	    height: 12vh;
	}
	td{
		vertical-align: middle !important;
	}
</style>	
<script>
	$('#loan-list').dataTable();
</script>