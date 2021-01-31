require('jsdom-global')()
const { config } = require('@vue/test-utils')

const globalValues = {
  debug: false,
  formerAppUrl: 'https://former-app'
}
for (const [key, value] of Object.entries(globalValues)) {
  global[key] = value
  config.mocks[key] = value
}
