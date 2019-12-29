from PIL import Image
import random
import torch.utils.data as TData
from pathlib import Path
import numpy as np
from noise.common_utils import *

class Dataset(TData.Dataset):
    def __init__(self, inputs, outputs):
        self.inputs = inputs
        self.outputs = outputs

    def __getitem__(self, index): # 返回的是tensor
        return self.inputs[index], self.outputs[index]

    def __len__(self):
        return len(self.inputs)

class DataUtil:
    def __init__(self):
        self.dim_div_by = 64
        self.max_width = (2160 // 6) * 6
        self.max_height = (1920 // 6) * 6
        self.watermark = Image.open('./dataset/watermark.png')
        if self.watermark.mode!='RGBA':
            alpha = Image.new('L', self.watermark.size, 255)
            self.watermark.putalpha(alpha)
        self.paste_mask = self.watermark.split()[3].point(lambda i: i * 0.06)

    def loader(self, batch_size):
        image_paths = list(Path("./dataset/train").glob("*.jpg"))
        x, y = [], []
        for image_path in image_paths:
            image = Image.open(str(image_path))
            y.append(self.tensor(image))
            x.append(self.tensor(self.combine(image)))
        dataset = Dataset(x, y)
        return TData.DataLoader(
            dataset=dataset,
            batch_size=batch_size,
            shuffle=True,
            num_workers=0,
        )

    def tensor(self, image):
        image = self.reshape(image)
        image = crop_image(image, self.dim_div_by)
        image = pil_to_np(image)
        image = np_to_torch(image)
        return image

    def target(self, image, h, w):
        image = torch_to_np(image)
        return image[:min(self.max_height, h), :min(self.max_width, w)]

    def reshape(self, image):
        canvas = np.zeros((self.max_height, self.max_width, 3), dtype=np.uint8)
        canvas[:min(image.size[1], self.max_height), :min(image.size[0], self.max_width)] = image
        return Image.fromarray(canvas)

    def combine(self, img):
        image = img.copy()
        random_X = random.randint(1 - self.watermark.size[0], image.size[0] - 1)
        random_Y = random.randint(1 - self.watermark.size[1] , image.size[1] - 1)
        image.paste(self.watermark, (random_X , random_Y ), mask=self.paste_mask)
        return image
