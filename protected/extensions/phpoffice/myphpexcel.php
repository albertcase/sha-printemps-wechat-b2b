
<?php
// require_once dirname(__FILE__).'/swiftmailer/lib/swift_required.php';
// require_once dirname(__FILE__).'/swiftmailer/SwiftMailer.php';
  class myphpexcel{

    public function __construct(){
      Yii::$enableIncludePath = false;
      Yii::import('ext.phpoffice.Classes.PHPExcel', 1);
      Yii::import('ext.phpoffice.Classes.PHPExcel.IOFactory', 1);
    }

    public function loadexcel5($file){
      $PHPExcel = PHPExcel_IOFactory::load($file);
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
