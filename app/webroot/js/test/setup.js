require([
	'mocha',
	'chai',
	'marionette',
], function (mocha, chai) {
	require([
		'js/test/tests.js'
		// 'js/test/test-todo-detail-layout-view.js',
		// 'js/test/test-todo-item-view.js',
	], function () {
		chai.should();
		mocha.run();
	});
});

