<?php

namespace App\Payment;

/**
*  檢查碼
*/
class CheckMacValue {

    static function generate(array $params, $hashKey='', $hashIV='', $encType = 1){
    // 0) 如果資料中有 null，必需轉成空字串
        $params = array_map('strval', $params);

        // 1) 如果資料中有 CheckMacValue 必需先移除
        unset($params['CheckMacValue']);

        // 2) 將鍵值由 A-Z 排序
        uksort($params, 'strcasecmp');

        // 3) 將陣列轉為 query 字串
        $paramsString = urldecode(http_build_query($params));

        // 4) 最前方加入 HashKey，最後方加入 HashIV
        $paramsString = "HashKey={$hashKey}&{$paramsString}&HashIV={$hashIV}";

        // 5) 做 URLEncode
        $paramsString = urlencode($paramsString);

        // 6) 轉為全小寫
        $paramsString = strtolower($paramsString);

        // 7) 轉換特定字元
        $paramsString = str_replace('%2d', '-', $paramsString);
        $paramsString = str_replace('%5f', '_', $paramsString);
        $paramsString = str_replace('%2e', '.', $paramsString);
        $paramsString = str_replace('%21', '!', $paramsString);
        $paramsString = str_replace('%2a', '*', $paramsString);
        $paramsString = str_replace('%28', '(', $paramsString);
        $paramsString = str_replace('%29', ')', $paramsString);

        // 8) 進行編碼
        $paramsString = $encType ? hash('sha256', $paramsString) : md5($paramsString);

        // 9) 轉為全大寫後回傳
        return strtoupper($paramsString);
    }
    /**
    * 自訂排序使用
    */
    private static function strcasecmp($a,$b)
    {
        return strcasecmp($a, $b);
    }

}
