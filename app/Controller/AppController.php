<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $components = array (
		'RequestHandler',
		'Auth' => array (
			'authenticate' => array (
				'Form' => array (
					'passwordHasher' => 'Blowfish'
				)
			)
		)
	);

	public function beforeFilter() {
		$user = $this->Auth->user();
		if ($user === null
				&& $this->request->params['controller'] !== 'users'
				&& $this->request->params['action'] !== 'login'
				&& $this->request->params['action'] !== 'logout'
				&& $this->request->params['action'] !== 'loggedin'
				&& $this->request->params['action'] !== 'signup') {
					throw new UnauthorizedException();
		}
		//全アクションを許可
		$this->Auth->allow();
	}

	public function logError($er){
	    if (Configure::read('debug') < 2) {
	    	return;
	    }
	    $log = array();
	    $log['message'] = $er->getMessage();
	    $log['file'] = $er->getFile();
	    $log['line'] = $er->getLine();
	    $log['code'] = $er->getCode();
	    $log['queryString'] = $er->queryString;
	    $this->log($log, LOG_DEBUG);
	}

	public function logSql(){
	    if (!class_exists('ConnectionManager') || Configure::read('debug') < 2) {
	    	return;
	    }
	    $sources = ConnectionManager::sourceList();

	    $sqlLogs = array();
	    foreach ($sources as $source){
		    $db = ConnectionManager::getDataSource($source);
		    if (!method_exists($db, 'getLog')){
		    	continue;
		    }
		    $sqlLogs[$source] = $db->getLog();
	    }

	    if (empty($sqlLogs)){
	        return;
	    }
	    foreach ($sqlLogs as $source => $logInfo){
	    	$text = $logInfo['count'] > 1 ? 'queries' : 'query';
    		foreach ($logInfo['log'] as $k => $i){
    			$i += array('error' => '');
    			if (!empty($i['params']) && is_array($i['params'])) {
    				$bindParam = $bindType = null;
    				if (preg_match('/.+ :.+/', $i['query'])) {
    					$bindType = true;
    				}
    				foreach ($i['params'] as $bindKey => $bindVal) {
    					if ($bindType === true) {
    						$bindParam .= h($bindKey) . " => " . h($bindVal) . ", ";
    					} else {
    						$bindParam .= h($bindVal) . ", ";
    					}
    				}
    				$i['query'] .= " , params[ " . rtrim($bindParam, ', ') . " ]";
    			}
    			$log = array(
    				'No.' => ($k + 1),
    				'query' => h($i['query']),
    				'error' => $i['error'],
    				'affected' => $i['affected'],
    				'numRows' => $i['numRows'],
    				'took' => $i['took']
				);
    			$this->log($log, LOG_DEBUG);
    		}
	    }
	}
}
