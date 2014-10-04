<?php // APP/Controller/UsersController.php:
class UsersController extends AppController 
{
	public $uses = array('Users');
	public function opauth_complete() 
	{
		//debug($this->data);
		
		if($this->data['validated'] == true)
		{
			$this->opauth_success();
			$this->set('success', true);
		}
		else
		{
			debug('validated == '.$this->data['validated']);
			$this->set('success', false);
		}
		
	}
	
	public function index()
	{
		if($this->Session->read('Logged_In'))
		{
			debug($this->Session->read('User'));
		}
	}
	
	public function opauth_success()
	{
		$data = $this->data;
		
		//Mark user as logged in
		$this->Session->write('Logged_In', true);
		
		//check for existing user
		$email = $data['auth']['info']['email'];
		$search_for = array
		(
			'conditions' => array('Users.email' => $email)
		);
		
		$existing_user = $this->Users->find('first', $search_for);
		
		//debug($existing_user);
		if(!empty($existing_user))
		{
			$existing_user['provider'] = $data['auth']['provider'];
			$existing_user['token'] = $data['auth']['credentials']['token'];
			$existing_user['token_expires'] = $data['auth']['credentials']['expires'];
			$this->Users->save($existing_user);
		}
		else
		{//create new user
			$user = array
			(
				'email' => $data['auth']['info']['email'],
				'name' => $data['auth']['info']['name'],
				'first_name' => $data['auth']['info']['first_name'],
				'last_name' => $data['auth']['info']['last_name'],
				'image' => $data['auth']['info']['image'],
				'provider' => $data['auth']['provider'],
				'token' => $data['auth']['credentials']['token'],
				'token_expires' => $data['auth']['credentials']['expires'],
				'oauth_response' => serialize($data),
			);
			
			$this->Users->create();
			$this->Users->save($user);
		}
		
		//Set session values to use later
		$this->Session->write('User.email', $data['auth']['info']['email']);
		$this->Session->write('User.name', $data['auth']['info']['name']);
		$this->Session->write('User.first_name', $data['auth']['info']['first_name']);
		$this->Session->write('User.last_name', $data['auth']['info']['last_name']);
		$this->Session->write('User.image', $data['auth']['info']['image']);
	}
}
