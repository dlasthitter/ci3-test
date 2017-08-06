<div class="text-center">
	<h4>Paid!</h4>

	<p>Here is your receipt</p>
</div>

<div class="table-responsive">
	<table class="table">
		<tr>
			<th>Item</th>
			<th class="text-center">Price</th>
			<th class="text-center">Quantity</th>
			<th class="text-right">Sub-total</th>
		</tr>	
	<?php foreach($txn_details as $detail) : ?>
		<tr>
			<td><?= $detail->name ?></td>
			<td class="text-center" class="text-center"><?= $detail->price ?></td>
			<td class="text-center"><?= $detail->qty ?></td>
			<td class="text-right"><?= $detail->qty * $detail->price ?></td>
		</tr>
	<?php endforeach; ?>
		<tr>
			<th colspan="3" class="text-right">Total</th>
			<th class="text-right"><?= $txn->amount ?></th>
		</tr>
	</table>
</div>