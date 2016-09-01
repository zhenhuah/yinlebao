<?PHP
/*********************************************************************************
 *                                RpcCore.php
 *                             -------------------
 *   begin                : 2012/10/11
 *   copyright            : (C) 2012 梁锋
 *   email                : vvkmake@163.com
 *   desc                 : API配置接口
********************************************************************************/
@define('API_RPC_STATUS', '1');					//接口可用状态 1=启用，0=关闭
@define('API_RPC_ENCRYPT', 'wA9cayK5eU68N');	//服务端的接口密码

//这里设置一下网站的根目录路径，以便创建相同路径的图片路径目录
@define("S_ROOT", substr(dirname(__FILE__).DIRECTORY_SEPARATOR, 0, -8)."www".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR);//根绝对路径
//@define("S_ROOT", 'D:\wamp\www\go3ccms\statics'.DIRECTORY_SEPARATOR);//根绝对路径
//以下是配置需要同步的服务器地址接口，以后需要请自己添加一行
@define('SYN_ROUTE_SERVER_ONE', 'http://www.qxf.com/API/SynUpdateImageServer.php');	//由器地址1


