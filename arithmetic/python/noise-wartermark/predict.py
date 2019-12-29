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
    parser.add_argument("--pkl", type=str, default='./data/noise_net.pkl', help="net model path")
    args = parser.parse_args()
    return args

def main():
    args = get_args()
    data_util = DataUtil()
    image = Image.open(args.filename)
    h, w = image.size[1], image.size[0]
    noise = data_util.tensor(data_util.combine(image), True)
    if os.path.exists(args.pkl):
        net = torch.load(args.pkl)
        target = net(noise)
    else:
        target = noise
    noise = data_util.target(noise)
    target = data_util.target(target)

    out_image = np.zeros((h, w * 3, 3), dtype=np.uint8)
    out_image[:, :w] = image
    out_image[:noise.shape[0], w:w + noise.shape[1]] = noise
    out_image[:target.shape[0], w * 2:w * 2 + target.shape[1]] = target
    
    cv2.namedWindow('result', cv2.WINDOW_NORMAL)
    cv2.imshow("result", cv2.cvtColor(out_image, cv2.COLOR_BGR2RGB))
    key = cv2.waitKey(-1)
    # "q": quit
    if key == 113:
        return 0


if __name__ == '__main__':
    main()