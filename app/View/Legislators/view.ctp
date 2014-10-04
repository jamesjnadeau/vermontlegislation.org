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
				<?php echo ucwords(strtolower($legislator['full_name'])); ?> 
				<small class="pull-right" ><a href="<?php echo $legislator['sources'][0]['url']; ?>" target="_blank" >Source</a></small>
			</h1>
		</div>
		<?php
			$panel_color = 'default';
			if($legislator['party'] == 'Democratic')
				$panel_color = 'primary';
			elseif($legislator['party'] == 'Republican')
				$panel_color = 'danger';
		?>
		<div class="panel panel-<?php echo $panel_color; ?>" >
			<div class="panel-heading">
				<?php echo $legislator['party']; ?>
			</div>
			<div class="panel-body">
				<?php
					echo $legislator['email'].'<br/>';
					foreach($legislator['offices'] as $address)
					{
						echo '<address>';
						echo '<b>'.$address['name'].'</b><br/>';
						echo $address['address'].'<br/>';
						echo $address['phone'];
						echo '</address>';
					}
				?>
			</div>
		</div>
	</div>
</div>

<?php

function legisltor_display_role($role)
{
	$icon = 'check';
	$icon_swatch = '';
	
	//logic to deal with the mess of info possible
	
	if(isset($role['type']))
	{
		switch($role['type'])
		{
			case 'member';
				$icon = 'user';
				$icon_swatch = 'warning';
				break;
			
			case 'committee member';
				$icon_swatch = 'info';
				break;
		}
	}
	
	if(isset($role['position']) 
		&& $role['position'] == 'clerk')
		$icon = 'pencil';
	
	
	
	$title = '';
	if(isset($role['chamber']) && !isset($role['position']) )
		$title .= ucfirst($role['chamber']).' ';
	if(isset($role['district']))
		$title .= $role['district'].' ';
	if(isset($role['committee']))
		$title .= '<a href="/committees/view/'.$role['committee_id'].'" >'.$role['committee'].'</a> ';
	
	if(isset($role['position']))
		$title = ucfirst($role['position']).' '.$title;
	
	?>
	<li class="timeline-inverted" >
		<div class="timeline-badge <?php echo $icon_swatch; ?>"><i class="glyphicon glyphicon-<?php echo $icon; ?>"></i></div>
		<div class="timeline-panel">
			<div class="timeline-heading">
				<h4 class="timeline-title">
					<?php 
						echo $title; 
					?>
				</h4>
				<p>
					<small class="text-muted">
						
						<span class="glyphicon glyphicon-time"></span> 
						<?php echo $role['term']; ?>
					</small>
					
				</p>
			</div>
			<div class="timeline-body">
				
				<?php 
					debug($role);
				?>
			</div>
		</div>
	</li>
<?php
}

$past_roles_count = 0;
ob_start();
	
	
	foreach($legislator['old_roles'] as $term)
	{ 
		foreach($term as $role)
		{
			$past_roles_count++;
			legisltor_display_role($role);
		}
	}
$past_roles = ob_get_clean();

$roles_count = 0;
ob_start();
	
	
	foreach($legislator['roles'] as $role)
	{ 
		{
			$roles_count++;
			legisltor_display_role($role);
		}
	}
$roles = ob_get_clean();

?>


<div class="row" >
	<div class="col-md-6" >
		
		<div class="panel panel-default" >
			<div class="panel-heading">
				<h2 class="panel-title collapsed" data-toggle="collapse" href="#roles">
					Current Roles
					<span class="badge badge-info pull-right">
						<?php echo $roles_count; ?>
					</span>
				</h2>
			</div>
			
			<div id="roles" class="collapse in">
				<div class="" >
					<ul class="timeline">
						<?php echo $roles; ?>
					</ul>
				</div>
			</div>
		</div>
		
		
	</div>
	<div class="col-md-6" >
		<div class="panel panel-default" >
			<div class="panel-heading">
				<h2 class="panel-title collapsed" data-toggle="collapse" href="#past_roles">
					Past Rolls
					<span class="badge badge-info pull-right">
						<?php echo $past_roles_count; ?>
					</span>
				</h2>
			</div>
			
			<div id="past_roles" class="collapse in">
				<div class="" >
					<ul class="timeline">
						<?php echo $past_roles; ?>
					</ul>
				</div>
			</div>
		</div>
		
	</div>
</div>
<img src="<?php echo $legislator['photo_url']; ?>" />
<?php 
debug($legislator);
