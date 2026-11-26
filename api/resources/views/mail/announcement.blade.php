<p>{{ $student->name }} さん</p>

<p>{{ $announcement->course->name }}（{{ $announcement->course->code }}）について、教務課からお知らせがあります。</p>

<p>{{ $announcement->title }}</p>
<p style="white-space: pre-wrap">{{ $announcement->body }}</p>

<p><a href="{{ url('/courses/' . $announcement->course_id) }}">授業のページを開く</a></p>

<p>POSSE大学 教務課</p>
