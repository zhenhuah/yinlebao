<?php
$file = $GET($file);
ob_clean();
header('Pragma: public');
header('Last-Modified:'.gmdate('D, d M Y H:i:s') . 'GMT');
header('Cache-Control:no-store, no-cache, must-revalidate');
header('Cache-Control:pre-check=0, post-check=0, max-age=0');
header('Content-Transfer-Encoding:binary'); 
header('Content-Encoding:none');
header('Content-type:multipart/form-data');
header('Content-Disposition:attachment; filename="'.basename($file).'"'); //�������ص�Ĭ���ļ���
header('Content-length:'. filesize($file));
$fp = fopen($file, 'r'); //��ȡ���ݣ���ʼ����
while(connection_status() == 0 && $buf = @fread($fp, 8192)){
    echo $buf;
}
fclose($fp);
@flush();
@ob_flush();
exit();
?>
