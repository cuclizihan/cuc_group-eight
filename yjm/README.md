# 中传放心传之文件的下载

## 实验内容
   ### 1.搭建实验环境，运行示例demo
     - 本次实验采用Windows环境代码开发，后移植至Linux系统。
     - 首先在virtualbox中安装Ubuntu系统，搭建了Python、Apache和MySQL环境，并同时安装docker环境（注：这里不使用官方镜像，而是使用中科大镜像源代替）。检查好没问题之后运行示例demo。

----

   - 自主完成email（由于小组暂时还没有讨论出来分工，所以自己先按照想法弄了一个email部分）
     - 中传放心传中缺少的一个功能是通过邮件发送文件。在此过程中我们需要先发起邮件发送请求，收到请求后检查是否具备发送请求。

     ```
     # 发送请求
     def send(title, recipients, html):
           message = Message(title, recipients, html=html)
           print(message)
           is_ok = True
           try:
               email.send(message)
           except Exception:
               is_ok = False
           return is_ok


     def test_mail(email):
           if email is None:  # 提醒用户添加email
               return "<p>通过访问 <strong>/test_mail?email=【你的邮箱】</strong> 邮件发送文件更方便！</p>"
           # 否则就正常发送邮件
           title = "邮件发送"
           recipients = [email]
           body = "<h1>邮件发送成功！</h1>"
           if send(title, recipients, body):  # 发送成功
               results = '<h1>邮件已发送，注意查收！</h1>'
           else:  # 发送失败
               results = '发送失败，请再次尝试！'
           return results
     ```
     -  但是经过讨论后小组认为这个不重要，先舍弃它

----

   ### 2.完成分工，文件的下载
   #### 2.1自签名证书（X.509）
  -  X.509 证书己应用在包括 `TLS/SSL` 在内的众多 Intenet 协议里,证书里含有公钥、身份信息和签名信息，它的结构优势在于它是由公钥和私钥组成的密钥对而构建的安全性较高。由于是自己做，所以证书的申请机构和颁发机构都是自己。
   #### 2.2 证书主体结构
   ```
   Certificate ::= SEQUENCE {
        
        tbsCertificate       TBSCertificate, -- 证书主体
        
        signatureAlgorithm   AlgorithmIdentifier, -- 证书签名算法标识
        
        signatureValue       BIT STRING --证书签名值,是使用signatureAlgorithm部分指定的签名算法对tbsCertificate证书主题部分签名后的值.
    }

   TBSCertificate ::= SEQUENCE {
        version         [0] EXPLICIT Version DEFAULT v1, -- 证书版本号
        
        serialNumber         CertificateSerialNumber, -- 证书序列号，对同一CA所颁发的证书，序列号唯一标识证书
        
        signature            AlgorithmIdentifier, --证书签名算法标识
        
        issuer               Name,                --证书发行者名称
        
        validity             Validity,            --证书有效期
        
        subject              Name,                --证书主体名称
        
        subjectPublicKeyInfo SubjectPublicKeyInfo,--证书公钥
        
        issuerUniqueID [1] IMPLICIT UniqueIdentifier OPTIONAL,
                             -- 证书发行者ID(可选)，只在证书版本2、3中才有
        
        subjectUniqueID [2] IMPLICIT UniqueIdentifier OPTIONAL,
                             -- 证书主体ID(可选)，只在证书版本2、3中才有
        
        extensions      [3] EXPLICIT Extensions OPTIONAL
                             -- 证书扩展段（可选），只在证书版本3中才有
   }

   ```
   
   #### 2.3文件的读取
   - 使用python的open()函数，以可读方式打开证书：
        ```
        filename = 'test.pem'
        f = open(filename,'rb')
        ```
   #### 2.4类型判断
   - X.509 证书在存储时候是按照字节存储的，同时我们需要根据数据标识类型来对具体的某个段进行操作，所以方法具体如下：
        ```
        data = f.read(1)
        type = ord(date);#将字节转为对应的数字
        if type < 0x80: #那么则证明满足 universal 类型，可采用对应操作方法
        elif type >= 0x80 and type < 0xa0:#隐式 Tag 操作类型
        elif type >= 0x00: #显式 Tag 操作类型
        ```
   #### 3.1 openssl pem生成公钥私钥及文件
   - 使用 `openssl` 包我们可以做以下事情：
     - Creation of RSA, DH and DSA Key Parameters # 创建密钥 key 
     - Creation of X.509 Certificates, CSRs and CRLs # 创建证书 
     - Calculation of Message Digests                # 
     - Encryption and Decryption with Ciphers # 加密、解密 
     - SSL/TLS Client and Server Tests        # SSL 服务器端/客户端测试 
     - Handling of S/MIME signed or encrypted Mail  # 处理签名或加密了的邮件 

   -  3.1.1 生成RSA密钥的方法 
   ```
    openssl genrsa -des3 -out privkey.pem 2048 
   ```
    这个命令会生成一个2048位的密钥，同时有一个des3方法加密的密码，如果你不想要每次都输入密码，可以改成：
    ``` 
    openssl genrsa -out privkey.pem 2048 
    ```
   建议用2048位密钥，少于此可能会不安全或很快将不安全。 
 
   - 3.1.2 生成一个证书请求 
   
    ```
    openssl req -new -key privkey.pem -out cert.csr 
    ```
   这个命令将会生成一个证书请求，当然，用到了前面生成的密钥privkey.pem文件 
   这里将生成一个新的文件cert.csr，即一个证书请求文件，你可以拿着这个文件去数字证书颁发机构（即CA）申请一个数字证书。CA会给你一个新的文件cacert.pem，那才是你的数字证书。 
 
   我们这里证书的申请机构和颁发机构都是自己，就可以用下面这个命令来生成证书： 
   ```
   openssl req -new -x509 -key privkey.pem -out cacert.pem -days 1095 
   ```
   这个命令将用上面生成的密钥privkey.pem生成一个数字证书cacert.pem 
 
   - 3.1.3 使用数字证书和密钥 
   有了privkey.pem和cacert.pem文件后就可以在自己的程序中使用

   #### 3.2 Python cryptography库及RSA非对称加密
   - 3.2.1 安装cryptography库
   ```
   python -m pip install cryptography
   ```
   - 3.2.2 设定公钥生成私钥
   ```
    private_key = rsa.generate_private_key(
    public_exponent = 65537,
    key_size = 2048,
    backend = default_backend()
    )
    public_key = private_key.public_key()
   ```
   - 3.2.3 读取密钥
   ```
   from cryptography.hazmat.backends import default_backend
   from cryptography.hazmat.primitives import serialization

   with open("private_key.pem", "rb") as key_file:
      private_key = serialization.load_pem_private_key(
        key_file.read(),
        password=None,
        backend=default_backend()
       )
   ```
  

----
# 问题及解决
- demo运行问题：
  - 运行的代码布局有问题：因为当时搞得有点头昏脑胀就暂时没有继续纠结这个问题
  ![demo运行布局问题](img\demo_question1.png)
  ![demo运行问题](img\demo_question2.png)
- 签名和验证签名出错
  - 按照 `3.1.1` `3.1.2` `3.1.3` 得到 `prvkey.pem` 文件，但是打开是空的

  - 通过 `openssl genrsa -out privkey.pem 2048` 生成的私钥pem并不是能直接拿来用的，得把RSA私钥转换成PKCS8格式
  ```
  pkcs8 -topk8 -inform PEM -in rsa_private_key.pem -outform PEM –nocrypt
  ```
  按照此方法获得的 `pem` 文件依然为空。此路不通，我选择换条路走——cryptography的出现拯救了我。

----
# 参考文献
 - [Ubuntu中docker安装教程](https://docs.docker.com/engine/install/ubuntu/)
 - [openssl生成自签名证书](https://www.jianshu.com/p/0e9ee7ed6c1d)
 - [维基百科_X.509证书](https://zh.wikipedia.org/wiki/X.509)
 - [使用openssl生成RSA公钥和私钥对](https://blog.csdn.net/weiyuefei/article/details/76269790)
 - [数字证书 X509详解 && python解析SSL证书](https://blog.csdn.net/weixin_43801662/article/details/120306356)
 - [Python cryptography库及RSA非对称加密](https://blog.csdn.net/photon222/article/details/109447327)
 - [师哥师姐中传放心传作品](https://github.com/Jasmine2020/SecurityManagementSystem)