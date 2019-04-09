<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 应用设置
// +----------------------------------------------------------------------

return [
    'alipay' => [
        'appId' => '2019031063479893',
        'gatewayUrl' => 'https://openapi.alipay.com/gateway.do',
        'rsaPrivateKey' => 'MIIEpAIBAAKCAQEApFalu/ph3oGkEKsfq0qQjU5qa0gzVhvvStQo59ShqC7DyuJF5LKyZQRqmEXGM0q+DkkEy7W+/Hu6Cqj0/c/CweE1a3ULTjx8bHTqYhgTtVLHKmUn9VTpf1vlgCwVBUOBHtklx4IkwrOcCbIZpNC69UdWz4HyMJlrK1VswvFqi3avEt5kI074z4fhRVKJyOU/EvW53y7gFqUvYTmh/UT/XSvSnGkd7UOciRT/ET29D4yf66eIe+57AdaNEeEWYZLPK30ijm7ZHdAW+8tV6YFF008sxsbu84wOMNPUNtpRRDcgGB33HSTyPBnh9s7uHPPhQEAn2yWzIuSZrnl0KUI1bQIDAQABAoIBACSGdZOQFMyFd9eUkRdbHUGq2hA6Hd7XmpnBCc5s/fkJW87t96ba6Ld3AdISP/kKda9rHzu/i1FGlpTj9H+s+5Zn3Z2ih/69htH8MlPXEhpM6aNMlFL8qmD6JcoVAh8HX91hWfo7vLvhe57UDMsK8WKulmbMdO3ES4N4GxJA4fNY9FJP49hSxcF3nj9lVz3yCOuF7jmJ0JmGU8GNa0TYnjBMtWRSffL497Z9BRXAXG0XfH4DXyjTGIXrBIXm4UbdSuLhrTw6BMiHxFOohCya6KrbT905yUavZXIExWn0EfxPotpgEj7MCWw2yLArkPAR81oz8pM6ZPQJx9qV904hvUECgYEA1ga0qrkymo+G4av7iDOSxlaLofPLCGl5kcfgMb9dM2z2oqyvG1uw0XqfAXBZ9lyUGTP9jKkzqQg0GpICSQJyqeRg+XWw7HJpONT/9JbCts6DhrrEzRk9CgFyjjKPIcE0VPKBuO24LAMEwLEruVh+Z+ar8IrOytKcHglSjP5I73UCgYEAxJFYDLeSQtQac8nIxtMPErzJP/cPKbGeQOjfu8Z3cBwGRFc2FgQlvqhxE8J304nhr5IpNRRi2bC9pViMLZ4j4D1LDI5nOdmdZ/N1ysEq8KKn9VYuhK/wz6VFO8Z5Kc+KNRcBtgheLtx1LhXLHhESLzcS2V8V6UpLcAEAxEwIJxkCgYAFx8SVX88YEYxJCAYRFaN/K9M1mon/PioEX+uULGDuBKFcn2FTUdSis7cbqNclKjbtv1O3utIkXI5bsVnScvh50is0UZNnr2dcG5SWHIRBv64Zs22hRG87l/JqGL943+jz6mDKh9ETjzlPovlVViiD0d0O8BDJtYp13TaXjD6YCQKBgQC0xfIS5/Pzz7pwB//Eki/HjKUMVXq1XmrqyP+RYglPqgY9bXMJNlE8EQ7FHFA01BYg8CtDbHcoOnl+iXuJLGlT/Sp65q+aLT6sbeNDTvjdiskqQFLRpjixzg1o1rNxNzOkdX8WWIW9VPPTKSm+gfaWQ3DrRZ4SCUhtPg7leAL7aQKBgQC3663WiP2wWJ3sV02+uff7sgnGl9lFEcNih9sAKUGNCborlax+QMuUEFkXReLErzHmwoTuTm8O7o5yJbgtR8qJydYDO4Vid4+bkh/BXfwhSv4jz0j9INB5nOL3nJPP8agb0Zm77jfDo+p9GjQjO83rZzbdUdwaraTZMoLL6SUyZA==',
        'rsaPublicKey' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApFalu/ph3oGkEKsfq0qQjU5qa0gzVhvvStQo59ShqC7DyuJF5LKyZQRqmEXGM0q+DkkEy7W+/Hu6Cqj0/c/CweE1a3ULTjx8bHTqYhgTtVLHKmUn9VTpf1vlgCwVBUOBHtklx4IkwrOcCbIZpNC69UdWz4HyMJlrK1VswvFqi3avEt5kI074z4fhRVKJyOU/EvW53y7gFqUvYTmh/UT/XSvSnGkd7UOciRT/ET29D4yf66eIe+57AdaNEeEWYZLPK30ijm7ZHdAW+8tV6YFF008sxsbu84wOMNPUNtpRRDcgGB33HSTyPBnh9s7uHPPhQEAn2yWzIuSZrnl0KUI1bQIDAQAB',
        'alipayrsaPublicKey' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAvy/jTdbjqHgTef15HF6OTU9IIwfj7zl4r76kB3/gHk2fEglSkHE0isQ6ooqgS8c9cHUGDnIilA0xak+1DHmN1Vab3Y2y4ti2pAbpaVhT7T826ye0sceTinq/zWryANgvRo/Wy0pq+yZxSwLzNfDHk5OQ1R/dF0KFhjvBCfG93xWG/15HZPjPluna/E5fDj241zQ4LfRM++Q+gRAGdtlIiiQ8ial0tXeXlW64u0/Xb8uYefJM3OtE7lR3Usj2ebRuwJCQbdcbTiNWTGcjalitKT+FNBucHqnLpEV7VjExZGkbRAqf4svLVvhNBr8i0PgHWa3W9rycXxw004MAaCjpZQIDAQAB',
        //'seller'            => '支付宝邮箱',//可不要
        'format' => 'json',
        'charset' => 'UTF-8',
        'signType' => 'RSA2',
        'transport' => 'http',
    ],
    'SUCCESS' => ['status' => 200, 'msg' => "成功"],
    'NOT_LOGIN' => ['status' => 400, 'msg' => "账号身份过期"],
    'SYS_ERROR' => ['status' => 401, 'msg' => "服务器异常,请稍后再试"],
    'PARAMS_ERROR' => ['status' => 409, 'msg' => "非法请求"],
    'NOT_SUPPORTED' => ['status' => 410, 'msg' => "token已过期"],
    'TOO_FREQUENT' => ['status' => 445, 'msg' => "太频繁的调用"],
    'UNKNOWN_ERROR' => ['status' => 499, 'msg' => "未知错误"],
    //local_path
    'local_path' => 'http://123.151.0.156',
    //token key
    'token_key' => 'top_school_no1',
    //salt
    'salt' => 'XBxYmkC46y7b8C5qN56z46y7b8C5qN56zsb8C5qN56z46y7b8C5qN56zs',
    // 应用名称
    'app_name' => '',
    // 应用地址
    'app_host' => '',
    // 应用调试模式
    'app_debug' => true,
    // 应用Trace
    'app_trace' => false,
    // 是否支持多模块
    'app_multi_module' => true,
    // 入口自动绑定模块
    'auto_bind_module' => false,
    // 注册的根命名空间
    'root_namespace' => [],
    // 默认输出类型
    'default_return_type' => 'json',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return' => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler' => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler' => 'callback',
    // 默认时区
    'default_timezone' => 'Asia/Shanghai',
    // 是否开启多语言
    'lang_switch_on' => false,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter' => '',
    // 默认语言
    'default_lang' => 'zh-cn',
    // 应用类库后缀
    'class_suffix' => false,
    // 控制器类后缀
    'controller_suffix' => false,

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module' => 'index',
    // 禁止访问模块
    'deny_module_list' => [''],
    // 默认控制器名
    'default_controller' => 'Index',
    // 默认操作名
    'default_action' => 'index',
    // 默认验证器
    'default_validate' => '',
    // 默认的空模块名
    'empty_module' => '',
    // 默认的空控制器名
    'empty_controller' => 'Error',
    // 操作方法前缀
    'use_action_prefix' => false,
    // 操作方法后缀
    'action_suffix' => '',
    // 自动搜索控制器
    'controller_auto_search' => false,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // PATHINFO变量名 用于兼容模式
    'var_pathinfo' => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch' => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr' => '/',
    // HTTPS代理标识
    'https_agent_name' => '',
    // IP代理获取标识
    'http_agent_ip' => 'X-REAL-IP',
    // URL伪静态后缀
    'url_html_suffix' => 'html',
    // URL普通方式参数 用于自动生成
    'url_common_param' => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type' => 0,
    // 是否开启路由延迟解析
    'url_lazy_route' => false,
    // 是否强制使用路由
    'url_route_must' => false,
    // 合并路由规则
    'route_rule_merge' => false,
    // 路由是否完全匹配
    'route_complete_match' => false,
    // 使用注解路由
    'route_annotation' => false,
    // 域名根，如thinkphp.cn
    'url_domain_root' => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert' => true,
    // 默认的访问控制器层
    'url_controller_layer' => 'controller',
    // 表单请求类型伪装变量
    'var_method' => '_method',
    // 表单ajax伪装变量
    'var_ajax' => '_ajax',
    // 表单pjax伪装变量
    'var_pjax' => '_pjax',
    // 是否开启请求缓存 true自动缓存 支持设置请求缓存规则
    'request_cache' => false,
    // 请求缓存有效期
    'request_cache_expire' => null,
    // 全局请求缓存排除规则
    'request_cache_except' => [],
    // 是否开启路由缓存
    'route_check_cache' => false,
    // 路由缓存的Key自定义设置（闭包），默认为当前URL和请求类型的md5
    'route_check_cache_key' => '',
    // 路由缓存类型及参数
    'route_cache_option' => [],

    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl' => Env::get('think_path') . 'tpl/dispatch_jump.tpl',
    'dispatch_error_tmpl' => Env::get('think_path') . 'tpl/dispatch_jump.tpl',

    // 异常页面的模板文件
    'exception_tmpl' => Env::get('think_path') . 'tpl/think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message' => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg' => false,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle' => '',

];
