import { defineConfig, globalIgnores } from 'eslint/config'

import globals from 'globals'
import js from '@eslint/js'
import vuePlugin from 'eslint-plugin-vue'
import vitestPlugin from 'eslint-plugin-vitest'

import { FlatCompat } from '@eslint/eslintrc'

import { fileURLToPath } from 'node:url'
import { dirname } from 'node:path'

const __dirname = dirname(fileURLToPath(import.meta.url))

const compat = new FlatCompat({
  baseDirectory: __dirname,
  recommendedConfig: js.configs.recommended,
  allConfig: js.configs.all
})

export default defineConfig([
  globalIgnores(['public', 'vendor']),
  js.configs.recommended,
  ...compat.extends(
    'plugin:vue/base',
    'plugin:vue/recommended'
  ),
  {
    files: ['resources/js/**/*.js', 'resources/js/**/*.vue'],
    languageOptions: {
      ecmaVersion: 2023,
      sourceType: 'module',
      globals: {
        ...globals.node
      }
    },
    plugins: {
      vue: vuePlugin
    },
    rules: {
      'no-new': 'off',
      'no-undef': 'off',
      'space-before-function-paren': [
        'error',
        { anonymous: 'always', named: 'never', asyncArrow: 'always' }
      ],

      'vue/multi-word-component-names': 'off'
      // semi: ['error', 'never'],
      //
      // 'no-unused-vars': [
      //   'error',
      //   {
      //     argsIgnorePattern: '^_',
      //     caughtErrorsIgnorePattern: '^_'
      //   }
      // ],
      //
      // 'vue/html-self-closing': 0,
      //
      // 'vue/max-attributes-per-line': [
      //   'error',
      //   {
      //     singleline: 20,
      //     multiline: 1
      //   }
      // ]
    }
  },
  {
    files: ['tests/Front/**/*.spec.js'],
    plugins: { vitest: vitestPlugin },
    rules: {
      ...vitestPlugin.configs.recommended.rules
    },
    languageOptions: {
      globals: {
        ...vitestPlugin.environments.env.globals
      }
    }
  }
])
