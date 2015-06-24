//Todoデータ１件を表すモデル
define(function() {
	var TodoModel = Backbone.Model.extend({
		urlRoot : '/rest-study/todo_lists',
		parse : function(response) {
			//モデルをパース
			console.log("モデルをパース");
			console.log(response);
			var parsed = response.TodoList;
			if(response.Owner){
			  parsed.assignee = response.Assignee.id;
			  parsed.Owner = response.Owner;
			  parsed.Assignee = response.Assignee;
			}
			return parsed;
		},
		toggle : function() {
			this.set('status', this.get("status") === '1' ? '0' : '1');
			this.save();
		}
	});
	return TodoModel;
});