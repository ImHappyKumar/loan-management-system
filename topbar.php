<style>
  <?php include 'new_UI/css/topbar.css'; ?>
</style>

<nav class="navbar">
  <div class="logo">
    <a href="index.php?page=home"><img src="new_UI/img/logo.png" alt="logo"></a>
    <h4 class="title">Loan Management System</h4>
  </div>

  <div class="ham" id="hamburger">  
  <span class="bar1"></span>  
  <span class="bar2"></span>  
  <span class="bar3"></span>  
  </div>
 </nav>

<ul class="nav-sub" id="nav-open">
  
    <!-- <li> <a href></li> -->
    <li class="list-item"><a href="index.php?page=home" class="nav-link nav-home"><span class='icon-field'><i class="fa fa-home" style="width :30px;"></i></span> Home</a></li>
    <li class="list-item"><a href="index.php?page=loans" class="nav-link nav-loans"><span class='icon-field'><i class="fa fa-file-invoice-dollar" style="width :30px;"></i></span> Loans</a></li>
    <li class="list-item"><a href="index.php?page=file_charges" class="nav-link nav-file_charges"><span class='icon-field'><i class="fa fa-file"  style="width :30px;"></i></span> File Charges</a></li>
    <li class="list-item"><a href="index.php?page=payments" class="nav-link nav-payments"><span class='icon-field'><i class="fa fa-money-bill"  style="width :30px;"></i></span> Payments</a></li>
    <li class="list-item"><a href="index.php?page=expenditure" class="nav-link nav-expenditure"><span class='icon-field'><i class="fa fa-money-bill-wave"  style="width :30px;"></i></span> Expenditure</a></li>
    <li class="list-item"><a href="index.php?page=pending_payments" class="nav-link nav-pending_payments"><span class='icon-field'><i class="fa fa-credit-card"  style="width :30px;"></i></span> Pending Payments</a></li>
    <li class="list-item"><a href="index.php?page=borrowers" class="nav-link nav-borrowers"><span class='icon-field'><i class="fa fa-user-friends"  style="width :30px;"></i></span> Borrowers</a></li>
    <?php if($_SESSION['login_type'] == 1): ?>
      <li class="list-item"><a href="index.php?page=plan" class="nav-link nav-plan"><span class='icon-field'><i class="fa fa-list-alt"  style="width :30px;"></i></span> Loan Plans</a>	</li>
      <li class="list-item"><a href="index.php?page=loan_type" class="nav-link nav-loan_type"><span class='icon-field'><i class="fa fa-th-list"  style="width :30px;"></i></span> Loan Types</a></li>
      <li class="list-item"><a href="index.php?page=users" class="nav-link nav-users"><span class='icon-field'><i class="fa fa-users"  style="width :30px;"></i></span> Users</a></li>
    <?php endif; ?>
    <li class="list-item"><a href="ajax.php?action=logout" class="nav-link nav-users"><span class='icon-field'><i class="fa fa-power-off"  style="width :30px;"></i> <?php echo $_SESSION['login_name'] ?> </a></li>
</ul> 


 <script>
  <?php include 'new_UI/js/topbar.js'; ?>
 </script>