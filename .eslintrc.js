module.exports = {
  env: {
    browser: true,
    es6: true,
    "jest/globals": true,
  },
  extends: [
    'plugin:vue/essential',
    'standard'
  ],
  globals: {
    Atomics: 'readonly',
    SharedArrayBuffer: 'readonly'
  },
  parserOptions: {
    ecmaVersion: 2018,
    sourceType: 'module'
  },
  plugins: [
    'vue',
    'jest'
  ],
  rules: {
  }
}
