module.exports = api => {
  api.cache(true)

  const presets = [
    [
      '@babel/preset-env',
      {
        useBuiltIns: 'usage',
        corejs: '3.2.1',
        targets: {
          browsers: '> 5%'
        }
      }
    ]
  ]
  const plugins = []

  return {
    presets,
    plugins
  }
}
