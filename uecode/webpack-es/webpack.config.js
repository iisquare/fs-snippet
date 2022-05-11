import path from "path";

import { fileURLToPath } from "url";

const __dirname = path.dirname(fileURLToPath(import.meta.url));

export default  {
    entry: './source/test.js',
    output: {
        path: path.resolve(__dirname, './dist'),
        filename: 'test.min.js',
        library: {
          type: 'module'
        }
    },
    experiments: {
        outputModule: true,
    },
    plugins: [
       
    ],
    module: {
         // https://webpack.js.org/loaders/babel-loader/#root
        rules: [
            {
                test: /\.m?js$/,
                loader: 'babel-loader',
                exclude: /node_modules/,
            }
        ],
    },
    devtool: 'source-map'
}
