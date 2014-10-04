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
			<h1 ><?php echo ucwords(strtolower($bill['Bill']['title'])); ?></h1>
		</div>
		<div id="summaries">
			<h3 id="summaries_header" style="display: none;" >Summaries</h3>
			<?php 
				if(count($summaries))
				{
					//debug($summaries);
					
					foreach($summaries as $value)
					{
						$value = $value['BillSummary'];
						echo '<div class="well well-sm">';
							echo '<h4>'.$value['title'].'</h4>';
							echo '<p>'.$value['description'].'</p>';
						echo '</div>';
					}
					?>
					<script>
					$( document ).ready(function()
					{
						$('#summaries_header').show();
					});
					</script>
					<?php
				}
			?>
		</div>
	</div>
</div>
<hr/>
<div class="row" >
	<div class="col-md-6" >
		<div class="panel panel-default" >
			<div class="panel-heading">
				<h2 class="panel-title collapsed" data-toggle="collapse" href="#dates">
					Dates
					<span class="badge badge-info pull-right">
						<?php 
						ob_start();
							uasort($bill['Openstate']['action_dates'], function($a, $b)
							{
								$a = strtotime($a);
								$b = strtotime($b);
								if ($a == $b) 
									return 0;
								return ($a < $b) ? -1 : 1;
							});
							$action_dates_count = 0;
							echo '<ul class="list-group">';
							//echo '<h3>Dates</h3>';
							foreach($bill['Openstate']['action_dates'] as $action => $date)
							{
								if($date != NULL)
								{
									$action_dates_count++;
									echo '<li class="list-group-item" >';
									switch($action)
									{
										case 'passed_upper';
											echo '<span title="Upper House" class="glyphicon glyphicon-arrow-up"></span> Passed Upper House on ';
											break;
										case 'passed_lower';
											echo '<span title="Upper House" class="glyphicon glyphicon-arrow-down"></span> Passed Upper House on ';
											break;
										case 'first';
											echo '<span title="Upper House" class="glyphicon glyphicon-star-empty"></span> First Introduced on ';
											break;
										case 'signed';
											echo '<span title="Upper House" class="glyphicon glyphicon-thumbs-up success"></span> Signed on ';
											break;
										case 'last';
											echo '<span title="Upper House" class="glyphicon glyphicon-time"></span> Last Updated on ';
											break;
									}
									echo date( 'l jS \of F Y', strtotime($date)); //.'<br/>'
									echo '</li>';
								}
							}
							echo '</ul>';
							
							//echo '<pre>'.print_r($bill['Openstate']['versions'], true).'</pre>'; 
							$action_dates = ob_get_clean();
						?>
						<?php echo $action_dates_count; ?>
					</span>
				</h2>
			</div>
			<div id="dates" class="collapse" >
				<?php echo $action_dates; ?>
			</div>
		</div>
		<div class="panel panel-default" >
			<div class="panel-heading">
				<h2 class="panel-title collapsed" data-toggle="collapse" href="#docs">
					Documents
					<span class="badge badge-info pull-right">
						<?php echo count($bill['Openstate']['versions']); ?>
					</span>
				</h2>
			</div>
			<div id="docs" class="collapse">
				<?php
				echo '<ul class="list-group">';
					//echo '<h3>Documents</h3>';
					foreach($bill['Openstate']['versions'] as $version)
					{
						echo '<a href="'.$version['url'].'" class="list-group-item" target="_blank" ><span class="glyphicon glyphicon-download-alt"></span> '.$version['name'].'</a>';
						//debug($version);
					}
				echo '</ul>';
				//echo '<hr/>';
				?>
			</div>
		</div>
		
		<div class="panel panel-default" >
			<div class="panel-heading">
				<h2 class="panel-title collapsed" data-toggle="collapse" href="#actions">
					Action Log
					<span class="badge badge-info pull-right">
						<?php echo count($bill['Openstate']['actions']); ?>
					</span>
				</h2>
			</div>
			
			<div id="actions" class="collapse">
				<div class="" >
					<ul class="timeline">
						<?php
						uasort($bill['Openstate']['actions'], function($a, $b)
						{
							$a = strtotime($a['date']);
							$b = strtotime($b['date']);
							if ($a == $b) 
								return 0;
							return ($a < $b) ? -1 : 1;
						});
						foreach($bill['Openstate']['actions'] as $action)
						{ 
							
							$icon = 'check';
							$icon_swatch = '';
							if(isset($action['type'][0]))
							{
								switch($action['type'][0])
								{
									case 'bill:passed';
									case 'committee:passed:favorable';
									case 'governor:signed';
										$icon = 'thumbs-up';
										$icon_swatch = 'success';
										break;
									
									case 'bill:reading:1';
									case 'bill:reading:2';
										$icon = 'volume-down';
										$icon_swatch = 'warning';
										break;
									
									case 'bill:reading:3';
										$icon = 'volume-up';
										$icon_swatch = 'warning';
										break;
									
									case 'committee:referred';
										$icon = 'user';
										$icon_swatch = 'warning';
										break;
									
									case 'other';
										$icon = 'adjust';
										$icon_swatch = 'danger';
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
												echo $action['action']; 
											?>
										</h4>
										<p>
											<small class="text-muted">
												<?php
													switch($action['actor'])
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
												<?php echo date( 'l jS \of F Y', strtotime($action['date'])); ?>
											</small>
											
										</p>
									</div>
									<div class="timeline-body">
										
										<?php 
											echo '<p>'.$action['type'].'</p>';
											//echo '<pre>'.print_r($action, true).'</pre>'; 
											//debug($action['type']);
										?>
									</div>
								</div>
							</li>
						<?php
						} ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-md-6" >
		<div id="disqus_thread"></div>
		
		<script type="text/javascript">
			/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
			var disqus_shortname = 'vermontlegistaltion'; // required: replace example with your forum shortname
			var disqus_identifier = '<?php echo $_SERVER['REQUEST_URI']; ?>';
			var disqus_title = '<?php echo h($bill['Bill']['title']); ?>';
			
			/* * * DON'T EDIT BELOW THIS LINE * * */
			(function() {
				var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
				dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
				(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
			})();
		</script>
		
		<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
		<!--
		<a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
		-->
	</div>
</div>

<?php
	debug($bill['Openstate']);
?>

<!-- Modal -->
<div class="modal fade" id="modal_summary" tabindex="-1" role="dialog" aria-labelledby="modal_summary_header" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="modal_summary_header">Submit Summary</h4>
			</div>
			<div class="modal-body">
				<?php
					/*
					echo $this->Form->create('BillSummary', array('default' => false));
					// default = false sets the submit button not to submit
					// so we can use AJAX. Still works for users w/o javascript
					echo $this->Form->input('BillSummary.title');
					echo $this->Form->input('BillSummary.description');
					echo $this->Form->end('Submit');
					*/
					echo $this->Form->create('BoostCake', array(
						'inputDefaults' => array(
						'div' => 'form-group',
						'wrapInput' => false,
						'class' => 'form-control',
						'default' => false
					),
					'class' => 'well'
				)); ?>
					<fieldset>
						<legend>Describe This Bill For Others</legend>
						<?php echo $this->Form->input('BillSummary.title', array(
							'label' => 'Title',
							'placeholder' => $bill['Bill']['title'],
							'type' => 'text',
							'after' => '<span class="help-block">'.$bill['Bill']['title'].'</span>'
						)); ?>
						<?php /*echo $this->Form->input('checkbox', array(
							'label' => 'Check me out',
							'class' => false
						)); */ ?>
						<?php echo $this->Form->input('BillSummary.description', array(
							'label' => 'Description/Summary',
							'placeholder' => "Your Description...",
							//'after' => '<span class="help-block">'.$bill['Bill']['title'].'</span>'
						)); ?>
						
						<input type="hidden" name="data[BillSummary][bill_id]" value="<?php echo $bill['Bill']['id']; ?>" />
						
						<?php echo $this->Form->submit('Submit', array(
							'div' => 'form-group',
							'class' => 'btn btn-default pull-right btn-success'
						)); ?>
					</fieldset>
				<?php echo $this->Form->end(); ?>
			</div>
			<!-- 
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
			-->
		</div>
	</div>
</div>

<script>
$( document ).ready(function()
{
	$('#BoostCakeViewForm').on('submit', function(event)
	{
		$.ajax({
			type:'POST',
			async: true,
			cache: false,
			dataType: 'json',
			url: '<?php echo Router::Url(array('controller' => 'Bills','admin' => FALSE, 'action' => 'submit_summary'), TRUE); ?>',
			success: function(response) {
				$('#modal_summary').modal('hide');
				$('#summaries_header').show();
				console.log(response);
				var new_content = $('<div class="well well-sm"></div>');
				new_content.append('<h4>'+response.BillSummary.title+'</h4>');
				new_content.append('<p>'+response.BillSummary.description+'</p>');
				$('#summaries').append(new_content);
			},
			data: $('#BoostCakeViewForm').serialize()
		});
		return false;
	});
});

</script>

