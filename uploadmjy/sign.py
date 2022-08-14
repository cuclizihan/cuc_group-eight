from Crypto.Hash import SHA
from Crypto.Signature import PKCS1_v1_5 as Signature_PKCS1_v1_5
from Crypto.PublicKey import RSA
import base64
import win32ui

dlg = win32ui.CreateFileDialog(1) #1表示打开文件对话框

dlg.SetOFNInitialDir('C:/')

dlg.DoModal()

signpath = dlg.GetPathName()

# 一次性读取文本内容
with open(signpath, 'r', encoding='utf-8') as banks:
    # print(text) 测试打印读取的数据
    # 待加密文本
    mystr = banks.read()
    message = base64.b64encode(mystr.encode('utf-8')).decode('ascii')


# 数据签名
with open('rsa.key', 'r') as f:
    private_key = f.read()
    rsa_key_obj = RSA.importKey(private_key)
    signer = Signature_PKCS1_v1_5.new(rsa_key_obj)
    digest = SHA.new()
    digest.update(message)
    signature = base64.b64encode(signer.sign(digest))
    print('signature text: ', signature)

# 验证签名
with open('rsa.pub', 'r') as f:
    public_key = f.read()
    rsa_key_obj = RSA.importKey(public_key)
    signer = Signature_PKCS1_v1_5.new(rsa_key_obj)
    digest = SHA.new(message)
    is_ok = signer.verify(digest, base64.b64decode(signature))
    print('is ok: ', is_ok)
