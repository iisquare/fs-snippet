import { defineConfig } from 'vite'
import { obfuscator } from 'rollup-obfuscator'

export default defineConfig(async ({ command, mode }) => {
  return {
    plugins: [
      obfuscator({
        sourceMap: true
      })
    ],
    build: {
      minify: true,
      sourcemap: true,
      emptyOutDir: true,
      lib: {
        entry: './source/test.js',
        name: 'TY',
        formats: ['es'],
        fileName: format => `test.min.js`
      }
    }
  }
})
