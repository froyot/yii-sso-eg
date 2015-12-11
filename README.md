Yii 2 Advanced SSO Login Example
===============================

### 实现SSO登陆的步骤如下:

*   子系统点击登陆，带上redirect作为返回路径跳转到sso统一登录页面(passport)进行
登录。

*   用户在passport登录之后(写入passport的session,cookie等登录信息)，通过js向各个
子系统的登录接口(并带上passport的临时票据ticket)

*   子系统的登录接口中,确定请求的合法性，并根据请求的临时票据ticket请求passport服务器获
取用户信息(这里是服务器跟服务器直接通讯，因此passport需要能够根据ticket获取到用户信息)，
并将用户信息写入登陆状态中，登录用户，写**子系统**的session,cookie

*   所有子系统写完之后，根据一开始请求passport的跳转参数redirect进行跳转条主，
回到登录前的子系统


### 退出过程：
*   子系统点击退出，跳转到passport的退出操作，并生存退出票据ticket
*   passport退出页面中可通过ajax请求各个子系统
*   各个子系统判断ticket的合法性，退出用户


目录:

```

auth    auth server

frontend  client test

```
