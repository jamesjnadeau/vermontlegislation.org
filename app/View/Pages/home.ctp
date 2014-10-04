<?php
/**
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Pages
 * @since         CakePHP(tm) v 0.10.0.1076
 */

if (!Configure::read('debug')):
	throw new NotFoundException();
endif;

App::uses('Debugger', 'Utility');
?>

<h2><small>Welcome to</small></h2>

<div class="jumbotron">
  <h1>Vermont Legislation Dot Org</h1>
  <p>A place to understand and discuss what our legislators are doing.</p>
  <p><a class="btn btn-primary btn-lg" role="button" href="/bills" >See What's going on this term</a></p>
</div>
