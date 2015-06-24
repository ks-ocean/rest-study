//User一覧表示用コレクション
define(function(require) {
	var UserModel = require('models/user-model');

	var UserCollection = Backbone.Collection.extend({
		url : '/rest-study/users.json',
		model : UserModel,

		parse : function(response) {
			return response;
		}
	});
	
	return UserCollection;
});