## 环境

```
进入项目目录, docker-compose up
正常会启动5个服务: laravms-php, laravms-nginx, laravms-mysql, laravms-redis, laravms-elasticsearch

docker宿主机, ifconfig查看对应ip, 本地hosts绑定对应ip, 示例: 172.24.200.171 www.laravms.me

进入laravms-mysql容器, 创建对应数据库laravms
docker exec -it laravms-mysql /bin/sh
mysql -uroot -p123456
CREATE DATABASE `laravms` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## 后端代码

```
路径: cd www/localhost/laravms
复制配置文件: cp .env.example .env

进入laravms-php容器, 安装依赖
docker exec -it laravms-php /bin/sh
cd localhost/laravms/
composer install

执行命令:
php artisan key:generate
php artisan jwt:secret
php artisan migrate

php artisan db:seed --class=AdminSeeder

php artisan init:es

浏览器访问 [http://www.laravms.me](http://www.laravms.me)
账号密码: admin@laravms.com laravms@2022
```

## 前端代码

```
路径: cd code/frontend
安装依赖: npm install
启动服务: npm run dev
浏览器访问 [http://localhost:9528](http://localhost:9528)
接口请求会转发到测试域名: www.laravms.me,如果后台代码还没部署,请求会报错

发布: npm run build:prod
然后把dist目录下的打包文件放到www/localhost/laravms/public目录下
```