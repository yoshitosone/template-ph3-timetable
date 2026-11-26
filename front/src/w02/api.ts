// W2 の POSSE課題：ここに型 LoadResult と、関数 isCourse・loadCourses を書いて export する。
// 下の3つは仮置き。W1 のあいだも pnpm typecheck のエラーを0件にしておくためだけにある（main.ts と W5 の check-api.ts がここから読み込むため）。
// W2 では、この3つを全部消してから書く。any は W1 で見たとおり型のチェックを止めるので、残したままにしない
export type LoadResult = any;
export function isCourse(_value: unknown): boolean {
  throw new Error("W2 の課題で isCourse を書く");
}
export async function loadCourses(_fetchFn: unknown): Promise<LoadResult> {
  throw new Error("W2 の課題で loadCourses を書く");
}
