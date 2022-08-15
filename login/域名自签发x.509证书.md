### 准备工作
- 下载openssl工具
### 生成CA证书
1. 创建私钥：
> - openssl genrsa -out ca/ca-key.pem 1024
2. 创建证书请求：
> - openssl req -new -out ca/ca-req.csr -key ca/ca-key.pem
- - 正确填写信息
3. 自签署证书：
> - openssl x509 -req -in ca/ca-req.csr -out ca/ca-cert.pem -signkey ca/ca-key.pem -days 3650
4. 将证书导出成浏览器支持的.p12格式：
> - openssl pkcs12 -export -clcerts -in ca/ca-cert.pem -inkey ca/ca-key.pem -out ca/ca.p12
### 生成server证书
1. 创建私钥：
> - openssl genrsa -out server/server-key.pem 1024
2. 创建证书请求：
> - openssl req -new -out server/server-req.csr -key server/server-key.pem
- - 此时Common Name要填写域名信息
3. 自签署证书：
> - openssl x509 -req -in server/server-req.csr -out server/server-cert.pem -signkey server/server-key.pem -CA ca/ca-cert.pem -CAkey ca/ca-key.pem -CAcreateserial -days 3650
4. 将证书导出成浏览器支持的.p12格式：
> - openssl pkcs12 -export -clcerts -in server/server-cert.pem -inkey server/server-key.pem -out server/server.p12
### 生成client证书
1. 创建私钥：
> - openssl genrsa -out client/client-key.pem 1024
2. 创建证书请求：
> - openssl req -new -out client/client-req.csr -key client/client-key.pem
3. 自签署证书：
> - openssl x509 -req -in client/client-req.csr -out client/client-cert.pem -signkey client/client-key.pem -CA ca/ca-cert.pem -CAkey ca/ca-key.pem -CAcreateserial -days 3650
4. 将证书导出成浏览器支持的.p12格式：
> - openssl pkcs12 -export -clcerts -in client/client-cert.pem -inkey client/client-key.pem -out client/client.p12
### 在浏览器中配置
- 进入浏览器的证书设置
- 在“权威机构”中添加ca-cert.pem
- 在“你的证书”中添加server的证书server.p12