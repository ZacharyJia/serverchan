# 基于 Laravel 的「Server酱」

## 关于 Server酱

「Server酱」是由 [@Easy](https://github.com/easychen) 提供的免费的从服务器推报警和日志到手机的工具。
你可以访问 [sc.ftqq.com](sc.ftqq.com) 来访问原版的「Server酱」的服务。

这个项目的产生是由于我在使用「Server酱」的时候，遇到了一些问题，例如消息记录只保留7天，这样造成了我无法查找历史消息，丢失了一些内容。

而且「Server酱」本身是不开源的，因此我决定自己动手写一个「Server酱」。

其实程序代码本身并不复杂，「Server酱」最大的价值在于其服务号以及服务号所申请到的高自由度的模板供我们使用。这个项目不能为你提供这些。
如果你要使用这个项目的话，可以使用腾讯提供的[微信公众平台接口测试账号](https://mp.weixin.qq.com/debug/cgi-bin/sandbox?t=sandbox/login)，
测试账号可以为我们提供绝大部分的功能，自然包括高自由度的模板。但是也存在一些限制，比如最多只能有100个用户，会被收在订阅号消息中，以及无法自定义公众号名称中。
如果你自己有服务号，也可以尝试使用它。


## 部署配置
```
> git clone https://github.com/ZacharyJia/serverchan.git
> cd serverchan
> composer install
> cp .env.example .env #配置文件
```
然后在.env文件中根据需要配置好APP以及数据库相关的配置
```
php artisan migrate #导入数据库
php artisan serve #启动服务
```

## 公众号配置
在`.env`文件中填写好公众号的`WECHAT_OFFICIAL_ACCOUNT_APPID`、`WECHAT_OFFICIAL_ACCOUNT_SECRET`和`WECHAT_OFFICIAL_ACCOUNT_TOKEN`。
然后增加一个新的模板，需要有`title`和`content`两个字段。增加完成后把模板的id填写到`WECHAT_OFFICIAL_ACCOUNT_TEMPLATE_ID`字段中。
