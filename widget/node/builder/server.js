/**
 * Node Server 使用说明
 * 1.npm install express - 安装express框架
 * 2.npm install body-parser - 安装请求参数解析器
 * 3.npm install multer - 安装文件上传处理器
 * 4.node server.js [port=80] [dir=debug] - 运行服务，中括号中为可选参数
 */
var arguments = process.argv; // 获取命令行参数
var parameters = { // 转换后的命令行参数
	port : 8088, // 默认端口
	dir : 'debug' // 默认Web目录
};
for (var i = 2; i < arguments.length; i++) { // 解析命令行参数
	var arr = arguments[i].split('=');
	if(2 != arr.length) continue ;
	parameters[arr[0]] = arr[1];
}

var express = require('express'); // 引入express框架
var bodyParser = require('body-parser'); // 引入body-parser请求参数解析器
var multer = require('multer'); // 引入multer文件上传处理模块
var upload = multer(); // multer模块只能以中间件的形式使用

var app = express(); // 实例化express

app.use(express.static(parameters.dir)); // 注册静态资源目录
app.use(bodyParser.urlencoded({extended:false})); // 解析请求参数

app.post('/uploader.php', upload.single('file'), function (request, response) { // 模拟上传
	response.send(JSON.stringify({
		state : 200,
		message : 'Success',
		result : request.file.originalname
	}));
});

app.post('/assets/json/upload.php', function (request, response) { // 模拟上传
	response.send(JSON.stringify({
		errcode : 0,
		errmsg : '上传成功',
		returnurl : '',
		result : {
			id : 1,
			url : '/assets/img/img.png'
		}
	}));
});

var server = app.listen(parameters.port, function () { // 监听服务端口
	var host = server.address().address // 获取服务地址
	var port = server.address().port // 获取服务端口
	console.log("应用实例，访问地址为 http://%s:%s", host, port)
});
