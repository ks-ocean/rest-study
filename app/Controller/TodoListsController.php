<?php
App::uses('AppController', 'Controller');

class TodoListsController extends AppController {
	private $fields = array (
		'TodoList.id',
		'TodoList.todo',
		'TodoList.status',
		'Owner.id',
		'Owner.name',
		'Assignee.id',
		'Assignee.name'
	);

	public function index() {
		try {
			$param = $this->request->query;
			$conditions = array ();
			$loginUserId = $this->Auth->user()['id'];
			if (isset($param['owner'])) {
				$conditions['owner'] = $loginUserId;
			}
			if (isset($param['assignee'])) {
				$conditions['assignee'] = $loginUserId;
			}
			$query = array (
				'fields' => array (
					'TodoList.id',
					'TodoList.todo',
					'TodoList.status',
					'Owner.id',
					'Owner.name',
					'Assignee.id',
					'Assignee.name'
				),
				'order' => "TodoList.id"
			);
			$res = $this->TodoList->find('all', $query);
			// 整形
			if (count($res) > 0) {
				foreach ( $res as $key => $row ) {
				    //「ログインユーザがオーナである」フラグ
					$res[$key]['TodoList']['owned'] = $row['Owner']['id'] === $loginUserId;
				    //「ログインユーザが担当である」フラグ
					$res[$key]['TodoList']['assigned'] = $row['Assignee']['id'] === $loginUserId;
				}
			}
			$this->set(compact('res'));
			$this->set('_serialize', 'res');
			$this->logSql();
		} catch ( Exception $e ) {
			$this->logError($e, LOG_DEBUG);
		}
	}

	public function view($id = null) {
		$res = $this->TodoList->findById($id);
		$this->set(compact('res'));
		$this->set('_serialize', 'res');
	}

	public function add() {
		$data = $this->request->data;
		$data['owner'] = $this->Auth->user()['id'];
		$res = $this->TodoList->save($data);
		$this->set(compact('res'));
		$this->set('_serialize', 'res');
	}

	public function delete($id) {
		$res = $this->TodoList->delete($id, false);
		$this->set(compact('res'));
		$this->set('_serialize', 'res');
	}

	public function edit($id) {
		$this->TodoList->id = $id;
		$data = $this->request->data;
		$res = $this->TodoList->save($this->request->data);
		$res = ! empty($res);
		$this->set(compact('res'));
		$this->set('_serialize', 'res');
	}
}
