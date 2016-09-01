<?PHP
/*********************************************************************************
 *                                RpcCore.php
 *                             -------------------
 *   begin                : 2012/10/11
 *   copyright            : (C) 2012 梁锋
 *   email                : vvkmake@163.com
 *   desc                 : API配置接口
********************************************************************************/
@define('API_RPC_STATUS', '0');					//接口可用状态 1=启用，0=关闭
@define('API_RPC_ENCRYPT', 'wA9cayK5eU68N');	//服务端的接口密码

//这里设置一下网站的根目录路径，以便创建相同路径的图片路径目录
@define("S_ROOT", '/how/wwwroot/default');//根绝对路径

//以下是配置需要同步的服务器地址接口，以后需要请自己添加一行
@define('SYN_ROUTE_SERVER_ONE', 'http://www.go3c.tv:8060/API/SynUpdateImageServer.php');	//路由器地址1

//任务图片路由设置
@define('SYN_ROUTE_SERVER_TWO', 'http://www.go3c.tv:8060/API/SynUpdateImageServer.php');	//路由器地址2

@define('LOCAL_IMG_DOWN_STATUS', '0');					//本地是否下载可用状态 1=启用，0=关闭

