# Laravel Project Starter

[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

## 包含依赖库
- [dingo/api](https://github.com/dingo/api)
- [cartalyst/sentinel](https://github.com/cartalyst/sentinel)
- [intervention/image](https://github.com/Intervention/image)

## 安装说明

```bash
$ composer install
```
#### 将.env.example复制到.env，配置数据库参数、邮件参数。
#### 生成密钥，执行数据迁移：

```bash
$ php artisan key:generate
$ php artisan migrate
$ php artisan db:seed
```

## License

本项目开源基于： [MIT license](http://opensource.org/licenses/MIT).
