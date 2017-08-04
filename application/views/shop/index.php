<table>
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
	          			<input type="text" name="qty[<?= $val->item_id ?>]"><br />
	          			<a href="#" class="btn btn-sm btn-primary">Add to cart</a>
	          		</td>
          		</tr>
    <?php	
			endforeach;
		}
	?>
    </tbody>
</table>

