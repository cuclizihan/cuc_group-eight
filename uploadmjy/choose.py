# 弹窗选择系统文件
import win32ui

dlg = win32ui.CreateFileDialog(1) #1表示打开文件对话框

dlg.SetOFNInitialDir('C:/')

dlg.DoModal()

filename = dlg.GetPathName()

print("%s" % filename)
