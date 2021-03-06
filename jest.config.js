module.exports = {
  collectCoverageFrom: ['**/*.{js}'],
  setupFiles: ['<rootDir>/tests/Front/config/setup'],
  testURL: 'http://localhost',
  testMatch: ['**/tests/Front/**/*.spec.js'],
  testEnvironment: 'node',
  transform: {
    '^.+\\.js$': 'babel-jest',
    '.*\\.(vue)$': 'vue-jest'
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
