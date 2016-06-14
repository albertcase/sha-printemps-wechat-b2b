
<?php
// require_once dirname(__FILE__).'/swiftmailer/lib/swift_required.php';
// require_once dirname(__FILE__).'/swiftmailer/SwiftMailer.php';
  class myphpexcel{
    
    public function __construct(){
      Yii::$enableIncludePath = false;
      Yii::import('ext.phpoffice.Classes.PHPExcel', 1);
      Yii::import('ext.phpoffice.Classes.DBHelper', 1);
      Yii::import('ext.phpoffice.Classes.PHPExcel.IOFactory', 1);
    }

    public function loadexcel5($file){
      $reader = PHPExcel_IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
      $PHPExcel = $reader->load($file); // 载入excel文件
      $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
      $highestRow = $sheet->getHighestRow(); // 取得总行数
      $highestColumm = $sheet->getHighestColumn(); // 取得总列数
      return array(
        'sheet' => $sheet,
        'highestRow' => $highestRow,
        'highestColumm' => $highestColumm,
      );
    }
}
?>
