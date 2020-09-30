<?php
    namespace App\Orders\Common;

    class FormMaker {
        /**
         * 回傳一個訂單編號
         *
         * @return string
         */
        public static function orderId() {
            return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        }
        /**
         * 建立表單並執行
         *
         * @return void
         */
        public static function make($url, &$dataAry) {

            $szHtml =  '<!DOCTYPE html>';
            $szHtml .= '<html>';
            $szHtml .=     '<head>';
            $szHtml .=         '<meta charset="utf-8">';
            $szHtml .=     '</head>';
            $szHtml .=     '<body>';
            $szHtml .=         "<form id=\"__ecpayForm\" method=\"post\" target=\"_self\" action=\"{$url}\">";

            foreach ($dataAry as $keys => $value) {
                $szHtml .=         "<input type=\"hidden\" name=\"{$keys}\" value='{$value}' />";
            }

            $szHtml .=         '</form>';
            $szHtml .=         '<script type="text/javascript">document.getElementById("__ecpayForm").submit();</script>';
            $szHtml .=     '</body>';
            $szHtml .= '</html>';

            echo $szHtml ;

        }
    }
