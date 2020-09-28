<?php

namespace App\Payment;

/**
*  檢查碼
*/
class CheckMacValue {

    static function generate($arParameters = array(),$HashKey = '' ,$HashIV = '',$encType = 0){
        $sMacValue = '' ;

        if(isset($arParameters))
        {
            unset($arParameters['CheckMacValue']);
            // 資料排序 php 5.3以下不支援
            uksort($arParameters, array('CheckMacValue','merchantSort'));

            // 組合字串
            $sMacValue = 'HashKey=' . $HashKey ;
            foreach($arParameters as $key => $value)
            {
                $sMacValue .= '&' . $key . '=' . $value ;
            }

            $sMacValue .= '&HashIV=' . $HashIV ;

            // URL Encode編碼
            $sMacValue = urlencode($sMacValue);

            // 轉成小寫
            $sMacValue = strtolower($sMacValue);

            // 取代為與 dotNet 相符的字元
            $sMacValue = str_replace('%2d', '-', $sMacValue);
            $sMacValue = str_replace('%5f', '_', $sMacValue);
            $sMacValue = str_replace('%2e', '.', $sMacValue);
            $sMacValue = str_replace('%21', '!', $sMacValue);
            $sMacValue = str_replace('%2a', '*', $sMacValue);
            $sMacValue = str_replace('%28', '(', $sMacValue);
            $sMacValue = str_replace('%29', ')', $sMacValue);

            // 編碼
            switch ($encType) {
                case EncryptType::ENC_SHA256:
                    // SHA256 編碼
                    $sMacValue = hash('sha256', $sMacValue);
                break;

                case EncryptType::ENC_MD5:
                default:
                // MD5 編碼
                    $sMacValue = md5($sMacValue);
            }

                $sMacValue = strtoupper($sMacValue);
        }

        return $sMacValue ;
    }
    /**
    * 自訂排序使用
    */
    private static function merchantSort($a,$b)
    {
        return strcasecmp($a, $b);
    }

}
