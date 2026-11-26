<h1>授業を登録する</h1>

{{-- 入力チェックに落ちたときは、ここにメッセージが出る --}}
@if ($errors->any())
  <ul>
    @foreach ($errors->all() as $message)
      <li>{{ $message }}</li>
    @endforeach
  </ul>
@endif

<form method="POST" action="/entries">
  @csrf
  <p>
    <label>授業
      <select name="course_id">
        @foreach ($courses as $course)
          <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>{{ ['', '月', '火', '水', '木', '金'][$course->day] }}{{ $course->period }} {{ $course->name }}（{{ $course->teacher ?? '担当未定' }}）</option>
        @endforeach
      </select>
    </label>
  </p>
  <button>登録</button>
</form>
