<?PHP
/*********************************************************************************
 *                                SynUpdateImageServer.php
 *                             -------------------
 *   begin                : 07 17, 2012
 *   copyright            : (C) 2012 梁锋
 *   email                : vvkmake@163.com
 *   desc                 : 服务器端API读写操作接口
********************************************************************************/
include_once('HproseHttpServer.php');
include_once('RpcCore.php');

class SynUpdateImage
{

	public $source;		//图片源地址

	public $savePath;	//保存的目录

	public $quality;

	public $_api_rpc_status;	//API_RPC_STATUS

	public $_api_rpc_encrypt;	//API_RPC_ENCRYPT


	//构造 初始化
	public function __construct()
	{
		$this->_api_rpc_status = API_RPC_STATUS;

		$this->_api_rpc_encrypt = API_RPC_ENCRYPT;
	}
	
	/*
	//禁止克隆这个对象
	private function __clone()
	{
	
	}
	*/

	/**
	 * 同步
	 *
	 * @param string
	 */
	public function runSynUpdateImg($apiRpcEncrypt,$imgUrl)
	{
		//return $imgUrl;
		if($this->_api_rpc_status == '1')	//接口是否启用
		{
			if(isset($apiRpcEncrypt) && ($apiRpcEncrypt == $this->_api_rpc_encrypt))//密码身份验证
			{
				if(!empty($imgUrl))
				{
					//同步到本地
					$localStatus = self::runImgUrl($imgUrl);

					if(!empty($localStatus))
					{
						return '1';
					}else{
						return '0';		//执行结果
					}

				}else{
					return '0';		//执行结果
				}

			}else{
				return ' stop services!!';
			}
		}else{
			return ' stop services!';
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
			
			if(!is_dir('.'.$DirFile)) //检查文件目录是否存在
			{
				$this->mk_dir('.'.$DirFile);//创建文件目录；
			}
			
			$this->savePath = '.'.$DirFile;

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
	
	
}	//class end

$server = new HproseHttpServer();

$server->addClassMethods(new SynUpdateImage);//addClassMethods addInstanceMethods

$server->handle();
?>