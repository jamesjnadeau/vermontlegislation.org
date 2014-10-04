<?php
if($success)
{
	echo '<div class="alert alert-success" role="alert">
		<b>Yay!</b> You have been logged in
	</div>
	';
}
else
{
	echo '<div class="alert alert-danger" role="alert">
		<b>Error!</b> Unable to log you in
	</div>
	';
}
