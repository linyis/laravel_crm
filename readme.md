## About 本專案

- 1.前台 List 有實作 search 文字
- 1.已實作 php artisan user:create {email} {passwd:optional} {name:optional}
- 1.已實作 Test data seed, Account: test@test.com / 111111
- 1.已實作 Test data seed, Data : test 帳號會有十筆資料 (app.php 已設定為中文測試資料)
- 1.已實作 Register 中的第三方登錄 LINE 登錄, 其它 Google Facebook 待補
- 1.Line Developer 中 Call back 的網址是 http://localhost:8000/line/callback, 所以必須開在 port 8000
- 1.Line 登入後, 會記錄社群登入資料到 SocialUser 並且在 Users 建立資料做相關連結
- 2.後台 Create New 可以上傳圖片, 並且縮圖到寬度 2048 以下
- 2.後台 Edit 直接使用 Ajax 傳送想修改的資料
- 2.後台 Delete 是使用 web route 傳送 id 處理後再 redirect 回 list
- 2.後台有實作 baum/baum on category model, 已自動建立五筆類別資料在 seed
- 2.資料隨機綁定不同的 category, 也有可能沒有
- 2.後台有顯示階層, web.php 有個遞迴, 用來給後台顯示類別階層的
- 2.瀏覽記錄及 IP 記錄有實作 Queue Job 機制
- 3.VerifyCsrfToken, 記錄一些訂單 callback 回來時避免掉csrf的網址
- 3.訂單資料庫已完成, Create & Store 已完成
- 3.訂單 Api 串接完成, 自動更新 Order 資料庫, PayInfo 資料庫
- 3.訂單 Log 記錄會放在 PayInfo (column:platform_status)
- 3.Mail Driver 改為 SES, 個人帳戶使用中
- 3.訂單完成後發送Email, 放在 queue 中
- ![image](https://github.com/linyis/laravel_crm/blob/master/TEST_CRM.png)
## 剩下工作!!
- 串接 data 封裝一下物件
- DB diagram?

## 基本要求

- 請使 Eloquent ORM
- 請使 Migrations 建立遷移腳本
- 請使 Laravel MVC 架構
- 請使 bootstrap 套版
- 資料庫規劃請 https://dbdiagram.io/ 具畫出 DB diagram
- 陣列請盡量使 Collection 處理

## 會員登入/註冊系統
- 除基本帳號/密碼登入外，必須實作第三方登入，Google、Facebook、Line
- 禁使用 Socialite 套件製作，因為此套件缺乏彈性
- 至少使用一種以上設計模式，工廠模式或轉接頭模式，請將第三方登入程式做好封裝處理
- 第三方登入相關參數請使用資料庫做設定，不要寫在 env 裡
- 會員註冊需要加 google reCAPTCHA 驗證碼機制
- 需要寫一個 php artisan console 指令輸⼊帳號/密碼，直接產生會員帳號
  
  
## CMS 系統
- 後台文章編輯請使用AJAX製作
- 文章列表至少要能搜尋標題
- 需要文章分類，且文章分類能夠支援無限階層，可使用 Baum 套件製作
- 套版文章分類時請使用遞迴方式顯示文章分類
- 需要實作圖片上傳功能，文章內⽂可夾帶圖片，上傳的圖片需要進行壓縮寬度不能超過 2048
- 文章瀏覽需紀錄使用者訪問紀錄，並記錄瀏覽量
- 訪問紀錄需紀錄使用者 IP 、UA、Header
- 瀏覽紀錄需請使用 Redis 做 Queue，並使用 Job 機制同步寫入 log 並更新⽂章總瀏覽量，禁止文章瀏覽時同步寫入 DB 文章瀏覽紀錄 log  
  
<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Laravel 資源

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, yet powerful, providing tools needed for large, robust applications.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
"# laravel_crm" 
