from flask_mail import Mail, Message
from config import email_config

app = None
email = None


def init(_app):
    global app, email
    app = _app
    app.config.update(email_config)
    email = Mail(app)


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
    if send(title, recipients, body):  # 如果发送成功
        results = '<h1>邮件已发送，注意查收！</h1>'
    else:  # 如果发送失败
        results = '发送失败，请检查操作！'
    return results