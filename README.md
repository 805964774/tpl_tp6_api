# .env
```
CONFIG_SCENE = [local|dev|prod]
请选择其中一个，会根据.env中的该配置匹配config中对应的配置
```
---
# 生成curd基本框架
```
php think curd --name Test
```
---
# 上线前的优化

## 生成classmap，优化加载
```
composer dump-autoload --classmap-authoritative
```
## 生成路由缓存，减小路由解析开销
```
php think optimize:route
```
## 生成数据库表字段缓存
```
php think optimize:schema
```
