// timetable.ts の関数を呼んで、結果を出す（このファイルは書き換えない）
import { courses } from "./courses.ts";
import { formatCourse, coursesOnDay, sortCourses } from "./timetable.ts";

for (const day of [1, 2] as const) {
  for (const course of sortCourses(coursesOnDay(courses, day))) {
    console.log(formatCourse(course));
  }
  console.log("");
}

sortCourses(courses);
console.log(`並べ替えたあとの元の配列の最後：${courses[courses.length - 1].code}`);
