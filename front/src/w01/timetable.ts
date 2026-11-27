// W1 の POSSE課題：ここに型 Day・Course と、関数 formatCourse・coursesOnDay・sortCourses を書く
export type Day = 1 | 2 | 3 | 4 | 5;
export type Course = {
    id: number;
    code: string;
    name: string;
    teacher: string | null;
    day: Day;
    period: number;
    room: string;
    credits: number;
    required: boolean;
};

export function formatCourse(c: Course): string {
    return `${["", "月", "火", "水", "木", "金"][c.day]}${c.period} ${c.name}（${c.room}／${c.teacher ?? "担当未定"}）`;
}

export function coursesOnDay(courses: Course[], day: Day): Course[] {
    return courses.filter(course => course.day === day);
}

export function sortCourses(courses: Course[]): Course[] {
    return [...courses].sort((a, b) => (a.day - b.day) || (a.period - b.period));
}

