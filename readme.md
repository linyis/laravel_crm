## About 本專案

- 本專案是 CRM 專案
- 已實作 php artisan user:create {email} {passwd:optional} {name:optional}
- 已實作 Test data seed, Account: test@test.com / 111111
- 已實作 Test data seed, Data : test 帳號會有十筆資料 (app.php 已設定為中文測試資料)
## 基本要求

- 請使 Eloquent ORM
- 請使 Migrations 建⽴遷移腳本
- 請使 Laravel MVC 架構
- 請使 bootstrap 套版
- 資料庫規劃請 https://dbdiagram.io/ 具畫出 DB diagram
- 陣列請盡量使 Collection 處理

## 會員登入/註冊系統
- 除基本帳號/密碼登⼊外，必須實作第三方登入，Google、Facebook、Line
- 禁使用 Socialite 套件製作，因為此套件缺乏彈性
- 至少使用一種以上設計模式，⼯廠模式或轉接頭模式，請將第三⽅登⼊程式做好封裝處理
- 第三方登入相關參數請使⽤資料庫做設定，不要寫在 env 裡
- 會員註冊需要加 google reCAPTCHA 驗證碼機制
- 需要寫一個 php artisan console 指令輸⼊帳號/密碼，直接產生會員帳號
  
  
## CMS 系統
- 後台⽂章編輯請使⽤AJAX製作
- ⽂章列表⾄少要能搜尋標題
- 需要⽂章分類，⽽且⽂章分類能夠⽀援無限階層，可使⽤ Baum 套件製作
- 套版⽂章分類時請使⽤遞迴⽅式顯⽰⽂章分類
- 需要實作圖⽚上傳功能，⽂章內⽂可夾帶圖⽚，上傳的圖⽚需要進⾏圖⽚壓縮寬度不能超過 2048
- ⽂章瀏覽需紀錄使⽤者訪問紀錄，並記錄瀏覽量
- 訪問紀錄需紀錄使⽤者 IP 、UA、Header
- 瀏覽紀錄需請使⽤ Redis 做 Queue，並使⽤ Job 機制⾮同步寫⼊ log 並更新⽂章總瀏覽量，禁⽌⽂章瀏覽時同步寫⼊ DB ⽂章瀏覽紀錄 log  
  
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
