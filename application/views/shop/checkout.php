<?php if (empty($orders)) : ?>
	No item(s) found.
<?php else : ?>
	<form action="shop/charge" method="post">
		
		
		<table class="table">	
			<tr>
				<th>Item</th>
				<th>Quantity</th>
				<th>Price</th>
				<th>Sub-total</th>
			</tr>	
			<?php foreach($orders['items'] as $id => $order) :?>
				<tr>
					<td><?= $order['item']->name ?></td>
					<td><?= $order['qty'] ?></td>
					<td><?= $order['item']->price ?></td>
					<td><?= $order['item']->price * $order['qty'] ?></td>
				</tr>
			<?php endforeach ?>

			<tr>
				<th>Total</th>
				<th></th>
				<th></th>
				<th><?=$orders['total']?></th>
			</tr>
		</table>

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