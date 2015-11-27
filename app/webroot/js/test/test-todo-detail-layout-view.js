define(function(require){
	var sinon = require('sinon');
	var TodoDetailLayoutView = require('views/todo-detail-layout-view');
	var TodoModel = require('models/todo-model');
	var loginTest = require('test/test-login');
	return function () {
		describe("TODO詳細の表示データ取得テスト", function () {
			var data;
			beforeEach(function () {
				//サーバから受信するデータ
				data = {
					TodoList: {
						id: "1",
						todo: "do somothing",
						status: "0",
						owned: false,
						assigned: false
					},
					Owner: {
						id: "1",
						name: "anonymous"
					},
					Assignee: {
						id: "1",
						name: "anonymous"
					}
				};
			});

			it('ログインチェック', function (done) {
				loginTest(done);
			});

			it("Todoとユーザ一覧取得", function (done) {
				this.timeout(10000);
				var response = {
					"TodoList": {
						"id": "67",
						"todo": "do something",
						"status": "0"
					},
					"Owner": {
						"id": "1",
						"name": "anonymous"
					},
					"Assignee": {
						"id": "1",
						"name": "anonymous"
					}
				}
				var layoutView = _createTodoDetailLayoutView();
				sinon.stub(layoutView, 'showItem', function (todoModel, userCollection) {
					todoModel.should.be.ok;
					userCollection.should.be.ok;
					layoutView.showItem.restore();
				});
				layoutView.render();
				done();
			});

			//utility
			function _createTodoDetailLayoutView(data) {
				//テンプレート
				var template = '<div><div></div></div>';
				//modelとtemplateを渡してviewを生成
				var view = new TodoDetailLayoutView({
					template: $(template),
					modelId: 1
				});
				return view;
			}
		});
	}
});


