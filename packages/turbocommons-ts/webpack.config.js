const path = require('node:path');

const sourceEntry = path.resolve(__dirname, 'src/main/ts/index.ts');

module.exports = [
  {
    name: 'es5',
    mode: 'production',
    entry: sourceEntry,
    devtool: 'source-map',
    module: {
      rules: [
        {
          test: /\.ts$/,
          exclude: /node_modules/,
          use: {
            loader: 'ts-loader',
            options: {
              compilerOptions: {
                target: 'ES2018',
                module: 'commonjs'
              }
            }
          }
        }
      ]
    },
    resolve: { extensions: ['.ts', '.js'] },
    output: {
      path: path.resolve(__dirname, 'dist/es5'),
      filename: 'turbocommons-es5.js',
      library: { name: 'org_turbocommons', type: 'var' }
    },
    optimization: { minimize: false }
  },
  {
    name: 'es6',
    mode: 'production',
    entry: sourceEntry,
    devtool: 'source-map',
    module: {
      rules: [
        {
          test: /\.ts$/,
          exclude: /node_modules/,
          use: {
            loader: 'ts-loader',
            options: {
              compilerOptions: {
                target: 'ES2015',
                module: 'commonjs'
              }
            }
          }
        }
      ]
    },
    resolve: { extensions: ['.ts', '.js'] },
    output: {
      path: path.resolve(__dirname, 'dist/es6'),
      filename: 'turbocommons-es6.js',
      library: { name: 'org_turbocommons', type: 'var' }
    },
    optimization: { minimize: false }
  }
];
