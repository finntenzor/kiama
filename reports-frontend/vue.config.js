module.exports = {
  assetsDir: '',
  publicPath: './{$BASE}',
  productionSourceMap: false,
  devServer: {
    proxy: {
      '/dev': {
        target: process.env.BACKEND_URL
      }
    }
  }
}
