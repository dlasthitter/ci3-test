<div class="row">
	<div class="col-sm-8">
		<div class="table-responsive">
			<table class="table">
				<thead>	
					<tr>
			    		<th>Picture</th>
			    		<th>Item Details</th>
			        </tr>
			    </thead>
			    <tbody>
				<?php
					if (!$items->result_id->num_rows){
							echo "<tr><td colspan='99'>No items found.</td></tr>";
					}
					else{
						foreach ($items->result() as $key => $val):
				?>
							<tr>
				          		<td></td>
				          		<td>
				          			<?= $val->name ?> <?= $val->price ?><br />
				          			<input type="text" id="qty_<?= $val->item_id ?>" name="qty[<?= $val->item_id ?>]" class="form-control input-sm" /><br />
				          			<a href="javascript:void(0)" data-id='<?= $val->item_id ?>' class="btn btn-sm btn-primary addToCart">Add to cart</a>
				          		</td>
			          		</tr>
			    <?php	
						endforeach;
					}
				?>
			    </tbody>
			</table>
		</div>
	</div>
	<div class="col-sm-4">
		<h4>Order</h4>

		<div id="orderSummary">
			<?= $orderHtml ?>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$(this).Shop._construct();
	});
</script>
