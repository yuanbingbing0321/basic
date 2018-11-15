<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use common\libs\curl;
class IndexController extends Controller{

	public $enableCsrfValidation = false;
	public function actionIndex(){
		return $this->render('list');
	}
	public function actionUpload(){
		if (yii::$app->request->ispost) {
			
			$dir = '../upload/excel.xls';
			$res = move_uploaded_file($_FILES['execl']['tmp_name'], $dir);

			if ($res) {
				$url="http://106.12.210.207/ybb/advanced/api/web/index.php?r=index/index";
				$param['title'] = Yii::$app->request->post("title");

				$file['execl'] = $dir;
				$reg = curl::_post($url,$param,$file);
				
				// var_dump($param);die;
				if ($reg) {
					echo $reg;die;
				}
			}
		}else{
			return $this->render('upload');
		}
	}
}