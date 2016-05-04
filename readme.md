# 宅急送标准对接SDK




## 安装

```
composer require wujingke/zjs
```

更新你的依赖包 ```composer update``` 或者全新安装 ```composer install```。



## 例子

```php
use ZJS\Order;  //订单类
use ZJS\Sender;  //发件人
use ZJS\Receiver; //收件人




//下订单
$sender = new Sender();

$sender->name = "xxx";
$sender->phone="028-82487225";
$sender->prov="四川省";
$sender->city="成都市";
$sender->district="成华区";
$sender->address="四川省成都市成华区xxxx";

$receiver  =new  Receiver();
$receiver->name = "裕承保险";
$receiver->mobile="028-82487225";
$receiver->prov="四川省";
$receiver->city="成都市";
$receiver->district="成华区";
$receiver->address="四川省成都市成华区xxxxxx";
$order = new  Order('xx', 'xx', 'xx', 'http://zjs.com.cn/');


$order->setOrderId(time());
$order->setSender($sender);
$order->setReceiver($receiver);
$order->setItem('我的1', 1);
$order->setItem('我的2', 1);
$order->setItems('总', 1);


//取消订单

$order = new  Order('xx', 'xx', 'xx', 'http://zjs.com.cn/');

$order->setOrderId(time());
$order->cancel())

```
