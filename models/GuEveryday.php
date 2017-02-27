<?php

namespace app\models;

use app\models\base\BaseGuEveryday;

class GuEveryDay extends BaseGuEveryday {
    public static function getRecommend($day = 5, $num = 250) {
        $data = GuEveryDay::find()->select('code, incr_decr')->asArray()->all();
        if (count($data)) {
            $re = [];
            foreach ($data as $value) {
                $code = $value['code'];
                if (isset($re[$code])) {
                    $re[$code] = $re[$code] + floatval($value['incr_decr']);
                } else {
                    $re[$code] = floatval($value['incr_decr']);
                }
            }
            arsort($re);
            $re = array_slice($re, 0, $num);
            $names = GuFix::find()->select('code,name')->where(['code' => array_keys($re)])->asArray()->all();
            $namesNew = [];
            foreach ($names as $ns) {
                $namesNew[$ns['code']] = $ns['name'];
            }

            $echoArr = '';
            foreach ($re as $code => $num) {
                $echoArr[$code] = $namesNew[$code] . '/' . $num;
            }
            return $echoArr;
        }
    }
}