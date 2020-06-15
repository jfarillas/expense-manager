module.exports = {
  devServer: {
    proxy: process.env.VUE_APP_API_URL,
    watchOptions: {
      poll: true
    }
  },
  chainWebpack: config => {
    config.module
      .rule('images')
        .use('url-loader')
          .loader('url-loader')
          .tap(options => Object.assign(options, { limit: 10240 }))
  }
}