import unittest
from spam import service as spam


class Tester(unittest.TestCase):

    def test_predict(self):
        result = spam.predict([
            ['你好', '世界'],
            ['代办', '假', '发票', '加', '微信', '{联系方式}'],
            ['测试'],
            ['青岛', '市', '办', '假', '存', '单'],
            ['征', '信', '问题', '资', '质', '问题', '流水', '收入', '证明', '房子', '房', '龄', '等', '问题', '都', '有可能', '具体', '看', '是', '哪', '方面', '原因']
        ])
        print(result)


if __name__ == '__main__':
    unittest.main()
