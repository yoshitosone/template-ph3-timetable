<h1>{{ $course->name }}</h1>

<p>{{ ['', '月', '火', '水', '木', '金'][$course->day] }}曜{{ $course->period }}限・{{ $course->teacher ?? '担当未定' }}・{{ $course->room }}</p>

<p>
  @foreach ($course->tags as $tag)
    <a href="/courses?tag={{ $tag->id }}">{{ $tag->name }}</a>
  @endforeach
</p>

<h2>お知らせ</h2>

@can('decide-enrollments')
  @error('title')<p>{{ $message }}</p>@enderror
  @error('body')<p>{{ $message }}</p>@enderror
  <form method="POST" action="/admin/courses/{{ $course->id }}/announcements">
    @csrf
    <p>タイトル <input name="title" value="{{ old('title') }}" maxlength="50" size="40"></p>
    <p><textarea name="body" rows="4" cols="50">{{ old('body') }}</textarea></p>
    <button>お知らせを出す</button>
  </form>
@endcan

@forelse ($announcements as $announcement)
  <div style="border-top: 1px solid #ccc">
    <p><strong>{{ $announcement->title }}</strong>（{{ $announcement->created_at->format('Y-m-d H:i') }}）</p>
    <p style="white-space: pre-wrap">{{ $announcement->body }}</p>
  </div>
@empty
  <p>お知らせはまだありません</p>
@endforelse

<h2>受講者（{{ count($course->students) }}／{{ $course->capacity }}人）</h2>

@if (count($course->students) === 0)
  <p>まだ登録している学生はいません</p>
@else
  <ul>
    @foreach ($course->students as $student)
      <li>{{ $student->name }}</li>
    @endforeach
  </ul>
@endif

<h2>レビューを書く</h2>

@if ($errors->any())
  <ul>
    @foreach ($errors->all() as $message)
      <li>{{ $message }}</li>
    @endforeach
  </ul>
@endif

<form method="POST" action="/courses/{{ $course->id }}/reviews">
  @csrf
  <p><textarea name="body" rows="4" cols="50">{{ old('body') }}</textarea></p>
  <button>投稿する</button>
</form>

<h2>レビュー（{{ count($reviews) }}件）</h2>

@forelse ($reviews as $review)
  <div style="border-top: 1px solid #ccc">
    <p>{{ $review->user->name }}（{{ $review->created_at->format('Y-m-d H:i') }}）</p>
    {{-- {{ }} は < や > を文字に変えて出す。改行は CSS でそのまま見せる --}}
    <p style="white-space: pre-wrap">{{ $review->body }}</p>
    @can('delete', $review)
      <form method="POST" action="/reviews/{{ $review->id }}">
        @csrf
        @method('DELETE')
        <button>削除</button>
      </form>
    @endcan
  </div>
@empty
  <p>まだレビューはありません</p>
@endforelse

<p><a href="/courses">授業一覧に戻る</a></p>
