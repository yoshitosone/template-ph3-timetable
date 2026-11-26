import { defineConfig, mergeConfig } from 'vitest/config'
import viteConfig from './vite.config.ts'

// W11：pnpm test（vitest run）の設定。画面の設定（vite.config.ts）に、テストのときだけの設定を足す
export default mergeConfig(
  viteConfig,
  defineConfig({
    test: {
      // ブラウザの代わりに jsdom（Node.js の中で動く偽のブラウザ）で部品を描く
      environment: 'jsdom',
      // テストのファイルより先に読む。toBeInTheDocument などの確かめ方を使えるようにする
      setupFiles: ['./src/test/setup.ts'],
    },
  }),
)
