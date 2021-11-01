[TOC]
#该目录下的控制器用于云图APP客户端接口调用

#### 1、获取云图App列表数据接口

```
URL：http://yuntu.pop136.com/api/graphicitem/getlist

header请求头：access-token  (之前约定的,登录或者绑定设备后有返回)

请求方式：GET/POST

参数说明：
```

| 参数名            | 说明                                                  | 是否必须 |
| ----------------- | ----------------------------------------------------- | -------- |
| key               | 关键字                                                | NO       |
| page              | 第几页    比如：2                                     | NO       |
| newStorgreet      | 1-最新发布  2-最受欢迎  比如 ：1                      | NO       |
| patternContent    | 图案内容（逗号隔开，多选）比如： 11708,12089,12091    | NO       |
| patternTechnology | 图案工艺（逗号隔开，多选）比如：11454,11455           | NO       |
| format            | 图案格式（逗号隔开，多选）比如：10961，10969，15090   | NO       |
| application       | 图案格式（局部/满身,逗号隔开，多选）比如：14102,14103 | NO       |
| queryTime         | 热门数据  输入   数字，代表多少天  比如：16           | NO       |
| gender            | 性别（多选）比如：1，2，3，4，5                       | NO       |

```
返回说明：
    --  列表图片  取不到中图，就使用大图
```

```json
成功：
{
    "code":0,
    "msg":"OK",
    "data":[
        {
            "popId":"graphicitem_457749",
            "imgPath":"http://img2.yuntu.pop-fashion.com/fashion/graphic/20190627000_sh5261/4/small/201906271_sh5261.jpg",
            "small":"http://img2.yuntu.pop-fashion.com/fashion/graphic/20190627000_sh5261/4/small/201906271_sh5261.jpg",
            "mbig":"http://img1.yuntu.pop-fashion.com/fashion/graphic/20190627000_sh5261/4/mbig/38014f832bc2e776a6da6e5a644c0164.jpg",
            "big":"http://img1.yuntu.pop-fashion.com/fashion/graphic/20190627000_sh5261/4/big/38014f832bc2e776a6da6e5a644c0164.jpg"
        }
        ...
    ],
    "info":{
       "total":"有条件的查询的总数量",
       "allTotal":"首页总数量",
       "pageSize":"每页显示多少条"
    }
}

失败：
{
	"code": "以100开头五位数据",
	"msg": "获取数据失败"
}
```

#### 2、获取筛选项接口

```
URL：http://yuntu.pop136.com/api/graphicitem/search
请求方式：GET/POST
sAPP--局部/满身 sFor--图案格式 sGen--性别 sPat--图案内容 sTec--图案工艺

参数说明：
```

| 参数名  | 说明                       | 是否必须 |
| ------- | -------------------------- | -------- |
| refresh | 刷新缓存   比如：refresh=1 | NO       |

```
返回说明：
```

```json
成功：
data对应关系：​sAPP--局部/满身 sFor--图案格式 sGen--性别 sPat--图案内容 sTec--图案工艺
{
    "code": 0,
	"msg": "OK",
	"data": {
		"sGen":[
            {
                "cId":1,
                "cName":"男装"
            },
            {
                "cId":2,
                "cName":"女装"
            },
            {
                "cId":3,
                "cName":"男童"
            },
            {
                "cId":4,
                "cName":"女童"
            },
            {
                "cId":5,
                "cName":"童装"
            }
        ],
        ......
    }
}

失败：
{
	"code": "以10开头的五位数",
	"msg": "获取筛选项失败"
}
```

#### 3，获取图案详情接口

```
URL：http://yuntu.pop136.com/api/graphicitem/detail

header请求头：access-token  (之前约定的,登录或者绑定设备后有返回)

请求方式：POST

参数说明：
```

| 参数名 | 说明      | 是否必须 |
| ------ | --------- | -------- |
| popId  | 假表名_id | YES      |

```
返回说明：
    --  详情图片  取不到中图，就使用大图
```

```json
成功：
{
    "code":0,
    "msg":"ok",
    "data":{
        "imgUrls":[
            {
                "mbig":"http://img1.yuntu.pop-fashion.com/fashion/graphic/20190627000_sh5261/4/mbig/38014f832bc2e776a6da6e5a644c0164.jpg",
                "small":"http://img2.yuntu.pop-fashion.com/fashion/graphic/20190627000_sh5261/4/small/201906271_sh5261.jpg",
                "big":"http://img1.yuntu.pop-fashion.com/fashion/graphic/20190627000_sh5261/4/mbig/38014f832bc2e776a6da6e5a644c0164.jpg"
            },
            {
                "mbig":"http://img2.yuntu.pop-fashion.com/fashion/graphic/20190627000_sh5261/4/mbig/df866c69ea470c8c43dcaa35a3421729.jpg",
                "small":"http://img2.yuntu.pop-fashion.com/fashion/graphic/20190627000_sh5261/4/small/201906272_sh5261.jpg",
                "big":"http://img2.yuntu.pop-fashion.com/fashion/graphic/20190627000_sh5261/4/mbig/df866c69ea470c8c43dcaa35a3421729.jpg"
            },
            {
                "mbig":"http://img2.yuntu.pop-fashion.com/fashion/graphic/20190627000_sh5261/4/mbig/17fa5c874925193c42cefc05aacf7169.jpg",
                "small":"http://img3.yuntu.pop-fashion.com/fashion/graphic/20190627000_sh5261/4/small/gucci.jpg",
                "big":"http://img2.yuntu.pop-fashion.com/fashion/graphic/20190627000_sh5261/4/mbig/17fa5c874925193c42cefc05aacf7169.jpg"
            }
        ],
		 "memo": "数据库存在的",
         "iFavoriteType":"收藏状态 0未收藏 1已收藏",
         "dCreateTime":"时间",
    }
}



失败：
{
	"code": "以10开头五位数据",
	"msg": "获取数据失败"
}
```

####  4、获取收藏列表接口

```
URL：http://yuntu.pop136.com/api/favorite/getlist

header请求头：access-token  (之前约定的,登录或者绑定设备后有返回)

请求方式：GET/POST

参数说明：
```

| 参数名 | 说明 | 是否必须 |
| ------ | ---- | -------- |
| page   | 页码 | NO       |

```
返回说明：
```

```json
成功：
{
    "code":0,
    "msg":"OK",
    "data":[
        {
            "popId":"graphicitem_457730",
            "imgPath":"http://img1.yuntu.pop-fashion.com/fashion/graphic/2019062704_SH5259/small/ML-2019062704_pop_SH5259_006.jpg",
            "small":"http://img1.yuntu.pop-fashion.com/fashion/graphic/2019062704_SH5259/small/ML-2019062704_pop_SH5259_006.jpg",
            "mbig":"http://img3.yuntu.pop-fashion.com/fashion/graphic/2019062704_SH5259/mbig/df0a0d6fa6060dd61692307a3fc43159.jpg",
            "big":"http://img3.yuntu.pop-fashion.com/fashion/graphic/2019062704_SH5259/big/df0a0d6fa6060dd61692307a3fc43159.jpg",
            "iFavoriteType": "1", // 1--已收藏   0--未收藏
            "memo":"jpg 地毯 墙纸 软装 伊斯兰风格 局部图案 高清图 民族风 中东风格"
        }
    ],
    "info":{
        "total":"查询的总数量"，
        "pageSize":"每页显示多少条"
    }
}

失败：
{
	"code": "以100开头错误代码",
	"msg": "获取数据失败"
}
```

####  5、取消或者收藏收藏接口

```
URL：http://yuntu.pop136.com/api/favorite/setcollect

header请求头：access-token  (之前约定的,登录或者绑定设备后有返回)

请求方式：GET/POST

参数说明：
```

| 参数名 | 说明                                                         | 是否必须id |
| ------ | ------------------------------------------------------------ | ---------- |
| id     | 图案的ID                                                     | Yes        |
| t      | 图案的假表名                                                 | Yes        |
| handle | 区分  取消--cancel  或者  添加-- join  收藏用的,默认 handle = 'join' | Yes        |

```
返回说明：
```

```json
成功：
{
    "code": 0,
	"msg": "OK",
	"data": {
		"code": "10/20",
		"msg": "取消收藏成功/加入收藏成功"
	},
}

失败：
{
	"code": "以10开头五位数",
	"msg": "获取数据失败"
}
```

####  6、获取普通用户的以图搜图的次数

```
URL：http://yuntu.pop136.com/api/searchpicture/gettrynum

header请求头：access-token  (之前约定的,登录或者绑定设备后有返回)

请求方式：GET/POST

参数说明：
```

```
返回说明：
```

```json
成功：
{
    "code": 0,
	"msg": "OK"
}

失败：
{
	"code": "以10开头错误代码/  1014",
	"msg": "获取数据失败/  普通用户，试用次数结束"
}
```
####  7、以图搜图的上传图片接口

```
URL：http://yuntu.pop136.com/api/searchpicture/uploadpic

header请求头：access-token  (之前约定的,登录或者绑定设备后有返回)

请求方式：POST

参数说明：
```

| 参数名 | 说明                 | 是否必须id |
| ------ | -------------------- | ---------- |
| file   | 表单提交的图片name值 | Yes        |

```
返回说明：
```

```json
成功：
{
    "code": 0,
	"msg": "OK",
    "data":{
        "imgPath": "http://img3.fm.pop-fashion.com/fashion/graphic/20190404007_SH4688/mbig/fdebb60b59999a1df52a4b4c89e63899.jpg"
    }
}

失败：
{
	"code": "以10开头错误代码",
	"msg": "获取各种出错提示"
}
```
####  8、获取以图搜图列表

```
URL：http://yuntu.pop136.com/api/searchpicture/getlist

header请求头：access-token  (之前约定的,登录或者绑定设备后有返回)

请求方式 POST

参数说明：
```

| 参数名         | 说明                                                         | 是否必须id |
| -------------- | ------------------------------------------------------------ | ---------- |
| path           | 完整域名的图片url                                            | Yes        |
| page           | 页码                                                         | no         |
| newStorgreet   | 1-最新发布(时间倒序) 2-最受欢迎（浏览量） 3-以图搜图匹配度最高(默认) | no         |
| patternContent | 图案内容（多选） 11708,12089,12091                           | no         |
| format         | 图案格式（矢量/位图）（多选） 10961，10969，15090            | no         |
| gender         | 性别（多选）1,2,3,4,5                                        | no         |
| application    | 局部/满身(图案应用)（多选）14102,14103                       | no         |

```
返回说明：
```

```json
成功：
{
    "code": 0,
	"msg": "OK",
    "data": {
		{
        "popId":"表名_id"，    
        "imgPath":"http://img3.fm.pop-fashion.com/fashion/graphic/20190404007_SH4688/mbig/fdebb60b59999a1df52a4b4c89e63899.jpg"，
            "dCreateTime":"时间",
            "memo":"描述",
            "view_count":"浏览量",
            "create_time":"时间"
        }
	},
    "info":{
        "total":"筛选后的总数量"
        "pageSize":"每页显示多少条"
    }
}

失败：
{
	"code": "以10开头错误代码",
	"msg": "获取数据失败等信息"
}
```
####  9、登录接口

```
URL：http://yuntu.pop136.com/api/login/user

请求方式 GET/POST

参数说明：
```

| 参数名       | 说明                                      | 是否必须id |
| ------------ | ----------------------------------------- | ---------- |
| account      | 账号                                      | Yes        |
| password     | 密码                                      | Yes        |
| deviceNumber | 设备号,非vip用户可以不传，vip用户必须传递 | NO         |

```
返回说明：
```

```json
成功：
{
    "code": 0,
	"msg": "OK",
    "data": {
		"id": "用户注册成功的id",
		"account": "用户账号",
        "deviceNumber":"设备号", // vip用户有这一条
        "token":"jwt生成的字符串",
	},
    
}

失败：
{
	"code": "以10开头错误代码",
	"msg": "获取数据失败等信息"
}
{
    "code": "1022",
	"msg": "该用户没有开通绑定设备权限"
}
{
    "code": "1021",
	"msg": "该用户没有绑定当前设备"
    "data":{
        "userId"=>"用户id",
        "number"=>"剩余的没有绑定设备的设备数"
      }
}
{
    "code": "1026",
	"msg": "设备超限"
    "data":{
        "userId"=>"用户id",
        "total"=>"用户购买vip后约定的绑定的设备数"
      }
}

```
#### 10、设备绑定接口

```
URL：http://yuntu.pop136.com/api/login/binddevices

请求方式 GET/POST

参数说明：
```

| 参数名        | 说明             | 是否必须id |
| ------------- | ---------------- | ---------- |
| userId        | 用户id           | Yes        |
| deviceNumber  | 设备号，唯一     | Yes        |
| equipmentType | 设备型号，不唯一 | Yes        |

```
返回说明：
```

```json
成功：
{
    "code": 0,
	"msg": "OK",
    "data": {
		"id": "用户注册成功的id",
		"account": "用户账号",
        "deviceNumber":"设备号",
        "token":"jwt生成的字符串",
	},
    
}

失败：
{
	"code": "以10开头错误代码",
	"msg": "获取数据失败等信息"
}
{
    "code": "1022",
	"msg": "该用户没有开通绑定设备权限"
}
{
    "code": "1026",
	"msg": "设备超限",
    "data":{
        "userId"=>"用户id",
        "total"=>"用户购买vip后约定的绑定的设备数"
      }
}

```
#### 11、注册获取图形验证码接口

```
URL：http://yuntu.pop136.com/api/login/getverifyimg

请求方式 GET/POST

参数说明：
```

| 参数名  | 说明                   | 是否必须id |
| ------- | ---------------------- | ---------- |
| phoneNo | 随机数，默认是  123456 | Yes        |

```
返回说明：
```

```json
成功：
http://yuntu.pop136.com/api/login/getverifyimg  
返回  4位数的数字


```
#### 12、验证图形验证码接口

```
URL：http://yuntu.pop136.com/api/login/validateimgverify

请求方式 GET/POST

参数说明：
```

| 参数名     | 说明                   | 是否必须id |
| ---------- | ---------------------- | ---------- |
| phoneNo    | 随机数，默认是  123456 | Yes        |
| verifyCode | 用户输入的验证码       | Yes        |

```
返回说明：
```

```json
成功：
{
    "code": 0,
	"msg": "OK", 
}

失败：
{
	"code": "以10开头错误代码",
	"msg": "获取数据失败等信息"
}


```
#### 13、获取短信验证码接口

```
URL：http://yuntu.pop136.com/api/login/getphoneverificationcode

请求方式 GET/POST

参数说明：
```

| 参数名    | 说明                                        | 是否必须id |
| --------- | ------------------------------------------- | ---------- |
| cellPhone | 手机号                                      | Yes        |
| type      | 1--注册短信验证码\|\| 2--忘记密码短信验证码 | Yes        |
| imgSeed   | 生成图片验证码的种子(随机数)                | Yes        |
| imgCode   | 图片验证码                                  | Yes        |

```
返回说明：
```

```json
成功：
{
    "code": 0,
	"msg": "短信发送成功", 
    "data":{
        "info":60 // 60秒
    }
}

失败：
{
	"code": "以10开头错误代码",
	"msg": "获取数据失败等信息"
}


```
#### 14、修改密码接口

```
URL：http://yuntu.pop136.com/api/login/modifypassword

header请求头：access-token  (之前约定的,登录或者绑定设备后有返回)

请求方式 POST

参数说明：
```

| 参数名        | 说明     | 是否必须id |
| ------------- | -------- | ---------- |
| newPassWord   | 新密码   | Yes        |
| oldPassWord   | 旧密码   | Yes        |
| reNewPassword | 重输密码 | Yes        |

```
返回说明：
```

```json
成功：
{
    "code": 0,
	"msg": "ok", 
}

失败：
{
	"code": "以10开头错误代码",
	"msg": "获取数据失败等信息"
}


```
#### 15、忘记密码接口

```
URL：http://yuntu.pop136.com/api/login/smsmodifypassword

请求方式 POST

参数说明：
```

| 参数名      | 说明           | 是否必须id |
| ----------- | -------------- | ---------- |
| newPassWord | 新密码         | Yes        |
| account     | 账号           | Yes        |
| phone       | 手机号         | Yes        |
| verify      | 手机短信验证码 | Yes        |

```
返回说明：
```

```json
成功：
{
    "code": 0,
	"msg": "ok", 
}

失败：
{
	"code": "以10开头错误代码",
	"msg": "获取数据失败等信息"
}


```
#### 16、注册接口

```
URL：http://yuntu.pop136.com/api/account/register

请求方式 POST

参数说明：
```

| 参数名     | 说明       | 是否必须 |
| ---------- | ---------- | -------- |
| account    | 账号       | Yes      |
| phone      | 手机号     | Yes      |
| password   | 密码       | Yes      |
| check_code | 短信验证码 | Yes      |

```
返回说明：
```

```json
成功：
{
    "code": 0,
	"msg": "ok", 
}

失败：
{
	"code": "以10开头错误代码",
	"msg": "获取数据失败等信息"
}


```
#### 17、app版本接口

```
URL：http://yuntu.pop136.com/api/account/getappversion

请求方式 get


```

```
返回说明：
```

```json
成功：
{
    "code": 0,
	"msg": "ok", 
    "data":{
        "forceUp":false
         或者
         云图2.0
    }
}


```
####  18、获取用户权限接口

```
URL：http://yuntu.pop136.com/api/account/getpowerinfo

header请求头：access-token  (之前约定的,登录或者绑定设备后有返回)

请求方式：GET/POST

```

```
返回说明：
```

```json
成功：
{
    "code": 0,
	"msg": "OK",
	"data": {
		"identity":"VIP/GENERAL",
        "privilege":{ // GENERAL(普通用户没有) 
            {
                "sColumn":"1",
                "dEndTime":1596595980
            },
            {
                "sColumn":"3",
                "dEndTime":1596423180
            }
        }
	},
     "info":"请求时间:Y-m-d H:i:s"
}

失败：
{
	"code": "以100开头错误代码",
	"msg": "获取数据失败"
}
```
#### 19、获取用户虚拟样衣模板数据接口

```
URL：http://yuntu.pop136.com/api/account/virtualsampletemplate

header请求头：access-token  (之前约定的,登录或者绑定设备后有返回)

请求方式：GET/POST

参数说明：
```

| 参数名 | 说明                                               | 是否必须 |
| ------ | -------------------------------------------------- | -------- |
| site   | 站点：1-服装，2-箱包，3-鞋子，5-家纺               | Yes      |
| type   | 不同入口：1=>图案详情的模拟成品，2=>拍照的模拟成品 | Yes      |

```
返回说明：
```

```json
成功：
{
    "code": 0,
	"msg": "OK",
	"data": {
		 "11807":[
            {
                "id":"279",
                "iAccountId":"106645",
                "iClassifyId":"11807",
                "sThumbnailPath":"http://img1.yuntu.pop-fashion.com/fashion/fm/virtual_spl/20171220/thumbnail_5a3a19bd0f7fa3438.jpg",
                "sLargePath":"http://img1.yuntu.pop-fashion.com/fashion/fm/virtual_spl/20171220/large_5a3a19bd27ad67720.jpg",
                "dPublishTime":"2017-11-09 09:52:05",
                "iWeight":"10032",
                "iStatus":"1",
                "iSite":"1",
                "sShowSite":"1,6"
            },
            {
                "id":"301",
                "iAccountId":"0",
                "iClassifyId":"11807",
                "sThumbnailPath":"http://img1.yuntu.pop-fashion.com/fashion/fm/virtual_spl/20171206/thumbnail_5a278d1e24bce6049.png",
                "sLargePath":"http://img1.yuntu.pop-fashion.com/fashion/fm/virtual_spl/20171206/large_5a278d1e45b792271.png",
                "dPublishTime":"2017-12-06 14:24:30",
                "iWeight":"21",
                "iStatus":"1",
                "iSite":"1",
                "sShowSite":"1,6"
            },
            {
                "id":"298",
                "iAccountId":"0",
                "iClassifyId":"11807",
                "sThumbnailPath":"http://img1.yuntu.pop-fashion.com/fashion/fm/virtual_spl/20171205/thumbnail_5a262ba66be487097.png",
                "sLargePath":"http://img1.yuntu.pop-fashion.com/fashion/fm/virtual_spl/20171205/large_5a262ba67e72a9150.png",
                "dPublishTime":"2017-12-05 13:16:22",
                "iWeight":"20",
                "iStatus":"1",
                "iSite":"1",
                "sShowSite":"1,6"
            },
             ........... // 还有很多
	},
    "info":{
        "site":"所选站点为:1",
        "map":{
            "男装":11807,
            "女装":11808,
            "童装":11809,
            "配饰":11810,
            "定制":12218
        },
        "keys":[
            "男装",
            "女装",
            "童装",
            "配饰",
            "定制"
        ]
    }
}

失败：
{
	"code": "以100开头错误代码",
	"msg": "获取数据失败等信息"
}

普通用户  没有选择哪个站点  and  没有模板  返回
{
    "code": 0,
	"msg": "OK",
    "data":{
       空
    },
    "info":{
        "site":""
    }

```
#### 20、获取无状态的token（如：游客）

```
URL：http://yuntu.pop136.com/api/apptoken/get

请求方法：get
```

```
返回说明：
```

```json
成功：
{
    "code":0,
    "msg":"OK",
        "data":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1NTcxMzczOTAsIm5iZiI6MTU1NzEzNzM5MH0.PC9noIOhHkRfrKIiOD-REy4WESEQdQqZNFSWYhufo8I"
}


```
#### 21、获取分类筛选项+icon

```
URL：http://yuntu.pop136.com/api/graphicitem/sortsearch

请求方法：get/post
```

```
返回说明：
```

```json
成功：
 {
    "code":0,
    "msg":"OK",
    "data":[
        {
            "cId":"11699",
            "cName":"动物",
            "imgPath":"http://yuntu.pop136.com/global/images/app/pattern/icon_dongwu.png"
        },
        {
            "cId":"11700",
            "cName":"人物",
            "imgPath":"http://yuntu.pop136.com/global/images/app/pattern/icon_renwu.png"
        },
        {
            "cId":"11701",
            "cName":"植物",
            "imgPath":"http://yuntu.pop136.com/global/images/app/pattern/icon_zhiwu.png"
        },
        {
            "cId":"11707",
            "cName":"花纹",
            "imgPath":"http://yuntu.pop136.com/global/images/app/pattern/icon_huawen.png"
        },
        {
            "cId":"11708",
            "cName":"文字",
            "imgPath":"http://yuntu.pop136.com/global/images/app/pattern/icon_wenzi.png"
        },
 ..................
}


```

#### 22、获取云图APP版本号

```
目的： 获取云图APP版本号，来判断 云图APP 是否升级

原来接口: http://yuntu.pop136.com/api/account/getappversion
接口: https://yuntu.pop136.com/api/account/getappversion
请求方式： get/post
```

| 请求头值    | 说明                                                | 是否必须 |
| ----------- | --------------------------------------------------- | -------- |
| device_type | 设备号:  ios  || android                          | YES      |
| version     | 版本号:  5.3.7(ios) || 1.0.7(android)  当前版本号 | YES      |

```shell
返回说明：

-- 1，成功返回值：

1-1，需要更新：
{
    "code":0,
    "msg":"OK",
    "data":{
        "forceUp":true
    }
}

1-2，不需要更新：
{
    "code":0,
    "msg":"OK",
    "data":{
        "forceUp":false
    }
}

-- 2，失败返回值：
{
    "code":100080,
    "msg":"参数缺失"
}
```
#### 23、获取达观推荐猜你喜欢接口

```
URL：https://yuntu.pop136.com/api/recommend/getlist

header请求头：access-token  (之前约定的,登录或者绑定设备后有返回)

请求方式：POST
```
```
返回说明：
```

```json
成功：
{
    "code":0,
    "msg":"ok",
    "data":[
        {
            "popId":"graphicitem_401655",
            "imgPath":"https://imgyt1.pop-fashion.com/fashion/graphic/20171027-sh4432-04/2/small/0-384161141449_sh443.jpg"
        },
        {
            "popId":"graphicitem_401656",
            "imgPath":"https://imgyt3.pop-fashion.com/fashion/graphic/2018051077707-sh4677/small/0-27903800416-SH4677.jpg"
        },
        {
            "popId":"graphicitem_401649",
            "imgPath":"https://imgyt3.pop-fashion.com/fashion/graphic/20171013005-sh3525/small/20171013003-sh3525.jpg"
        },
        {
            "popId":"graphicitem_401650",
            "imgPath":"https://imgyt1.pop-fashion.com/fashion/graphic/20171013005-sh3525/small/20171013004-sh3525.jpg"
        }
    ],
    "info":{
        "scene_type":"personal_cloud",
        "request_id":"1569304501324184",
        "allTotal":261363,
    }
}
失败 ：
{
    "code":100080,
    "msg":"获取列表数据失败"
}
```
#### 24，获取详情相关推荐接口

```
URL：https://yuntu.pop136.com/api/recommend/guesslike

header请求头：access-token  (之前约定的,登录或者绑定设备后有返回)

请求方式：POST

参数说明：
```

| 参数名 | 说明      | 是否必须 |
| ------ | --------- | -------- |
| popId  | 假表名_id | YES      |

```json
成功：
{
    "code":0,
    "msg":"ok",
    "data":[
        {
            "popId":"graphicitem_401655",
            "imgPath":"https://imgyt1.pop-fashion.com/fashion/graphic/20171027-sh4432-04/2/small/0-384161141449_sh443.jpg"
        },
        {
            "popId":"graphicitem_401656",
            "imgPath":"https://imgyt3.pop-fashion.com/fashion/graphic/2018051077707-sh4677/small/0-27903800416-SH4677.jpg"
        },
        {
            "popId":"graphicitem_401649",
            "imgPath":"https://imgyt3.pop-fashion.com/fashion/graphic/20171013005-sh3525/small/20171013003-sh3525.jpg"
        },
        {
            "popId":"graphicitem_401650",
            "imgPath":"https://imgyt1.pop-fashion.com/fashion/graphic/20171013005-sh3525/small/20171013004-sh3525.jpg"
        }
    ],
    "info":{
        "scene_type":"relate_cloud",
        "request_id":"1569304501324184"
    }
}
失败 ：{
    "code":100081,
    "msg":"获取详情数据失败"
}
```