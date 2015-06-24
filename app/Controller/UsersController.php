<?php
App::uses('AppController', 'Controller');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class UsersController extends AppController {
	private $fields = array (
		'User.id',
		'User.username',
		'User.name'
	);
	public function index() {
		try {
			$res = $this->User->find('all', array('fields' => $this->fields));
			$this->set(compact('res'));
			$this->set('_serialize', 'res');
			$this->logSql();
		} catch ( Exception $e ) {
			$this->logError($e, LOG_DEBUG);
		}
	}

	public function login() {
		$user = $this->Auth->user();
		$res = array ();
		if ($user) {
			if ($user['username'] === $this->request->data['username']) {
				$res['User'] = $user;
				$res['message'] = "ログイン済みです";
			} else {
				$res['message'] = "別のユーザがログイン済みです";
			}
		} else {
			$this->request->data['User']['username'] = $this->request->data['username'];
			$this->request->data['User']['password'] = $this->request->data['password'];
			if ($this->Auth->login()) {
				$user = $this->Auth->user();
				$res['User'] = $user;
				$res['message'] = "ログイン成功";
			} else {
				$res['message'] = "ユーザ名またはパスワードが違います";
			}
		}
		$this->set(compact('res'));
		$this->set('_serialize', 'res');
	}

	public function loggedIn() {
		$user = $this->Auth->user();
		if ($user) {
			$res['User'] = $user;
			$res['message'] = "ログイン済みです";
			$this->set(compact('res'));
			$this->set('_serialize', 'res');
		} else {
			throw new UnauthorizedException();
		}
	}

	public function logout() {
		$user = $this->Auth->user();
		$res = array ();
		if ($user !== null) {
			$this->Auth->logout();
			$res['message'] = "ログアウトしました。";
		} else {
			$res['message'] = "ログインしていません。";
		}
		$this->set(compact('res'));
		$this->set('_serialize', 'res');
	}

	public function signUp() {
		$data = $this->request->data;
		if (isset($data['password'])) {
			$passwordHasher = new BlowfishPasswordHasher();
			$data['password'] = $passwordHasher->hash($data['password']);
		}
		$res = $this->User->save($data);
		if ($res) {
			unset($res['User']['password']);
			$res['message'] = "登録しました。ログインできます！";
		} else {
			$res['message'] = "登録に失敗しました。";
		}
		$this->set(compact('res'));
		$this->set('_serialize', 'res');
	}
}
