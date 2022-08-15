1. 首先找到/opt/lampp/etc/httpd.conf：
- 去掉==Include etc/extra/http-vhosts.conf== 前面的#号，结果如下：
> - \# Virtual hosts
> - Include etc/extra/httpd-vhosts.conf
2. 修改/opt/lampp/etc/extra/httpd-vhost.conf：
- 在DocumentRoot后添加所绑定的目录
- 在ServerName后添加设置的域名
> - <VirtualHost *:80>
> - DocumentRoot "/opt/lampp/htdocs/demo"
> - ServerName demo.com
> - </VirtualHost>
3. 修改 /etc/hosts:
- 为设置的域名指定IP地址
> - 127.0.0.1      demo.com
4. 重启apache，可通过域名demo.com正常访问