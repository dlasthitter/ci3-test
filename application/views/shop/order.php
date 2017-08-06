<?php if (empty($orders)) : ?>
	No item(s) found.
<?php else : ?>
	<table class="table">	
		<tr>
			<th>Item</th>
			<th>Quantity</th>
			<th>Price</th>
			<th></th>
		</tr>		
		<?php foreach($orders['items'] as $id => $order) :?>
			<tr>
				<td><?= $order['item']->name ?></td>
				<td><?= $order['qty'] ?></td>
				<td><?= currency($order['item']->price) ?></td>
				<td><a href="javascript:void(0)" class="removeOrder" data-id='<?=$id?>'><i class="fa fa-trash"></i></a></td>
			</tr>
		<?php endforeach ?>

		<tr>
			<th>Total</th>
			<th></th>
			<th colspan="2"><?= currency($orders['total']) ?></th>
		</tr>
	</table>

	<div class="text-right">
		<a href="shop/checkout" class="btn btn-success"><i class="fa fa-cart"></i> Checkout</a>
	</div>
<?php endif; ?>