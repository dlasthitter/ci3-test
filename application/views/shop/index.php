<table>
	<thead>	
		<tr>
    		<th>Picture</th>
    		<th>Item Details</th>
        </tr>
    </thead>
    <tbody>
	<?php
		if (!$items->num_rows){
				echo "<tr><td colspan='99'>No items found.</td></tr>";
		}
		else{
			foreach ($items->result() as $key => $val):
	?>
				<tr class="<?=$class?>">
	          		<td></td>
	          		<td>
	          			<?= $val->name ?>
	          		</td>
          		</tr>
    <?php	
			endforeach;
		}
	?>
    </tbody>
</table>

