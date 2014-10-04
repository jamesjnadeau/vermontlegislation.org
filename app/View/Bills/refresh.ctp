<h1>Refreshing Bills</h1>
<?php
echo '<p>Added '.$bill_count.' bills</p>';
?>
<div class="panel panel-default" >
	<div class="panel-heading">
		<h4 class="panel-title text-center collapsed" data-toggle="collapse" href="#refresh_debug">
			Debug
		</h4>
	</div>
	
	<div id="refresh_debug" class="collapse">
		<?php
			echo '<pre>'.print_r($bills, true).'</pre>';
		?>
	</div>
</div>
