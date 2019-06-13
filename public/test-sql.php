<?php
$format = intval($_GET['format']);
if ($format === 1) {
	$phpInput = file_get_contents("php://input");
	$phpInputJson = json_decode($phpInput);
	echo json_encode(array('code' => 0, 'data' => json_encode($phpInputJson, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)));
	exit;
}
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>接口测试</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width">
	<link href="https://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.css" rel="stylesheet">
	<style>
		.container {
			height: 100%;
		}
	</style>
</head>
<body>

<div class="container">
	<form>
		<div class="form-group">
			<label for="url">接口</label>
			<input type="text" class="form-control" id="url" value="">
		</div>
		<div class="form-group">
			<label for="sql">SQL</label>
			<textarea class="form-control" rows="7" id="sql"></textarea>
		</div>
		<div class="form-group">
			<label for="list">是否查询</label>
			<select class="form-control" id="list">
				<option value="0" selected="selected">否</option>
				<option value="1">是</option>
			</select>
		</div>
		<div class="form-group">
			<label for="api-result">接口结果</label>
			<textarea class="form-control" rows="7" id="api-result"></textarea>
		</div>
		<button type="button" class="btn btn-default" id="api-submit">提交</button>
	</form>
</div>

<script src="https://cdn.bootcss.com/zepto/1.1.6/zepto.min.js"></script>
<script>

	$('#api-submit').click(function () {
		var that = this;
		if (that.innerHTML === '提交中...') {
			return;
		}
		that.innerHTML = '提交中...';
		request($('#url').val(), {sql: $('#sql').val(), list: parseInt($('#list').val())}, {
			success: function (result) {
				request('test-sql.php?format=1', result, function (resultCode) {
					that.innerHTML = '提交';
					$('#api-result').val(resultCode.data);
				});
			},
			failed: function (result) {
				request('test-sql.php?format=1', result, function (resultCode) {
					that.innerHTML = '提交';
					$('#api-result').val(resultCode.data);
				});
			},
			error: function () {
			},
			complete: function () {
			}
		});
	});

	/**
	 * 接口请求
	 * @param api
	 * @param params
	 * @param options
	 */
	function request(api, params, options) {
		if (options === undefined) {
			options = function () {
			};
		}
		if (typeof options.start === 'function') {
			options.start();
		}
		$.ajax({
			type: options.type || 'POST',
			url: api,
			contentType: 'application/json; charset=utf-8',
			data: JSON.stringify(params),
			dataType: 'json',
			timeout: options.timeout || 30 * 1000,
			success: function (result) {
				var code = result.code;
				var message = result.message;
				if (code === 0) {
					if (typeof options === 'function') {
						options(result);
					} else if (typeof options.success === 'function') {
						options.success(result);
					}
				} else {
					if (typeof options.failed === 'function') {
						options.failed(result);
					} else {
						alert(message);
					}
				}
			},
			error: function (xhr) {
				if (typeof options.error === 'function') {
					options.error(xhr);
				} else {
					alert('网络错误，请稍后重试');
				}
			},
			complete: function (xhr, status) {
				if (status === 'timeout') {
					alert('网络超时，请稍后重试！');
					return;
				}
				if (typeof options.complete === 'function') {
					options.complete(xhr);
				}
			}
		});
	}
</script>

</body>
</html>
