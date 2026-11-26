// 授業の API の代わりをする、偽の fetch（このファイルは書き換えない）
// どれも本物の fetch と同じく、URL を受け取って Response を返す Promise を返す。
// W5 で Laravel の API を作り、W6 で本物の fetch に差し替える
import courses from "../data/courses.json" with { type: "json" };

// fetch と同じ形の関数の型。loadCourses の引数に使う
export type FetchFn = (url: string) => Promise<Response>;

// 通信にかかる時間の代わりに、少し待つ
function wait(ms: number): Promise<void> {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

function json(body: unknown, status: number): Response {
  return new Response(JSON.stringify(body), { status, headers: { "Content-Type": "application/json" } });
}

// 正常な30件を返す
export const fetchOk: FetchFn = async () => {
  await wait(100);
  return json(courses, 200);
};

// 30件のうち id 3 の period が文字の "3" になっている（API 側の入力ミス）
export const fetchBroken: FetchFn = async () => {
  await wait(100);
  return json(courses.map((course) => (course.id === 3 ? { ...course, period: "3" } : course)), 200);
};

// サーバーの中でエラーが起き、500 を返す
export const fetchServerError: FetchFn = async () => {
  await wait(100);
  return json({ message: "Server Error" }, 500);
};

// サーバーまで届かない（本物の fetch と同じく TypeError を投げる）
export const fetchNetworkError: FetchFn = async () => {
  await wait(100);
  throw new TypeError("fetch failed");
};
