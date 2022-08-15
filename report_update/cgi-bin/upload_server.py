### 上传文件
import cgi,os
from email import message
import cgitb

cgitb.enable()
form = cgi.FieldStorage() # 获取网页提交的数据
fileitem = form['uploadfile']

if fileitem.filename:
    fn = os.path.basename(fileitem.filename)
    ### 限制文件大小
    fsize = os.path.getsize(fn)
    fsize = fsize / float(1024*1024)# 单位换算为MB

    while fsize > 10:
      print("选择的文件大于10MB请重新选择")
      cgitb.enable()
      form = cgi.FieldStorage()
      fileitem = form['uploadfile']
      fsize = os.path.getsize(fn) # 获取文件大小：以字节为单位
      fsize = fsize / float(1024*1024)# 单位换算为MB
    
    ### 限制文件类型
    def typeList():
        return {
        "3c68313ee689abe68f8f": 'html',
        "504b03040a0000000000": 'xlsx',
        '504b0304140008080800': 'docx',
        "d0cf11e0a1b11ae10000": 'doc',
        '2d2d204d7953514c2064': 'sql',
        'ffd8ffe000104a464946': 'jpg',
        '89504e470d0a1a0a0000': 'png',
        '47494638396126026f01': 'gif',
        '3c21444f435459504520': 'html',
        '3c21646f637479706520': 'htm',
        '48544d4c207b0d0a0942': 'css',
        '2f2a21206a5175657279': 'js',
        '255044462d312e350d0a': 'pdf',
        }

    def bytes2hex(bytes):# 字节码转16进制字符串
      num = len(bytes)
      hexstr = u""
      for i in range(num):
        t = u"%x" % bytes[i]
        if len(t) % 2:
            hexstr += u"0"
        hexstr += t
      return hexstr.upper()
 
    def filetype(fileitem):# 获取文件类型
      binfile = open(fileitem, 'rb')  # 必需二制字读取
      bins = binfile.read(20)  # 提取20个字符
      binfile.close()  # 关闭文件流
      bins = bytes2hex(bins)  # 转码
      bins = bins.lower()  # 小写
      print(bins)
      tl = typeList()  # 文件类型
      ftype = 'unknown'
      for hcode in tl.keys():
        lens = len(hcode)  # 需要的长度
        if bins[0:lens] == hcode:
            ftype = tl[hcode]
            break
      if ftype == 'unknown':  # 全码未找到，优化处理，码表取5位验证
        bins = bins[0:5]
      for hcode in tl.keys():
        if len(hcode) > 5 and bins == hcode[0:5]:
            ftype = tl[hcode]
            break
      return ftype

    def filescanner(path):# 文件扫描，如果是目录，就将遍历文件，是文件就判断文件类型
      if type(path) != type('a'):  # 判断是否为字符串
        print('抱歉，你输入的不是一个字符串路径！')
      elif path.strip() == '':  # 将两头的空格移除
        print('输入的路径为空！')
      elif not os.path.exists(path):
        print('输入的路径不存在！')
      elif os.path.isfile(path):
        if path.rfind('.') > 0:
            print('文件名:', os.path.split(path)[1])
        else:
            print('文件名中没有找到格式')
        path = filetype(path)
        if (path == 'png' or path == 'jpg' or path == 'doc' or path == 'docx'):
            print("文件类型不符合请重新选择")
        else:
            print('解析文件判断格式：' + path)
      elif os.path.isdir(path):
        print('输入的路径指向的是目录，开始遍历文件')
        for p, d, fs in os.walk(path):
            print(os.path.split(p))
            for n in fs:
                n = n.split('.')
                print('\t' + n[0] + '\t' + n[1])

    if __name__ == '__main__':
      fn = os.path.basename(fileitem.filename)
      ftype = filetype(fn)
      
    open(os.getcwd()+'/files/'+fn,'wb').write(fileitem.file.read())
    message = '"' + fn + '" was uploaded successfully'

else:
    message = 'No file was uploaded'

### RSA加密
from Crypto import Random
from Crypto.PublicKey import RSA

# 获取一个伪随机数生成器
random_generator = Random.new().read
# 获取一个rsa算法对应的密钥对生成器实例
rsa = RSA.generate(1024, random_generator)

# 生成私钥并保存
private_pem = rsa.exportKey()
with open('rsa.key', 'wb') as f:
    f.write(private_pem)

# 生成公钥并保存
public_pem = rsa.publickey().exportKey()
with open('rsa.pub', 'wb') as f:
    f.write(public_pem)

# 输出
print('Content-type:text/html \n\n')
print('file_size = %fMB,file_type = %s,file %s' % (fsize,ftype,message))