import argparse
import torch
from noise.skip_net import skip
from noise.data_util import DataUtil

def get_args():
    parser = argparse.ArgumentParser(description="Test train watermark", formatter_class=argparse.ArgumentDefaultsHelpFormatter)
    parser.add_argument("--batch_size", type=int, default=16, help="batch size")
    parser.add_argument("--nb_epochs", type=int, default=60, help="number of epochs")
    parser.add_argument("--lr", type=float, default=0.01, help="learning rate")
    parser.add_argument("--steps", type=int, default=1000, help="steps per epoch")
    args = parser.parse_args()
    return args

def main():
    args = get_args()
    pad = 'reflection' # 'zero'

    data_util = DataUtil()
    loader = data_util.loader(args.batch_size)
    net = skip(32, data_util.max_width, 
               num_channels_down = [128] * 5,
               num_channels_up =   [128] * 5,
               num_channels_skip =    [128] * 5,  
               filter_size_up = 3, filter_size_down = 3, 
               upsample_mode='nearest', filter_skip_size=1,
               need_sigmoid=True, need_bias=True, pad=pad, act_fun='LeakyReLU')
    loss_func = torch.nn.MSELoss()
    optimizer = torch.optim.Adam(net.parameters(), lr=args.lr, betas=(0.9, 0.99))

    for epoch in range(args.nb_epochs):
        for step, (batch_x, batch_y) in enumerate(loader):
            print('Epoch: ', epoch, '| Step: ', step, '| batch x: ', batch_x.numpy().shape, '| batch y: ', batch_y.numpy().shape)
            prediction = net(batch_x)
            loss = loss_func(prediction, batch_y)
            optimizer.zero_grad()
            loss.backward()
            optimizer.step()
        torch.save(net, './data/noise_net_epoch_' + str(epoch) + '.pkl')
    torch.save(net, './data/noise_net.pkl')

if __name__ == '__main__':
    main()