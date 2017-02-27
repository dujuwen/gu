<?php
namespace app\controllers;

use Yii;
use app\library\base\BaseController;
use app\models\GuEveryDay;
use yii\helpers\StringHelper;

class ReController extends BaseController {

    public function actionIndex() {
//     	echo json_encode(GuEveryDay::getRecommend());
    	$data = GuEveryDay::getRecommend();
    	foreach ($data as $code => $value) {
    	    if (StringHelper::startsWith($value, '6')) {
        		echo "&nbsp;&nbsp;&nbsp;<a href='http://gu.qq.com/sh{$code}' target='_blank'>{$code}/</a>" . $value . "<br/>";
    	    } else {
        		echo "&nbsp;&nbsp;&nbsp;<a href='http://gu.qq.com/sz{$code}' target='_blank'>{$code}/</a>" . $value . "<br/>";
    	    }
    	}
    }
}