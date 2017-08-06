<?php if (empty($orders)) : ?>
	No item(s) found.
<?php else : ?>
	<form action="shop/charge" method="post">
		<div class="table-responsive">				
		<table class="table">	
			<tr>
				<th></th>
				<th>Item</th>
				<th class="text-center">Quantity</th>
				<th class="text-center">Price</th>
				<th class="text-right">Sub-total</th>
			</tr>	
			<?php foreach($orders['items'] as $id => $order) :?>
				<tr>
					<td><img src='items/<?= $order['item']->thumb ?>' width='100' /></td>
					<td><?= $order['item']->name ?></td>
					<td class="text-center"><?= $order['qty'] ?></td>
					<td class="text-center"><?= currency($order['item']->price) ?></td>
					<td class="text-right"><?= currency($order['item']->price * $order['qty']) ?></td>
				</tr>
			<?php endforeach ?>

			<tr>
				<th colspan="4" class="text-right">Total</th>
				<th class="text-right"><?=currency($orders['total']) ?></th>
			</tr>
		</table>
		</div>

		<div class="text-right">
			<script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
		          data-key="<?php echo $stripe['publishable_key']; ?>"
		          data-description="Order payment" 
		          data-amount="<?= $orders['total'] * 100?>"
		          data-locale="auto">
		   	</script>
		</div>
	</form>
<?php endif; ?>