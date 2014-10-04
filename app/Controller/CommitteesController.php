<?php
class CommitteesController extends AppController {
	
	public $uses = array('Openstate');
	
	public function index()
	{
		$committees = $this->Openstate->find('all', array(
				'sub_folder' => 'committees',
				'autocache' => true,
				'conditions' => array( 
					'state'=> 'vt', 
					)
			));
		
		$this->set('committees', $committees['Openstate']);
	}
	
	public function view($id = null) 
	{
		$committee = $this->Openstate->find('all', array(
				'sub_folder' => 'committees/'.urlencode($id),
				'autocache' => true,
			));
		
		$this->set('committee', $committee['Openstate']);
	}
}
