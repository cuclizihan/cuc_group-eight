import base64
from Crypto.PublicKey import RSA
from Crypto.Cipher import PKCS1_v1_5 as Cipher_PKC
from Crypto import Random
from Crypto.Hash import SHA256
from Crypto.Signature import PKCS1_v1_5 as Signature_PKC
import cgi
import cgitb

class HandleRSA():
    def create_rsa_key(self):
        # 伪随机数生成器
        random_gen = Random.new().read
        # 生成秘钥对实例对象：1024是秘钥的长度
        rsa = RSA.generate(1024, random_gen)

        # Server的秘钥对的生成
        private_pem = rsa.exportKey()
        with open("server_private.pem", "wb") as f:
            f.write(private_pem)

        public_pem = rsa.publickey().exportKey()
        with open("server_public.pem", "wb") as f:
            f.write(public_pem)

        # Client的秘钥对的生成
        private_pem = rsa.exportKey()
        with open("client_private.pem", "wb") as f:
            f.write(private_pem)

        public_pem = rsa.publickey().exportKey()
        with open("client_public.pem", "wb") as f:
            f.write(public_pem)



    # Server使用自己的私钥对内容进行签名
    def signature(self,data:str):
        # 读取私钥
        private_key = RSA.import_key(open("server_private.pem").read())
        # 根据SHA256算法处理签名内容data
        sha_data= SHA256.new(data.encode("utf-8")) # b类型

        # 私钥进行签名
        signer = Signature_PKC.new(private_key)
        sign = signer.sign(sha_data)

        # 将签名后的内容，转换为base64编码
        sign_base64 = base64.b64encode(sign)
        return sign_base64.decode()

    # Client使用Server的公钥对内容进行验签

    def verify(self,data:str,signature:str) -> bool:
        # 接收到的sign签名 base64解码
        sign_data = base64.b64decode(signature.encode("utf-8"))

        # 加载公钥
        piblic_key = RSA.importKey(open("server_public.pem").read())

        # 根据SHA256算法处理签名之前内容data
        sha_data = SHA256.new(data.encode("utf-8"))  # b类型

        # 验证签名
        signer = Signature_PKC.new(piblic_key)
        is_verify = signer.verify(sha_data, sign_data)

        return is_verify

if __name__ == '__main__':

    mrsa = HandleRSA()
    # mrsa.create_rsa_key()
    cgitb.enable()
    form = cgi.FieldStorage() # 获取网页提交的数据
    fileitem = form['uploadfile']

    # 一次性读取文本内容
    with open(fileitem, 'r', encoding='utf-8') as banks:
    # print(text) 测试打印读取的数据
    # 待加密文本
        mystr = banks.read()
        message = base64.b64encode(mystr.encode('utf-8')).decode('ascii')

    sign_data = mrsa.signature(message)
    is_verify = mrsa.verify(data=message,signature=sign_data)
    print("签名：\n",sign_data)
    print("验签：\n",is_verify)
