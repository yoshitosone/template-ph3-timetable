// W11：どのテストのファイルより先に読まれる（vitest.config.ts の setupFiles）
// expect(...).toBeInTheDocument() など、画面の要素を確かめる書き方を足す
import "@testing-library/jest-dom/vitest";
import { cleanup } from "@testing-library/react";
import { afterEach, vi } from "vitest";

// テストごとに、描いた画面と、偽物に差し替えた fetch などを元に戻す
afterEach(() => {
  cleanup();
  vi.unstubAllGlobals();
});
