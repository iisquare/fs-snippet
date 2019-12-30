import argparse
import numpy as np
import cv2
from noise.data_util import DataUtil
from PIL import Image

def get_args():
    parser = argparse.ArgumentParser(description="Test combine watermark", formatter_class=argparse.ArgumentDefaultsHelpFormatter)
    parser.add_argument("--filename", type=str, required=True, help="test image file path")
    args = parser.parse_args()
    return args

def main():
    args = get_args()
    filename = args.filename

    data_util = DataUtil()
    image = cv2.imread(filename)
    h, w, _ = image.shape
    image = image[:(h // 16) * 16, :(w // 16) * 16]  # for stride (maximum 16)
    h, w, _ = image.shape
    out_image = np.zeros((h, w * 2, 3), dtype=np.uint8)
    image = Image.fromarray(image)
    combine = data_util.combine(image)
    out_image[:, :w] = image
    out_image[:, w:w * 2] = combine

    cv2.namedWindow('result', cv2.WINDOW_NORMAL)
    cv2.imshow("result", out_image)
    key = cv2.waitKey(-1)
    # "q": quit
    if key == 113:
        return 0


if __name__ == '__main__':
    main()