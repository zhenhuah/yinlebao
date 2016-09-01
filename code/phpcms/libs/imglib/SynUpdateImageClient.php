<?php
/*********************************************************************************
 *                                SynUpdateImageClient.php
 *                             -------------------
 *   begin                : 11 17, 2012
 *   copyright            : (C) 2012 ����
 *   email                : vvkmake@163.com
 *   desc                 : �ͻ���ͼƬ�������ز�֪ͨԶ�̷�����APIͬ�����½ӿ�
********************************************************************************/
//ini_set('memory_limit', '-1');
//include_once('RpcCore.php');
//include_once('HproseHttpClient.php');



// ��Զ�̰�ͼƬ�ص�������������
class SynUpdateImage {

	public $source;		//ͼƬԴ��ַ

	public $savePath;	//�����Ŀ¼

	public $quality;

	public $_api_rpc_status;	//API_RPC_STATUS

	public $_api_rpc_encrypt;	//API_RPC_ENCRYPT

	public $_api_local_img_down_status;	//API_RPC_ENCRYPT

	public $_instance;			//����

	//���� ��ʼ��
	public function __construct($getServerInstance)
	{
		$this->_api_rpc_status = API_RPC_STATUS;

		$this->_api_rpc_encrypt = API_RPC_ENCRYPT;
		
		$this->_api_local_img_down_status = LOCAL_IMG_DOWN_STATUS;

		$this->_instance = $getServerInstance;
	}

	/**
	 * ִ��ͬ��
	 *
	 * @param string
	 */
	public function runSynUpdateImg($imgUrl)
	{
		if(!empty($imgUrl))
		{

			//�����Ƿ�����
			if($this->_api_local_img_down_status == '1')
			{
				$localStatus = self::runImgUrl($imgUrl); //ִ�н��
			}		

			return $synStatus = $this->_instance->runSynUpdateImg($this->_api_rpc_encrypt,$imgUrl);

		}else{
			return '0';		//ִ�н��
		}
	}



	//ֱ�Ӵ�����URLͼƬ·��
	private function runImgUrl($imgUrl = "")
	{
		$newImgUrl = parse_url($imgUrl);

		if(!empty($newImgUrl['host']))
		{
			/*
			//�ж��Ƿ���������·
			$getUrlParseInfo = parse_url($imgUrl);
			
			if(empty($getUrlParseInfo['host']))
			{
				$imgWholeUrl = $setHostAdd.ltrim($imgUrl,'/');
			}else{
				$imgWholeUrl = $imgUrl;
			}
			*/

			//��ȡͼƬ�ļ������ص�����������Ŀ¼һ����λ��
			$this->source = $imgUrl;

			$FILE = self::runDownload(); //ͼƬ�ƶ�������

			if(!empty($FILE))//����ͬ���ɹ�
			{
				return '1';
			}else{	//����ͬ��ʧ��
				return '0';
			}

			//������������������

		}
	}

	//ִ�в���
	private function runDownload($method = 'curl')
	{
		$parse_url = parse_url($this->source);

		//�ж�Ҫ�����Ŀ¼�Ƿ���ڣ������ڲ�������û��Ŀ¼����ڸ�Ŀ¼����
		if(!empty($parse_url['path']))
		{
			$OutputFile = $parse_url['path'];

			$DirFile = str_replace(strrchr($OutputFile,"/"),"",$OutputFile);
			
			/*
			if(!is_dir('.'.$DirFile)) //����ļ�Ŀ¼�Ƿ����
			{
				$this->mk_dir('.'.$DirFile);//�����ļ�Ŀ¼��
			}
			
			$this->savePath = '.'.$DirFile;
			*/
			if(!is_dir(S_ROOT.$DirFile)) //����ļ�Ŀ¼�Ƿ����
			{
				$this->mk_dir(S_ROOT.$DirFile);//�����ļ�Ŀ¼��
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

		$savePath = $this->savePath."/".basename($this->source);//�����ϵ�����һ�������ֲ���
		
		//������� ��ɸ�$_FILE����һ�� �õ����Լ���ƽ��ͼƬ�ϴ�����һ����
		$img_info['name'] = basename($this->source);

		$img_info['type'] = $mime;

		$img_info['size'] = 1000;

		$img_info['tmp_name'] = $savePath;

		$img_info['error'] = 0;

		if($method == 'curl')
		{
			$saveImageStatus = self::loadImageCURL($savePath);	//����ͼƬ������

			if(empty($saveImageStatus))	//ʧ��
			{
				return false;
			}
		}else{

			$img_info = '';
		}

		return $img_info;
	}


	//ͬ������
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
	��������:  mk_dir($dir, $mode = 0777)
	��Ҫ����:ѭ������Ŀ¼
	����:string()
	���:string
	�޸���־:------
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
//ʹ������

//��ͼƬURL���ӿ�
$url = 'http://www.baidu.com/img/baidu_sylogo1.gif';

$aImg = new SynUpdateImage;

echo $downloadStatus = $aImg->runImgUrl($url);
*/
//ʹ������
/*
//��ͼƬURL���ӿ�
$imgUrl= 'http://111.208.56.207/poster/ingestposter/tc111946043/poster43050.png';

if(!empty($imgUrl))
{
	$getServerInstance = new HproseHttpClient(SYN_ROUTE_SERVER_ONE);//����API·��

	if(is_object($getServerInstance))
	{
		//����
		$imgClient =  new SynUpdateImage($getServerInstance);
		
		echo $synUpdateStatus = $imgClient->runSynUpdateImg($imgUrl);//���·�������
	}
}
*/
?>
