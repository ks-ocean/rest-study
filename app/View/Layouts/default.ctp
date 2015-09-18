<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- Bootstrap CSS -->
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- Application CSS -->
<link href="css/app.css" rel="stylesheet">
<title>シンプルTODOアプリ</title>
</head>
<body>
	<!-- ヘッダ -->
	<div id="header" class="container"></div>
	<!-- コンテンツ -->
	<div id="main" class="container"></div>

	<!-- ヘッダのテンプレート -->
	<script type="text/template" id="header-template">
		<div>
			<div class="row header">
				<div class="form-group col-xs-12">
					<form class="form-inline pull-right">
						<label for="logout"><%- username %>（<%- name %>）</label>
						<input type="button" class="btn btn-default btn-sm" id="logout" value="ログアウト">
					</form>
				</div>
			</div>
		</div>
	</script>

	<!-- TODO一覧表示のレイアウトテンプレート -->
	<script type="text/template" id="todo-layout-template">
		<div>
			<h1>TODOリスト</h1>
			<div id="todo-lists"></div>
		</div>
	</script>

	<!-- TODO一覧表示のテンプレート -->
	<script type="text/template" id="todo-composite-template">
	<div class="row">
		<div class="col-xs-12">
			<span class="row form-inline">
				<div class="input-group col-sm-6 col-xs-12">
					<label for="new-todo" class="visible-xs">Todo</label>
					<textarea class="form-control todo-item-text" rows="3"
						id="new-todo" placeholder="Todo?" autofocus
					></textarea>
				</div>
				<div class="input-group col-sm-3 col-xs-12">
					<label for="user-list" class="visible-xs">担当者</label> <select
						class="form-control" name="assignee" id="user-list"
					></select>
				</div>
				<div class="input-group col-sm-3 col-xs-12">
					<label class="visible-xs"></label> <input type="button"
						id="addTodo" class="btn btn-primary btn-md todo-action-button"
						value="追加"
					>
				</div>
			</span>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr class="success">
						<th class="col-sm-6" colspan="2">ToDo</th>
						<th class="col-sm-2">オーナ</th>
						<th class="col-sm-2">担当</th>
						<th class="col-sm-2" colspan="2"></th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-6">
			<a href="/rest-study/todo_lists/download" class="alert alert-default pull-left" id="downloadTodo">TODOリストをダウンロード</a>
		</div>
		<div class="col-xs-6">
			<form class="form-inline">
				<input type="file" id="uploadFile" name="uploadFile" multiple class="form-control">
				<a class="btn btn-info pull-right" id="uploadButton">TODOリストファイルアップロード</a>
			</form>
		</div>
	</div>
	</script>

	<!-- TODO一行分のテンプレート（上のtbody部分に挿入される） -->
	<script type="text/template" id="todo-item-template">
		<td colspan="2">
			<div class="checkbox">
				<label class="todo-item-text">
					<input type="checkbox" class="toggle" <%- status === '1' ? 'checked' : '' %>><%- todo %>
				</label>
			</div>
		</td>
		<td>
			<span><%- Owner.name %></span>
		</td>
		<td>
			<span><%- Assignee.name %></span>
		</td>
		<td class="text-center">
			<div class="btn-group">
				<a class="btn btn-danger remove-link todo-item-button" href="#">削除</a>
			</div>
		</td>
		<td>
			<div class="btn-group">
				<a class="btn btn-success detail-link todo-item-button" href="#todo-lists/<%- id %>">詳細</a>
			</div>
		</td>
	</script>

	<!-- 詳細画面のレイアウトテンプレート -->
	<script type="text/template" id="todo-detail-layout-template">
		<div id="todo-item" class="container"></div>
	</script>

	<!-- 詳細画面の表示内容テンプレート -->
	<script type="text/template" id="todo-detail-item-template">
		<div class="row">
			<div class="col-xs-12">
				<h2>Todo #<%- id %></h2>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div>
					<form role="form">
						<div class="row">
							<div class="form-group col-sm-6">
								<label for="edit-todo">Todo</label>
								<textarea class="form-control todo-item-text" rows="6" id="edit-todo" autofocus placeholder="Todo?"><%- todo %></textarea>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-sm-3">
								<label for="user-list">担当者</label>
								<select class="form-control"  name="assignee" id="user-list">
								</select>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-xs-12">
								<input type="button" class="btn btn-primary todo-action-button" id="updateTodo" value="更新"></input>
								<input type="button" class="btn btn-default todo-action-button" id="updateCancel" value="キャンセル"></input>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</script>

	<!-- ログイン画面テンプレート -->
	<script type="text/template" id="login-layout-template">
		<form role="form">
			<h2>ログイン</h2>
			<div class="row">
				<div class="col-sm-1">
				</div>
				<div class="form-group col-sm-5">
					<label for="username">ユーザ名</label>
					<input class="form-control" type="text" id="username" placeholder="username" autofocus></input>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-1">
				</div>
				<div class="form-group col-sm-5">
					<label for="password">パスワード</label>
					<input class="form-control" type="password" id="password" placeholder="password"></input>
				</div>
				</div>
			<div class="row">
				<div class="col-sm-1">
				</div>
				<div class="form-group col-sm-12">
					<input type="button" class="btn btn-success todo-action-button" id="login" value="ログイン"></input>
				</div>
			</div>
		</form>
		<form role="form">
			<h2>ユーザ登録</h2>
			<div class="row">
				<div class="col-sm-1">
				</div>
				<div class="form-group col-sm-5">
					<label for="username">ユーザ名</label>
					<input class="form-control" type="text" id="signup-username" placeholder="username"></input>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-1">
				</div>
				<div class="form-group col-sm-5">
					<label for="password">氏名</label>
					<input class="form-control" type="text" id="signup-name" placeholder="name"></input>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-1">
				</div>
				<div class="form-group col-sm-5">
					<label for="password">パスワード</label>
					<input class="form-control" type="password" id="signup-password" placeholder="password"></input>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-1">
				</div>
				<div class="form-group col-sm-12">
					<input type="button" class="btn btn-warning todo-action-button" id="signup" value="登録"></input>
				</div>
			</div>
		</form>
	</script>

	<!-- require -->
	<script type="text/javascript" src="js/require-config.js"></script>
	<script type="text/javascript" src="js/lib/require.js"
		data-main="main.js"
	></script>

</body>
</html>
