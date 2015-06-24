<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>シンプルTODOアプリ</title>
</head>
<body>
	<!-- ヘッダ -->
	<div id="header"></div>
	<!-- コンテンツ -->
	<div id="main"></div>
	
	<!-- ヘッダのテンプレート -->
	<script type="text/template" id="header-template">
	<p>ユーザ：<%- username %>（<%- name %>）　<input type="button" id="logout" value="ログアウト"></input></p>
	<hr>
	</script>

	<!-- TODO一覧表示のレイアウトテンプレート -->
	<script type="text/template" id="todo-layout-template">
	<h1>TODOリスト</h1>
	<div id="todo-lists"></div>
	</script>

	<!-- TODO一覧表示のテンプレート -->
	<script type="text/template" id="todo-composite-template">
	<textarea style="width:300px;height:50px"id="new-todo" placeholder="Todo?" autofocus></textarea>
    <select name="assignee" id="user-list">
	<input type="button" id="addTodo" value="追加">
	<hr>
	<div>
		<table border="1" width="350px">
            <thead>
                <tr>
                    <td>完了</td>
                    <td>ToDo</td>
                    <td>オーナ</td>
                    <td>担当</td>
                    <td colspan="2"></td>
                </tr>
            </thead>
			<tbody></tbody>
		</table>
	</div>
	</script>

	<!-- TODO一行分のテンプレート（上のtbody部分に挿入される） -->
	<script type="text/template" id="todo-item-template">
	<td><input type="checkbox" class="toggle" <%- status === '1' ? 'checked' : '' %>></td>
	<td style="margin:0px">
		<span class="todo-edit" style="margin:0px"><%- todo %></span>
	</td>
    <td>
		<span><%- Owner.name %></span>
    </td>
    <td>
		<span><%- Assignee.name %></span>
    </td>
	<td>
		<a class="remove-link" href="#">削除</a>
    </td>
	<td>
		<a class="detail-link" href="#todo-lists/<%- id %>">詳細</a>
	</td>
	</script>

	<!-- 詳細画面のレイアウトテンプレート -->
	<script type="text/template" id="todo-detail-layout-template">
	<div id="todo-item"></div>
	</script>

	<!-- 詳細画面の表示内容テンプレート -->
	<script type="text/template" id="todo-detail-item-template">
	<h2>Todo #<%- id %></h2>
	<div>
	<textarea style="width:300px;height:50px" id="edit-todo" autofocus placeholder="Todo?"><%- todo %></textarea>
    <select name="assignee" id="user-list">
    </select>
	<input type="button" id="updateTodo" value="更新"></input>
	<input type="button" id="updateCancel" value="キャンセル"></input>
	</div>
	</script>

	<!-- ログイン画面テンプレート -->
	<script type="text/template" id="login-layout-template">
	<h2>ログイン</h2>
	<div>
	<p>ユーザ名　：<input type="text" id="username" placeholder="username" autofocus></input></p>
	<p>パスワード：<input type="password" id="password" placeholder="password"></input></p>
	<input type="button" id="login" value="ログイン"></input>
	</div>
	<hr>
	<h2>ユーザ登録</h2>
	<div>
	<p>ユーザ名　：<input type="text" id="signup-username" placeholder="username"></input></p>
	<p>氏名　　　：<input type="text" id="signup-name" placeholder="name"></input></p>
	<p>パスワード：<input type="password" id="signup-password" placeholder="password"></input></p>
	<input type="button" id="signup" value="登録"></input>
	</div>
	</script>

	<!-- require -->
	<script type="text/javascript" src="js/require-config.js"></script>
	<script type="text/javascript" src="js/lib/require.js" data-main="main.js"></script>

</body>
</html>
