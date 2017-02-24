<?php
namespace app\controllers;

use Yii;
use app\library\base\BaseController;
use app\models\GuEveryDay;

class ReController extends BaseController {

    public function actionIndex() {
    	echo json_encode(GuEveryDay::getRecommend());
    }
}