<?php
App::uses('AppController', 'Controller');

class TodoListsControllerTest extends ControllerTestCase {
	public $fixtures = array (
		'app.todo_list',
		'app.user'
	);

	private function generateTestTarget($controller = 'TodoLists', $mockMethods = null, $mockModels = null) {
		$mocks = array ();
		if (isset($mockMethods)) {
			$mocks['methods'] = $mockMethods;
		}
		if (isset($mockModels)) {
			$mocks['models'] = $mockModels;
		}
		$mocks['components'] = array (
			'Auth' => array (
				'user'
			)
		);

		$controller = $this->generate($controller, $mocks);
		$loginUser = array (
			"id" => 1000,
			"username" => "yamada",
			"name" => "yamada"
		);
		$controller->Auth->staticExpects($this->any())->method('user')->will($this->returnValue($loginUser));
		return $controller;
	}

	public function testIndex() {
		$this->generateTestTarget();
		$result = $this->testAction('/todo_lists/1000.json', array (
			'method' => 'get'
		));
		$result = $this->vars['res'];
		$expected = array (
			"TodoList" => array (
				"id" => 1000,
				"todo" => "牛乳を買う",
				"status" => "1"
			),
			"Owner" => array (
				"id" => 1000,
				"name" => "山田太郎"
			),
			"Assignee" => array (
				"id" => 1000,
				"name" => "山田太郎"
			)
		);
		$this->assertEquals($expected, $result);
	}

	public function testUploadOKFile() {
		//一時保存されるアップロードファイル
		$postFileName = 'testUploadOKFile.txt';
		$tmpFileName = tempnam('/tmp', $postFileName);
		file_put_contents($tmpFileName, array (
			"ほげ\n",
			"12345\n"
		));
		//POSTされるフォームデータ
		$uploadFormData = array (
			'name' => $postFileName,
			'tmp_name' => $tmpFileName
		);

		//フォームデータ取得関数をモックにする
		$methods = array (
			'getUploadFileParams'
		);
		//担当者の存在チェックをモックにする
		$models = array (
			'TodoList' => array (
				'existsUser'
			)
		);

		//TodoListControllerを生成
		$controller = $this->generateTestTarget('TodoLists', $methods, $models);
		//担当者の存在チェック(モック)は常にtrueを返すようにする
		$controller->TodoList->expects($this->any())
			->method('existsUser')
			->will($this->returnValue(true));
		//フォームデータ取得関数(モック)は、上で用意したフォームデータを返すようにする
		$controller->expects($this->any())
			->method('getUploadFileParams')
			->will($this->returnValue(array($uploadFormData)));
		//テスト実行
		$result = $this->testAction('/todo_lists/upload.json', array (
			'method' => 'post'
		));
		//結果取得 / 確認
		$result = $this->vars['response'];
		$expected = '2件のTODOを登録しました。';
		$this->assertEquals($expected, $result);
	}

	public function testUploadOKandNGFile() {
		//一時保存されるアップロードファイル1
		$postFileName1 = 'testUploadOKandNGFile1.txt';
		$tmpFileName1 = tempnam('/tmp', $postFileName1);
		file_put_contents($tmpFileName1, array (
			"ほげ\n",
			"12345\n"
		));
		//一時保存されるアップロードファイル1
		$postFileName2 = 'testUploadOKandNGFile2.txt';
		$tmpFileName2 = tempnam('/tmp', $postFileName2);
		file_put_contents($tmpFileName2, array (
			"ふが\n",
			"12345\n" //これは重複でエラーになる
		));

		//POSTされるフォームデータ
		$uploadFormData = array (
			array(
				'name' => $postFileName1,
				'tmp_name' => $tmpFileName1
			),
			array(
				'name' => $postFileName2,
				'tmp_name' => $tmpFileName2
			),
		);

		//フォームデータ取得関数をモックにする
		$methods = array (
			'getUploadFileParams'
		);
		//担当者の存在チェックは除外
		$models = array (
			'TodoList' => array (
				'existsUser'
			)
		);
		//TodoListControllerを生成
		$controller = $this->generateTestTarget('TodoLists', $methods, $models);
		//担当者の存在チェック(モック)は常にtrueを返すようにする
		$controller->TodoList->expects($this->any())
			->method('existsUser')
			->will($this->returnValue(true));
		//フォームデータ取得関数(モック)は、上で用意したフォームデータを返すようにする
		$controller->expects($this->any())
			->method('getUploadFileParams')
			->will($this->returnValue($uploadFormData));
		//テスト実行
		$result = $this->testAction('/todo_lists/upload.json', array (
			'method' => 'post'
		));
		//結果取得 / 確認
		$result = $this->vars['response'];
		$this->assertEquals('3件のTODOを登録しました。', $result['errors'][0][0]);
		$this->assertEquals('以下のエラーが発生しました。', $result['errors'][1][0]);
		$this->assertEquals('file:testUploadOKandNGFile2.txt - line: 2: 同じ内容のTODOが既に登録されています。', $result['errors'][2][0]);
	}
}
