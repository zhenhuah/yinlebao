<?php
/*********************************************************************************
 *                                SynUpdateImageClient.php
 *                             -------------------
 *   begin                : 11 17, 2012
 *   copyright            : (C) 2012 梁锋
 *   email                : vvkmake@163.com
 *   desc                 : 客户端图片请求下载并通知远程服务器API同步更新接口
********************************************************************************/
//ini_set('memory_limit', '-1');
//include_once('RpcCore.php');
//include_once('HproseHttpClient.php');



// 从远程吧图片载到服务器本地类
class SynUpdateImage {

	public $source;		//图片源地址

	public $savePath;	//保存的目录

	public $quality;

	public $_api_rpc_status;	//API_RPC_STATUS

	public $_api_rpc_encrypt;	//API_RPC_ENCRYPT

	public $_api_local_img_down_status;	//API_RPC_ENCRYPT

	public $_instance;			//对象

	//构造 初始化
	public function __construct($getServerInstance)
	{
		$this->_api_rpc_status = API_RPC_STATUS;

		$this->_api_rpc_encrypt = API_RPC_ENCRYPT;
		
		$this->_api_local_img_down_status = LOCAL_IMG_DOWN_STATUS;

		$this->_instance = $getServerInstance;
	}

	/**
	 * 执行同步
	 *
	 * @param string
	 */
	public function runSynUpdateImg($imgUrl)
	{
		if(!empty($imgUrl))
		{

			//本地是否下载
			if($this->_api_local_img_down_status == '1')
			{
				$localStatus = self::runImgUrl($imgUrl); //执行结果
			}		

			return $synStatus = $this->_instance->runSynUpdateImg($this->_api_rpc_encrypt,$imgUrl);

		}else{
			return '0';		//执行结果
		}
	}



	//直接传可用URL图片路径
	private function runImgUrl($imgUrl = "")
	{
		$newImgUrl = parse_url($imgUrl);

		if(!empty($newImgUrl['host']))
		{
			/*
			//判断是否是完整的路
			$getUrlParseInfo = parse_url($imgUrl);
			
			if(empty($getUrlParseInfo['host']))
			{
				$imgWholeUrl = $setHostAdd.ltrim($imgUrl,'/');
			}else{
				$imgWholeUrl = $imgUrl;
			}
			*/

			//读取图片文件并下载到本地与线上目录一样的位置
			$this->source = $imgUrl;

			$FILE = self::runDownload(); //图片移动到本地

			if(!empty($FILE))//下载同步成功
			{
				return '1';
			}else{	//下载同步失败
				return '0';
			}

			//发布到其他服务器上

		}
	}

	//执行操作
	private function runDownload($method = 'curl')
	{
		$parse_url = parse_url($this->source);

		//判断要保存的目录是否存在，不存在并创建，没有目录则存在根目录下面
		if(!empty($parse_url['path']))
		{
			$OutputFile = $parse_url['path'];

			$DirFile = str_replace(strrchr($OutputFile,"/"),"",$OutputFile);
			
			/*
			if(!is_dir('.'.$DirFile)) //检查文件目录是否存在
			{
				$this->mk_dir('.'.$DirFile);//创建文件目录；
			}
			
			$this->savePath = '.'.$DirFile;
			*/
			if(!is_dir(S_ROOT.$DirFile)) //检查文件目录是否存在
			{
				$this->mk_dir(S_ROOT.$DirFile);//创建文件目录；
			}
			
			$this->savePath = S_ROOT.$DirFile;

		}else{
			$this->savePath = '.';
		}
		
		$info = @GetImageSize($this->source);
		
		$mime = $info['mime'];
		
		// What sort of image?
		$type = substr(strrchr($mime, '/'), 1);
		switch ($type)
		{
			case 'jpeg':
			$image_create_func = 'ImageCreateFromJPEG';
			$image_save_func = 'ImageJPEG';
			$new_image_ext = 'jpg';
			// Best Quality: 100
			$quality = isset($this->quality) ? $this->quality : 100;
			break;

			case 'png':
			$image_create_func = 'ImageCreateFromPNG';
			$image_save_func = 'ImagePNG';
			$new_image_ext = 'png';
			// Compression Level: from 0 (no compression) to 9
			$quality = isset($this->quality) ? $this->quality : 0;
			break;

			case 'bmp':
			$image_create_func = 'ImageCreateFromBMP';
			$image_save_func = 'ImageBMP';
			$new_image_ext = 'bmp';
			break;

			case 'gif':
			$image_create_func = 'ImageCreateFromGIF';
			$image_save_func = 'ImageGIF';
			$new_image_ext = 'gif';
			break;

			case 'vnd.wap.wbmp':
			$image_create_func = 'ImageCreateFromWBMP';
			$image_save_func = 'ImageWBMP';
			$new_image_ext = 'bmp';
			break;

			case 'xbm':
			$image_create_func = 'ImageCreateFromXBM';
			$image_save_func = 'ImageXBM';
			$new_image_ext = 'xbm';
			break;

			default:
			$image_create_func = 'ImageCreateFromJPEG';
			$image_save_func = 'ImageJPEG';
			$new_image_ext = 'jpg';
			break;
		}

		
		if(isset($this->set_extension))
		{
			$ext = strrchr($this->source, ".");

			$strlen = strlen($ext);

			$new_name = basename(substr($this->source, 0, -$strlen)).'.'.$new_image_ext;

		}else{
			$new_name = basename($this->source);
		}

		$savePath = $this->savePath."/".basename($this->source);//与线上的名称一样，保持不变
		
		//输出对象 组成跟$_FILE变量一样 得到后自己和平常图片上传处理一样了
		$img_info['name'] = basename($this->source);

		$img_info['type'] = $mime;

		$img_info['size'] = 1000;

		$img_info['tmp_name'] = $savePath;

		$img_info['error'] = 0;

		if($method == 'curl')
		{
			$saveImageStatus = self::loadImageCURL($savePath);	//保存图片到本地

			if(empty($saveImageStatus))	//失败
			{
				return false;
			}
		}else{

			$img_info = '';
		}

		return $img_info;
	}


	//同步更新
	private function loadImageCURL($savePath)
	{
		if(!empty($savePath))
		{
			$ch = curl_init($this->source);

			$fp = fopen($savePath, "wb");

			// set URL and other appropriate options
			$options = array(CURLOPT_FILE => $fp,
			CURLOPT_HEADER => 0,
			CURLOPT_FOLLOWLOCATION => 1,
			CURLOPT_TIMEOUT => 60); // 1 minute timeout (should be enough)
			curl_setopt_array($ch, $options);
			curl_exec($ch);
			curl_close($ch);

			fclose($fp);
			return true;
		}else{
			return false;
		}
	}

	/*
	-----------------------------------------------------------
	函数名称:  mk_dir($dir, $mode = 0777)
	简要描述:循环创建目录
	输入:string()
	输出:string
	修改日志:------
	-----------------------------------------------------------
	*/
	private function mk_dir($dir, $mode = 0777) {
		if (is_dir($dir) || @mkdir($dir, $mode))
			return true;
		if (!self::mk_dir(dirname($dir), $mode))
			return false;
		return @mkdir($dir, $mode);
	}
}

/*
//使用例子

//传图片URL给接口
$url = 'http://www.baidu.com/img/baidu_sylogo1.gif';

$aImg = new SynUpdateImage;

echo $downloadStatus = $aImg->runImgUrl($url);
*/
//使用例子
/*
//传图片URL给接口
$imgUrl= 'http://111.208.56.207/poster/ingestposter/tc111946043/poster43050.png';

if(!empty($imgUrl))
{
	$getServerInstance = new HproseHttpClient(SYN_ROUTE_SERVER_ONE);//启用API路由

	if(is_object($getServerInstance))
	{
		//更新
		$imgClient =  new SynUpdateImage($getServerInstance);
		
		echo $synUpdateStatus = $imgClient->runSynUpdateImg($imgUrl);//更新方法调用
	}
}
*/
?>
