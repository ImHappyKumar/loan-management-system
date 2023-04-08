<?php include 'db_connect.php' ?>
<style>
   
</style>

<div class="containe-fluid">

	<div class="row">
		<div class="col-lg-12">
			
		</div>
	</div>
    
    <div class="row mt-3 ml-3 mr-3">
            <div class="col-lg-12">
            <div class="card" style="margin-bottom: 75px;">
                <div class="card-body">
                    <?php if($_SESSION['login_type'] == 1): ?>
                        <h1>Admin Dashboard</h1>	
                    <?php endif; ?>
                    <?php if($_SESSION['login_type'] == 2): ?>
                        <h1>Staff Dashboard</h1>	
                    <?php endif; ?>
                </div>
                <hr>
                <div class="row ml-2 mr-2">
                <div class="col-md-3">
                        <div class="card bg-primary text-white mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="mr-3">
                                        <div class="text-white-75 small">Payments Today</div>
                                        <div class="text-lg font-weight-bold">
                                            <?php 
                                            $payments = $conn->query("SELECT sum(amount) as total FROM payments where date(date_created) = date(CURRENT_DATE)");
                                            echo $payments->num_rows > 0 ? number_format($payments->fetch_array()['total'],2) : "0.00";
                                            ?>
                                                
                                        </div>
                                    </div>
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="index.php?page=payments">View Payments</a>
                                <div class="small text-white">
                                    <i class="fas fa-angle-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="col-md-3">
                        <div class="card bg-success text-white mb-3" style="background: #28a745 !important;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="mr-3">
                                        <div class="text-white-75 small">File Charges Today</div>
                                        <div class="text-lg font-weight-bold">
                                            <?php 
                                            $file_charges = $conn->query("SELECT sum(amount) as total FROM file_charges where date(date_created) = date(CURRENT_DATE)");
                                            echo $file_charges->num_rows > 0 ? number_format($file_charges->fetch_array()['total'],2) : "0.00";
                                            ?>
                                                
                                        </div>
                                    </div>
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="index.php?page=file_charges">View File Charges</a>
                                <div class="small text-white">
                                    <i class="fas fa-angle-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="col-md-3">
                        <div class="card bg-danger text-white mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="mr-3">
                                        <div class="text-white-75 small">Expenditure Today</div>
                                        <div class="text-lg font-weight-bold">
                                            <?php 
                                            $expenditure = $conn->query("SELECT sum(amount) as total FROM expenditure where date(date_created) = date(CURRENT_DATE)");
                                            echo $expenditure->num_rows > 0 ? number_format($expenditure->fetch_array()['total'],2) : "0.00";
                                            ?>
                                                
                                        </div>
                                    </div>
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="index.php?page=expenditure">View Expenditure</a>
                                <div class="small text-white">
                                    <i class="fas fa-angle-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="col-md-3">
                        <div class="card bg-info text-white mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="mr-3">
                                        <div class="text-white-75 small">Net Total Today</div>
                                        <div class="text-lg font-weight-bold">
                                            <?php
                                            $payments = $conn->query("SELECT sum(amount) as total FROM payments where date(date_created) = date(CURRENT_DATE)");
                                            $file_charges = $conn->query("SELECT sum(amount) as total FROM file_charges where date(date_created) = date(CURRENT_DATE)");
                                            $expenditure = $conn->query("SELECT sum(amount) as total FROM expenditure where date(date_created) = date(CURRENT_DATE)");
                                            $payments =  $payments->num_rows > 0 ? $payments->fetch_array()['total'] : "0";
                                            $file_charges =  $file_charges->num_rows > 0 ? $file_charges->fetch_array()['total'] : "0";
                                            $expenditure =  $expenditure->num_rows > 0 ? $expenditure->fetch_array()['total'] : "0";
                                            echo number_format(($payments + $file_charges - $expenditure),2);
                                            ?>
                                                
                                        </div>
                                    </div>
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="index.php?page=payments">View Payments</a>
                                <div class="small text-white">
                                    <i class="fas fa-angle-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="col-md-3">
                        <div class="card bg-warning text-white mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="mr-3">
                                        <div class="text-white-75 small">Pending Payments Today</div>
                                        <div class="text-lg font-weight-bold">
                                        <?php
							
                                            $i=1;
                                            $total_pending = 0;
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
                                        <?php $total_pending += $pending; ?>
                                        <?php endwhile; ?>
                                        <?php echo number_format($total_pending,2) ?>
                                        </div>
                                    </div>
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="index.php?page=pending_payments">View Pending Payments</a>
                                <div class="small text-white">
                                    <i class="fas fa-angle-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="col-md-3">
                        <div class="card text-white mb-3" style="background: #0dcaf0 !important;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="mr-3">
                                        <div class="text-white-75 small">Borrowers</div>
                                        <div class="text-lg font-weight-bold">
                                            <?php 
                                            $borrowers = $conn->query("SELECT * FROM borrowers");
                                            echo $borrowers->num_rows > 0 ? $borrowers->num_rows : "0";
                                            ?>
                                                
                                        </div>
                                    </div>
                                    <i class="fa fa-user-friends"></i>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="index.php?page=borrowers">View Borrowers</a>
                                <div class="small text-white">
                                    <i class="fas fa-angle-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="col-md-3">
                        <div class="card bg-secondary text-white mb-3" style="background: #20c997 !important;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="mr-3">
                                        <div class="text-white-75 small">Active Loans</div>
                                        <div class="text-lg font-weight-bold">
                                            <?php 
                                            $loans = $conn->query("SELECT * FROM loan_list where status = 2");
                                            echo $loans->num_rows > 0 ? $loans->num_rows : "0";
                                            ?>
                                                
                                        </div>
                                    </div>
                                    <i class="fa fa-user-friends"></i>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="index.php?page=loans">View Loan List</a>
                                <div class="small text-white">
                                    <i class="fas fa-angle-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white mb-3" style="background: #d63384 !important;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="mr-3">
                                        <div class="text-white-75 small">Total Receivable</div>
                                        <div class="text-lg font-weight-bold">
                                            <?php 
                                            $payments = $conn->query("SELECT sum(amount - penalty_amount) as total FROM payments");
                                            $loans = $conn->query("SELECT sum(l.amount + (l.amount * (p.interest_percentage/100))) as total FROM loan_list l inner join loan_plan p on p.id = l.plan_id where l.status >= 2");
                                            $loans =  $loans->num_rows > 0 ? $loans->fetch_array()['total'] : "0";
                                            $payments =  $payments->num_rows > 0 ? $payments->fetch_array()['total'] : "0";
                                            echo number_format($loans - $payments,2);
                                            ?>
                                                
                                        </div>
                                    </div>
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="index.php?page=loans">View Loan List</a>
                                <div class="small text-white">
                                    <i class="fas fa-angle-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            
        </div>
        </div>
    </div>
</div>

<script>
	
</script>