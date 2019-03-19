module.exports = {
  assetsDir: './',
  publicPath: './',
  productionSourceMap: false,
  devServer: {
    proxy: {
      '/dev': {
        target: process.env.BACKEND_URL
      }
    }
  }
}
