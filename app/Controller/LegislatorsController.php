<?php
class LegislatorsController extends AppController {
	
	public $uses = array('Openstate');
	
	public function index()
	{
		//makes call to geo_lookup via javascript in view
	}
	
	public function view($id = null) 
	{
		$legislator = $this->Openstate->find('all', array(
				'sub_folder' => 'legislators/'.urlencode($id),
				'autocache' => true,
			));
		
		$this->set('legislator', $legislator['Openstate']);
	}
	
	public function geo_lookup()
	{
		$this->layout='ajax';
		if ($this->request->is('ajax')) 
		{
			
			$request = $this->request->data;
		}
		else
		{
			$request = $this->request->query;
		}
		
		$result = $this->Openstate->find('all', array(
				'sub_folder' => 'legislators/geo/',
				'autocache' => true,
				'conditions' => array( 
					'lat'=> $request['lat'], 
					'long'=> $request['long']
					)
			));
		
		$this->set('result', $result['Openstate']);
	}
}
