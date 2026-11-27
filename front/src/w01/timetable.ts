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

const DAYS = ["", "月", "火", "水", "木", "金"];

export function formatCourse(c: Course): string {
    return `${DAYS[c.day]}${c.period} ${c.name}（${c.room}／${c.teacher ?? "担当未定"}）`;
}

export function coursesOnDay(courses: Course[], day: Day): Course[] {
    return courses.filter(course => course.day === day);
}

export function sortCourses(courses: Course[]): Course[] {
    return [...courses].sort((a, b) => {
        if (a.day !== b.day) {
            return a.day - b.day;
        }
        return a.period - b.period;
    });
}

