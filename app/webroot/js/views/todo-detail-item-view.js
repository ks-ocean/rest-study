//詳細ビュー
define(function(require) {
	var TodoDetailItemView = Marionette.ItemView.extend({

		//テンプレート
		template: "#todo-detail-item-template",

		ui : {
			todoStatus   : '#edit-todo',
			updateButton : '#updateTodo',
			cancelButton : '#updateCancel',
			userList     : '#user-list'
		},

		//DOMイベントハンドラ設定
		events : {
			//更新ボタンクリック時
			'click @ui.updateButton' : 'onUpdateClick',
			//キャンセルボタンクリック時
			'click @ui.cancelButton' : 'onCancelClick',
		},

		//初期化
		initialize : function(options) {
			_.bindAll(this, 'onSaveSuccess');
			this.userList = options.userList;
		},

		onRender : function() {
			//ユーザ一覧を表示
			this.showUserList(this.ui.userList, this.userList);
			//担当者を選択状態にする
			this.ui.userList.val(this.model.attributes.assignee);
		},
		
		//ユーザ一覧を表示
		showUserList : function($list, userList){
			$.each(userList, function(index, userModel) {
				$list.append(
						"<option value='" 
						+ userModel.attributes.id + "'>"
						+ userModel.attributes.name + "</option>");
			});
		},

		//更新ボタンクリックのイベントハンドラ
		onUpdateClick : function() {
			//テキストボックスから文字を取得
			var todoString = this.ui.todoStatus.val();  // Todo
			var assigneeId = this.ui.userList.val();	// 担当者
			this.model.save({
				todo : todoString,
				assignee : assigneeId
			}, {
				silent : true,
				success : this.onSaveSuccess,
			});
		},

		//キャンセルボタンクリックのイベントハンドラ
		onCancelClick : function() {
			this.backTodoLists();
		},

		//更新成功
		onSaveSuccess : function() {
			this.backTodoLists();
		},

		//TODOリスト画面に戻る
		backTodoLists : function() {
			Backbone.history.navigate('#todo-lists', true);
		}

	});
	return TodoDetailItemView;
});