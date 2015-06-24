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
		
		onRender : function() {
			this.userCollection = new UserCollection();
			this.listenToOnce(this.userCollection, 'reset', this.onLoadUsers, this);
			this.userCollection.fetch({
				reset : true
			});
		},

		onLoadUsers : function(userCollection){
			var todoModel = new TodoModel({
				id : this.options.modelId
			});
			//モデルのサーバからのデータ取得完了時、描画を行う
			this.listenToOnce(todoModel, 'sync', this.showItem, this);
			//サーバからデータ取得
			todoModel.fetch({
				wait : true
			});
		},

		showItem : function(todoModel) {
			this.itemRegion.show(new TodoDetailItemView({
				model : todoModel,
				userList : this.userCollection.models
			}));
		},

	});
	return TodoDetailLayoutView;
});