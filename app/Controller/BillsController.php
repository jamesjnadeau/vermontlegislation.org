<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class BillsController extends AppController {

	public $uses = array('Openstate', 'Bill', 'BillSummary');
	
	public $components = array('Paginator');
	
	public $paginate = array(
		'limit' => 10,
		'order' => array(
			'Bill.updated_at' => 'desc'
		)
	);
	
	public function index()
	{
		$this->Paginator->settings = $this->paginate;
		
		// similar to findAll(), but fetches paged results
		$bills = $this->Paginator->paginate('Bill');
		$this->set('bills', $bills);
		
		/*
		$this->Paginator->settings = array(
			'sub_folder' => 'bills',
			'conditions' => array( 
				'state' => 'vt', 
				'sort' => 'first',
				'search_window' => 'term'
			),
		);
		
		$data = $this->Paginator->paginate('Openstate');
		$this->set('bills', $data['Openstate']);
		*/
		
		/*
		$bills = $this->Openstate->find('all', array(
			'sub_folder' => 'bills',
			'conditions' => array( 
				'state' => 'vt', 
				'sort' => 'first',
				'search_window' => 'term'
				)
		));
		
		//throw new CakeException('<pre>'.print_r($bills, true).'</pre>');
		
		$this->set('bills', $bills['Openstate']);
		*/
	}
	
	public function view($id = null) 
	{
		if (!$id) 
		{
			throw new NotFoundException(__('Invalid bill'));
		}
		
		$bill = $this->Bill->findById($id);
		
		if (!$bill) 
		{
			throw new NotFoundException(__('Invalid bill'));
		}
		
		
		
		
		
		$bill_detail = $this->Openstate->find('all', array(
			'sub_folder' => 'bills/'.$bill['Bill']['id'],
			'autocache'=> true,
			/*'conditions' => array( 
				'state' => 'vt', 
				'sort' => 'first',
				'search_window' => 'term'
				)*/
		));
		
		//debug($this->Openstate->autocache_is_from);
		
		$bill = array_merge_recursive($bill, $bill_detail);
		//$this->set('bill_detail', $bill_detail);
		
		$this->set('bill', $bill);
		
		
		$summaries = $this->BillSummary->find('all', array
		(
			'conditions' => array( 'bill_id' => $bill['Bill']['bill_id'])
		));
		
		$this->set('summaries', $summaries);
	}
	
	public function refresh()
	{
		//$this->Bill->query('TRUNCATE table bill;');
		
		$bills = $this->Openstate->find('all', array(
			'sub_folder' => 'bills',
			'conditions' => array( 
				'state' => 'vt', 
				'sort' => 'first',
				'search_window' => 'term'
				)
		));
		
		foreach($bills['Openstate'] as $bill)
		{
			$this->Bill->create();
			unset($bill['type']);
			unset($bill['subjects']);
			//$bill['bill_id'] = $bill['id'];
			//unset($bill['id']);
			$this->Bill->save($bill);
		}
		
		$this->set('bill_count', count($bills['Openstate']));
		$this->set('bills', $bills);
	}
	
	public function submit_summary() 
	{
		if ($this->request->is('ajax')) 
		{
			$this->layout='ajax';
			$this->BillSummary->create();
			// Use data from serialized form
			// print_r($this->request->data['Contacts']); // name, email, message
			//$this->render('contact-ajax-response', 'ajax'); // Render the contact-ajax-response view in the ajax layout
			if($this->BillSummary->save($this->request->data))
			{
				$this->set("result", $this->request->data);
			}
		}
	}
}
