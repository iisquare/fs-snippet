import argparse
import torch
from noise.data_util import DataUtil
from PIL import Image
import numpy as np
import os
import cv2

def get_args():
    parser = argparse.ArgumentParser(description="Test train watermark", formatter_class=argparse.ArgumentDefaultsHelpFormatter)
    parser.add_argument("--filename", type=str, required=True, help="test image file path")
    parser.add_argument("--with_combine", type=bool, default=True, help="combine image with watermark")
    args = parser.parse_args()
    return args

def main():
    args = get_args()
    pkl = './data/noise_net.pkl'
    data_util = DataUtil()
    image = Image.open(args.filename)
    h, w = image.size[1], image.size[0]
    noise = data_util.tensor(image)
    if os.path.exists(pkl):
        net = torch.load()
        target = net(noise)
    else:
        target = noise
    out_image = np.zeros((h, w * 3, 3), dtype=np.uint8)
    out_image[:, :w] = image
    out_image[:, w:w * 2] = data_util.target(noise, h, w)
    out_image[:, w * 2:] = data_util.target(target, h, w)

    cv2.namedWindow('result', cv2.WINDOW_NORMAL)
    cv2.imshow("result", out_image)
    key = cv2.waitKey(-1)
    # "q": quit
    if key == 113:
        return 0


if __name__ == '__main__':
    main()