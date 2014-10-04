<?php

App::uses('HttpSocket', 'Network/Http');

class OpenstateSource extends DataSource {

/**
 * An optional description of your datasource
 */
    public $description = 'A far away datasource';

/**
 * Our default config options. These options will be customized in our
 * ``app/Config/database.php`` and will be merged in the ``__construct()``.
 */
    public $config = array(
        'apiKey' => '',
    );
	
	private $url = 'http://openstates.org/api/v1/';
	
/**
 * If we want to create() or update() we need to specify the fields
 * available. We use the same array keys as we do with CakeSchema, eg.
 * fixtures and schema migrations.
 */
    protected $_schema = array(
        'id' => array(
            'type' => 'integer',
            'null' => false,
            'key' => 'primary',
            'length' => 11,
        ),
        'name' => array(
            'type' => 'string',
            'null' => true,
            'length' => 255,
        ),
        'message' => array(
            'type' => 'text',
            'null' => true,
        ),
    );

/**
 * Create our HttpSocket and handle any config tweaks.
 */
    public function __construct($config) {
        parent::__construct($config);
        $this->Http = new HttpSocket();
    }

/**
 * Since datasources normally connect to a database there are a few things
 * we must change to get them to work without a database.
 */

/**
 * listSources() is for caching. You'll likely want to implement caching in
 * your own way with a custom datasource. So just ``return null``.
 */
    public function listSources($data = null) {
        return null;
    }

/**
 * describe() tells the model your schema for ``Model::save()``.
 *
 * You may want a different schema for each model but still use a single
 * datasource. If this is your case then set a ``schema`` property on your
 * models and simply return ``$model->schema`` here instead.
 */
    public function describe($model) {
        return $this->_schema;
    }

/**
 * calculate() is for determining how we will count the records and is
 * required to get ``update()`` and ``delete()`` to work.
 *
 * We don't count the records here but return a string to be passed to
 * ``read()`` which will do the actual counting. The easiest way is to just
 * return the string 'COUNT' and check for it in ``read()`` where
 * ``$data['fields'] === 'COUNT'``.
 */
    public function calculate(Model $model, $func, $params = array()) {
        return 'COUNT';
    }

/**
 * Implement the R in CRUD. Calls to ``Model::find()`` arrive here.
 */
    public function read(Model $model, $queryData = array(),
        $recursive = null) {
        /**
         * Here we do the actual count as instructed by our calculate()
         * method above. We could either check the remote source or some
         * other way to get the record count. Here we'll simply return 1 so
         * ``update()`` and ``delete()`` will assume the record exists.
         */
        if ($queryData['fields'] === 'COUNT') {
            return array(array(array('count' => 1)));
        }
        
        //throw new CakeException('<pre>'.print_r($queryData, true).'</pre>');
        
        
		/*
		if(isset($queryData['page']))
		{
			$queryData['conditions']['page'] = $queryData['page'];
			$queryData['conditions']['per_page'] = 10;
			
		}
		*/
        
        /**
         * Now we get, decode and return the remote data.
         */
        $queryData['conditions']['apikey'] = $this->config['apiKey'];
        $response = $this->Http->get(
            $this->url.$queryData['sub_folder'].'/',
            //$queryData['conditions']
            http_build_query($queryData['conditions'])
        );
        
        //debug( $this->url.$queryData['sub_folder'].'/' );
        //debug($this->url.$queryData['sub_folder'].'?'. http_build_query($queryData['query']));
        //throw new CakeException('<pre>'.print_r($response, true).'</pre>');
        $res = json_decode($response->body, true);
        
        
        
		if (is_null($res)) 
		{
			if (!function_exists('json_last_error_msg')) 
			{
				function json_last_error_msg() {
					static $errors = array(
						JSON_ERROR_NONE             => null,
						JSON_ERROR_DEPTH            => 'Maximum stack depth exceeded',
						JSON_ERROR_STATE_MISMATCH   => 'Underflow or the modes mismatch',
						JSON_ERROR_CTRL_CHAR        => 'Unexpected control character found',
						JSON_ERROR_SYNTAX           => 'Syntax error, malformed JSON',
						JSON_ERROR_UTF8             => 'Malformed UTF-8 characters, possibly incorrectly encoded'
					);
					$error = json_last_error();
					return array_key_exists($error, $errors) ? $errors[$error] : "Unknown error ({$error})";
				}
			}
			$error = json_last_error_msg();
			//debug($response);
			$query = $this->url . $queryData['sub_folder'];
			if(is_array($queryData['conditions']));
				$query .= '?'. http_build_query($queryData['conditions']);
			throw new CakeException($error.' from '.$query);
			
		}
		return array($model->alias => $res);
    }
    
    
    /**
	 * Overridden paginate method - group by week, away_team_id and home_team_id
	 */
	public function paginate($conditions, $fields, $order, $limit, $page = 1,
	    $recursive = null, $extra = array()) {
		
	    $recursive = -1;
	    return $this->find(
	        'all',
	        compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive', 'group')
	    );
	}
    
    public function paginateCount($conditions = null, $recursive = 0, $extra = array()) 
	{
		$this->recursive = $recursive;
		return 1000;
		return count($results);
	}

/**
 * Implement the C in CRUD. Calls to ``Model::save()`` without $model->id
 * set arrive here.
 */
    public function create(Model $model, $fields = null, $values = null) {
        $data = array_combine($fields, $values);
        $data['apiKey'] = $this->config['apiKey'];
        $json = $this->Http->post('http://example.com/api/set.json', $data);
        $res = json_decode($json, true);
        if (is_null($res)) {
            $error = json_last_error();
            throw new CakeException($error);
        }
        return true;
    }

/**
 * Implement the U in CRUD. Calls to ``Model::save()`` with $Model->id
 * set arrive here. Depending on the remote source you can just call
 * ``$this->create()``.
 */
    public function update(Model $model, $fields = null, $values = null,
        $conditions = null) {
        return $this->create($model, $fields, $values);
    }

/**
 * Implement the D in CRUD. Calls to ``Model::delete()`` arrive here.
 */
    public function delete(Model $model, $id = null) {
        $json = $this->Http->get('http://example.com/api/remove.json', array(
            'id' => $id[$model->alias . '.id'],
            'apiKey' => $this->config['apiKey'],
        ));
        $res = json_decode($json, true);
        if (is_null($res)) {
            $error = json_last_error();
            throw new CakeException($error);
        }
        return true;
    }

}
