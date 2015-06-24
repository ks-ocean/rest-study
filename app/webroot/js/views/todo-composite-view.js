//Todo一覧表示用ビュー
define(function(require) {
	var TodoItemView = require('views/todo-item-view');

	var TodoCompositeView = Marionette.CompositeView.extend({
		template: '#todo-composite-template',

		childView : TodoItemView,

		childViewContainer : 'tbody',

		ui : {
			addTodo : '#addTodo',
			newTodo : '#new-todo',
			userList : '#user-list'
		},

		events : {
			'click @ui.addTodo' : 'onCreateTodo',
		},

		initialize: function(options){
			_.bindAll( this, 'onCreatedSuccess' );
			this.userList = options.userList;
		},

		onRender : function() {
			//ユーザ一覧を表示
			this.showUserList(this.ui.userList, this.userList);
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

		onCreateTodo : function() {
			this.collection.create(this.newAttributes(), {
		          silent:  true ,
		          success: this.onCreatedSuccess
			});
			this.ui.newTodo.val('');
		},

		newAttributes : function() {
			return {
				todo : this.ui.newTodo.val().trim(),
				status : 0,
				assignee : this.ui.userList.val()
			};
		},

		onCreatedSuccess : function(){
			this.collection.fetch({ reset : true });
		},

	});
	return TodoCompositeView;
});