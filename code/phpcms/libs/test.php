<?php
$data= L('search')->findAll();   //�������
$name='Excelfile';    //���ɵ�Excel�ļ��ļ���
$res=service('ExcelToArrary')->push($data,$name);

class ExcelToArrary extends Service{
       public function __construct() {
              /*����phpExcel������    ע�� �����·�����Ҳ�һ���Ͳ���ֱ�Ӹ���*/
               include_once('./PHPExcel.php');
       }
     /* ����excel����*/
    public function push($data,$name='Excel'){
          error_reporting(E_ALL);
          date_default_timezone_set('Europe/London');
         $objPHPExcel = new PHPExcel();
        /*������һЩ���� ��ʲô����  ���Ⱑ֮���*/
         $objPHPExcel->getProperties()->setCreator("ת�������")
                               ->setLastModifiedBy("ת�������")
                               ->setTitle("����EXCEL����")
                               ->setSubject("����EXCEL����")
                               ->setDescription("��������")
                               ->setKeywords("excel")
                              ->setCategory("result file");
         /*���¾��ǶԴ���Excel������ݣ� ����ȡ���ݣ���Ҫ����һ����������������Ҫ��*/
        foreach($data as $k => $v){
             $num=$k+1;
             $objPHPExcel->setActiveSheetIndex(0)
                         //Excel�ĵ�A�У�uid����������ļ�ֵ�������Դ�����
                          ->setCellValue('A'.$num, $v['uid'])    
                          ->setCellValue('B'.$num, $v['email'])
                          ->setCellValue('C'.$num, $v['password'])
            }
            $objPHPExcel->getActiveSheet()->setTitle('User');
            $objPHPExcel->setActiveSheetIndex(0);
             header('Content-Type: application/vnd.ms-excel');
             header('Content-Disposition: attachment;filename="'.$name.'.xls"');
             header('Cache-Control: max-age=0');
             $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
             $objWriter->save('php://output');
             exit;
      }


?>
