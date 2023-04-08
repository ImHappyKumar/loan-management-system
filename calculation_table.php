<?php 
extract($_POST);

$daily_amount = ($amount + ($amount * ($interest/100))) / $days;
$daily_amount = sprintf('%0.2f', $daily_amount);
$penalty = $daily_amount * ($penalty/100);

?>

<input type="hidden" name="daily_amount" class="form-control text-right" step="any" id="" value="<?php echo isset($daily_amount) ? $daily_amount : '' ?>" require>
<hr>
<table width="100%">
	<tr>
		<th class="text-center" width="33.33%">Total Payable Amount</th>
		<th class="text-center" width="33.33%">Daily Payable Amount</th>
		<th class="text-center" width="33.33%">Penalty Amount</th>
	</tr>
	<tr>
		<td class="text-center"><small><?php echo number_format($daily_amount * $days,2) ?></small></td>
		<td class="text-center"><small><?php echo number_format($daily_amount,2) ?></small></td>
		<td class="text-center"><small><?php echo number_format($penalty,2) ?></small></td>
	</tr>
</table>
<hr>
