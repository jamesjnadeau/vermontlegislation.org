<?php

echo '<div class="list-group">';
foreach($committees as $committee)
{
	if(isset($committee['committee']))
	{
		?>
			<a href="/committees/view/<?php echo urlencode($committee['id']); ?>" class="list-group-item">
				<h4 class="list-group-item-heading">
					<?php 
					echo ucwords(strtolower($committee['committee'])); 
					/*echo $this->Html->link
					($bil['title'],
						array
						(
							'controller' => 'committees', 
							'action' => 'view', 
							$committee['id']
						)
					);*/
					?>
				</h4>
				<p class="list-group-item-text">
					<h6>
						<span class="glyphicon glyphicon-time"></span> Created: 
						<?php echo date( 'l jS \of F Y', strtotime($committee['created_at']));; ?>
					</h6>
				</p>
			</a>
		<?php
	}
	else
	{
		echo '<pre>'.print_r($committee, true).'</pre>';
	}
}
echo '</div>';


debug($committees);
