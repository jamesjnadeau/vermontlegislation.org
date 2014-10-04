<?php

//echo '<pre>'.print_r($bills, true).'</pre>';
//echo "<h3>here</h3>";

echo '<div class="list-group">';
foreach($bills as $bill)
{
	$bill = $bill['Bill'];
	if(isset($bill['title']))
	{
		?>
			<a href="/bills/view/<?php echo urlencode($bill['id']); ?>" class="list-group-item">
				<h4 class="list-group-item-heading">
					<?php 
					echo ucwords(strtolower($bill['title'])); 
					/*echo $this->Html->link
					($bil['title'],
						array
						(
							'controller' => 'bills', 
							'action' => 'view', 
							$bill['id']
						)
					);*/
					?>
				</h4>
				<p class="list-group-item-text">
					<h6>
						<?php
							switch($bill['chamber'])
							{
								case 'upper';
									echo '<span title="Upper House" class="glyphicon glyphicon-arrow-up"></span>';
									break;
								case 'lower';
									echo '<span title="Lower House" class="glyphicon glyphicon-arrow-down"></span>';
									break;
								default;
									echo $action['actor'].' ';
									break;
							}
						?>
						<span class="glyphicon glyphicon-time"></span> 
						<?php echo date( 'l jS \of F Y', strtotime($bill['updated_at']));; ?>
					</h6>
					<p><?php echo $bill['bill_id']; ?></p>
				</p>
			</a>
		<?php
	}
	else
	{
		echo '<pre>'.print_r($bill, true).'</pre>';
	}
}
echo '</div>';

echo '<div class="center-block">';
	echo $this->Paginator->pagination(array(
		'ul' => 'pagination pagination-lg pull-right'
	));
echo '</div>';
