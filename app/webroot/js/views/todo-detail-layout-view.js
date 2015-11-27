//詳細画面用レイアウトビュー
define(function(require) {
	var TodoDetailItemView = require('views/todo-detail-item-view');
	var TodoModel = require('models/todo-model');
	var UserCollection = require('collections/user-collection');

	var TodoDetailLayoutView = Marionette.LayoutView.extend({
		//テンプレート
		template : '#todo-detail-layout-template',

		regions : {
			itemRegion : '#todo-item',
		},

		onRender: function () {
			//Todoを取得
 			this.todoModel = new TodoModel({
 				id : this.options.modelId
 			});
 			var todoFetching = this.todoModel.fetch({reset : true});
			//ユーザ一覧取得
			this.userCollection = new UserCollection();
			var userFetching = this.userCollection.fetch();
			$.when(
				todoFetching,
				userFetching
			).done(function(){
				this.showItem(this.todoModel, this.userCollection);
			}.bind(this));
		},

		showItem: function (todoModel, userCollection) {
 			this.itemRegion.show(new TodoDetailItemView({
 				model : todoModel,
 				userList : userCollection.models
 			}));
 		},

	});
	return TodoDetailLayoutView;
});