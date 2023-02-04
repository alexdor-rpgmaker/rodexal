module.exports = {
  collectCoverageFrom: ['**/*.{js}'],
  setupFiles: ['<rootDir>/tests/Front/config/setup'],
  testMatch: ['**/tests/Front/**/*.spec.js'],
  testEnvironment: 'node',
  testEnvironmentOptions: { url: 'http://localhost' },
  transform: {
    '^.+\\.js$': 'babel-jest',
    '.*\\.(vue)$': '@vue/vue2-jest'
  },
  transformIgnorePatterns: [
    '/node_modules/(?!transpile-me|transpile-me-too).+(js|jsx)$'
  ],
  moduleNameMapper: {
    '^vue$': 'vue/dist/vue.common.js', // common -> esm ?
    '^~/(.*)$': '<rootDir>/$1',
    '^@/(.*)$': '<rootDir>/$1'
  },
  moduleFileExtensions: ['js', 'vue']
}
