// 4つの偽の fetch で loadCourses を呼び、結果を出す（このファイルは書き換えない）
import { formatCourse, type Course } from "../w01/timetable.ts";
import { loadCourses, type LoadResult } from "./api.ts";
import { fetchOk, fetchBroken, fetchServerError, fetchNetworkError } from "./fake-api.ts";

function show(label: string, result: LoadResult): void {
  console.log(`[${label}]`);
  if (result.ok) {
    console.log(`${result.courses.length}件の授業を読み込みました`);
    // 月曜の1限から5限までを、時限ごとに探して並べる
    for (const period of [1, 2, 3, 4, 5]) {
      const found = result.courses.filter((course: Course) => course.day === 1 && course.period === period);
      if (found.length === 0) console.log(`月${period} （授業なし）`);
      for (const course of found) console.log(formatCourse(course));
    }
  } else if (result.reason === "invalid") {
    console.log(`授業のデータの形が合いませんでした（id: ${result.ids.join(", ")}）`);
  } else if (result.reason === "server") {
    console.log("授業を読み込めませんでした（サーバーのエラー）");
  } else {
    console.log("通信できませんでした。ネットワークを確かめてください");
  }
  console.log("");
}

show("正常なデータ", await loadCourses(fetchOk));
show("period が文字の授業を含むデータ", await loadCourses(fetchBroken));
show("サーバーのエラー", await loadCourses(fetchServerError));
show("通信の失敗", await loadCourses(fetchNetworkError));
