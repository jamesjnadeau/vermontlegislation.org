<div class="row" >
	<div class="col-xs-1" >
		<a href="#" class="btn btn-info btn-sm" title="Go Back" onclick="history.back(); return false;" >
			<span class="glyphicon glyphicon-fast-backward"></span>
		</a>
		<!--
		<br/><br/>
		<a href="#" class="btn btn-warning btn-sm" title="?">
			<span class="glyphicon glyphicon-bookmark"></span>
		</a>
		-->
		<br/><br/>
		<button class="btn btn-danger btn-sm" data-target="#<?php
			if($this->Session->read('Logged_In')) 
				echo 'modal_summary'; 
			else
				echo 'modal_login';
			?>" data-toggle="modal" >
			<span class="glyphicon glyphicon-new-window"></span>
		</button>
	</div>
	<div class="col-xs-11" >
		<div class="page-header" >
			<h1 >
				<?php echo ucwords(strtolower($committee['committee'])); ?> 
				<small class="pull-right" ><a href="<?php echo $committee['sources'][0]; ?>" >Source</a></small>
			</h1>
			<p><?php echo ucfirst($committee['chamber']); ?> Chamber</p>
		</div>
		
	</div>
</div>

<?php
$member_count = 0;
ob_start();
	foreach($committee['members'] as $member)
	{ 
		$member_count++;
		$icon = 'check';
		$icon_swatch = '';
		
		//logic to deal with the mess of info possible
		
		if(isset($member['role']))
		{
			switch($member['role'])
			{
				case 'member';
					$icon = 'user';
					$icon_swatch = '';
					break;
				
				case 'chair';
					$icon = 'user';
					$icon_swatch = 'danger';
					break;
				
				case 'vice chair';
					$icon = 'user';
					$icon_swatch = 'warning';
					break;
				
				case 'clerk';
					$icon = 'pencil';
					$icon_swatch = 'info';
					break;
			}
		}
		
		?>
		<li class="timeline-inverted" >
			<div class="timeline-badge <?php echo $icon_swatch; ?>"><i class="glyphicon glyphicon-<?php echo $icon; ?>"></i></div>
			<div class="timeline-panel">
				<div class="timeline-heading">
					<h4 class="timeline-title">
						<?php
							if($member['leg_id'] != NULL)
								echo '<a href="/legislators/view/'.$member['leg_id'].'" >'.$member['name'].'</a>';
							else
								echo $member['name']; 
						?>
					</h4>
					<p>
						<small class="text-muted">
							
							<span class="glyphicon glyphicon-asterisk"></span> 
							<?php echo $member['role']; ?>
						</small>
						
					</p>
				</div>
				<div class="timeline-body">
					
					<?php 
						//echo '<p>'.$action['type'].'</p>';
						//echo '<pre>'.print_r($action, true).'</pre>'; 
						//debug($member);
					?>
				</div>
			</div>
		</li>
	<?php
	}
$members = ob_get_clean();
?>
<div class="panel panel-default" >
	<div class="panel-heading">
		<h2 class="panel-title collapsed" data-toggle="collapse" href="#actions">
			Members
			<span class="badge badge-info pull-right">
				<?php echo $member_count; ?>
			</span>
		</h2>
	</div>
	
	<div id="actions" class="collapse in">
		<div class="" >
			<ul class="timeline">
				<?php echo $members; ?>
			</ul>
		</div>
	</div>
</div>

<?php 

debug($committee);


