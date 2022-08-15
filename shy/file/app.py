# 邮件功能
from flask import Flask, request
import email
import file

app = Flask(__name__)


@app.route('/test_mail')
def test_mail():
    email = request.args.get('email')
    return email.test_mail(email)


@app.route('/upload_file', methods=['POST'])
def upload_file():
    # f = 上传的文件
    # file.upload_file(f)
    pass


@app.route('/file_list')
def file_list():
    file.file_list()
    pass


def main():
    email.init(app)
    app.run(host='zlbweb.cn', port=443, ssl_context=('cert.pem', 'key.pem'))


if __name__ == "__main__":
    main()