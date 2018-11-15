<?php
namespace api\controllers;
use Yii;
use yii\web\Controller;
class IndexController extends Controller{
	public $enableCsrfValidation = false;
	public function actionIndex(){

		$dir = '/usr/share/nginx/html/ybb/advanced/api/upload/excel.xls';
		$res = move_uploaded_file($_FILES['execl']['tmp_name'], $dir);

		$title = yii::$app->request->post('title');
		require (__DIR__.'/../../common/libs/PHPExcel.php');
		header("content-type:text/html;charset = utf-8");
		$objPHPExcel = \PHPExcel_IOFactory::load($dir);
		$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		
		unset($sheetData[1]);
		$type=array_flip(Yii::$app->params['type']);
		$score=Yii::$app->params['score'];
		// var_dump($score);die;
		// var_dump($sheetData);die;
		foreach($sheetData as $key => $val){
			
			$arr = array(
				'stem'=>$val['C'],
				'unit'=>$title,
				'type'=>$type[$val['B']],
				'addtime'=>time(),
				'sub_people'=>$val['L']
			);
			$res = Yii::$app->db->createCommand()->insert('subject',$arr)->execute();
			$tid = Yii::$app->db->getLastInsertID();
			$an_num = array('D' => 'A', 'E'=>'B','F'=>'C','G'=>'D','H'=>"E",'I'=>'F');
			$yesinfo = str_split($val['J'],1);
			for ($i='D'; $i < 'I'; $i++) { 
				
				if (empty($val[$i])) continue;
				$is_true = in_array($an_num[$i], $yesinfo) ? 1:0;
				$arr2 = array(
					'tid'=>$tid,
					'option'=>$val[$i],
					'is_option'=>$is_true
				);
				$ress = Yii::$app->db->createCommand()->insert('answer',$arr2)->execute();
			}
		}
		if ($res && $ress) {
			echo 1;
		}
	}
}