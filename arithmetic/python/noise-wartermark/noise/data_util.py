from PIL import Image
import random

class DataUtil:
    def __init__(self):
        self.watermark = Image.open('./dataset/watermark.png')
        if self.watermark.mode!='RGBA':
            alpha = Image.new('L', self.watermark.size, 255)
            self.watermark.putalpha(alpha)
        self.paste_mask = self.watermark.split()[3].point(lambda i: i * 0.04)

    def combine(self, img):
        img = img.copy()
        image = Image.fromarray(img)

        random_X = random.randint(1 - self.watermark.size[0], image.size[0] - 1)
        random_Y = random.randint(1 - self.watermark.size[1] , image.size[1] - 1)
        
        image.paste(self.watermark, (random_X , random_Y ), mask=self.paste_mask)
        return image
