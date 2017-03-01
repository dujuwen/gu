<?php
namespace app\controllers;

use app\library\base\BaseController;
use app\models\GuEveryDay;
use yii\helpers\StringHelper;

class ReController extends BaseController {

    public function actionIndex() {
        $day = intval($this->getGet('day')) ?: 3;
    	$data = GuEveryDay::getRecommend($day);
    	$re = $data[0];
    	$namesNew = $data[1];
    	foreach ($re as $code => $num) {
    	    if (StringHelper::startsWith($code, '6')) {
        		echo "&nbsp;&nbsp;&nbsp;<a href='http://gu.qq.com/sh{$code}' target='_blank'>{$code}/</a>" . $namesNew[$code] . '/' . $num . "<br/>";
    	    } else {
        		echo "&nbsp;&nbsp;&nbsp;<a href='http://gu.qq.com/sz{$code}' target='_blank'>{$code}/</a>" . $namesNew[$code] . '/' . $num . "<br/>";
    	    }
    	}
    }
}