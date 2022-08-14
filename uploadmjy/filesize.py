# 判断文件的大小不能超过10M(利用弹窗选择文件)
import os
from winsound import MB_ICONASTERISK
import win32ui

print('请选择文件大小不超过10MB的文件')

dlg = win32ui.CreateFileDialog(1) #1表示打开文件对话框

dlg.SetOFNInitialDir('C:/')

dlg.DoModal()

filename = dlg.GetPathName()

# 获取获取文件夹的文件
fsize = os.path.getsize(filename) # 获取文件大小：以字节为单位
# 可换算成MB等单位
fsize = fsize / float(1024*1024)

while fsize > 10:
    print("选择的文件大于10MB请重新选择")
    dlg = win32ui.CreateFileDialog(1) #1表示打开文件对话框

    dlg.SetOFNInitialDir('C:/')

    dlg.DoModal()

    filename = dlg.GetPathName()

    
    fsize = os.path.getsize(filename) # 获取文件大小：以字节为单位
    # 可换算成MB等单位
    fsize = fsize / float(1024*1024)
else:
    print(fsize)
