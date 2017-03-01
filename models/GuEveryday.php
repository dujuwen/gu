<?php

namespace app\models;

use app\models\base\BaseGuEveryday;

class GuEveryDay extends BaseGuEveryday {
    public static function getRecommend($day = 5, $num = 250) {
        $day = intval($day);
        $data = GuEveryDay::find()->select('code, incr_decr')->orderBy('date_ desc')->asArray()->all();
        $countNum = [];
        if (count($data)) {
            $re = [];
            foreach ($data as $value) {
                $code = $value['code'];
                if (isset($re[$code])) {
                    $countNum[$code] = $countNum[$code] + 1;
                    if ($countNum[$code] > $day) {
                        continue;
                    }
                    $re[$code] = $re[$code] + floatval($value['incr_decr']);
                } else {
                    $countNum[$code] = 1;
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

            return [$re, $namesNew];
        }
    }
}