// W3 からの画面の入口。pnpm dev で開くと、この App が表示される
import courses from "./data/courses.json";

export default function App() {
  // W3：ここで TimetableGrid に courses を渡す
  return (
    <main className="p-4">
      <p>授業のデータは {courses.length} 件です。ここに時間割の表を出します。</p>
    </main>
  );
}
