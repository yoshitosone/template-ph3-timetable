<h1>授業一覧</h1>

<form method="GET" action="/courses">
  <input name="q" value="{{ $q }}" placeholder="授業名・担当教員">
  @if ($tag)
    <input type="hidden" name="tag" value="{{ $tag->id }}">
  @endif
  <button>検索</button>
</form>

@if ($tag)
  <p>タグ「{{ $tag->name }}」で絞り込み中 <a href="/courses">絞り込みを外す</a></p>
@endif

@if (count($courses) === 0)
  <p>当てはまる授業はありません</p>
@else
  <table border="1">
    <tr><th>曜日</th><th>時限</th><th>科目コード</th><th>授業名</th><th>担当教員</th><th>教室</th><th>受講者／定員</th><th>タグ</th><th>気になる</th><th></th></tr>
    @foreach ($courses as $course)
      <tr>
        <td>{{ ['', '月', '火', '水', '木', '金'][$course->day] }}</td><td>{{ $course->period }}</td><td>{{ $course->code }}</td><td><a href="/courses/{{ $course->id }}">{{ $course->name }}</a></td><td>{{ $course->teacher ?? '担当未定' }}</td><td>{{ $course->room }}</td><td>{{ $course->students_count }}／{{ $course->capacity }}人</td>
        <td>
          @foreach ($course->tags as $courseTag)
            <a href="/courses?tag={{ $courseTag->id }}">{{ $courseTag->name }}</a>
          @endforeach
        </td>
        <td style="white-space: nowrap">
          <form method="POST" action="/courses/{{ $course->id }}/favorite" style="display: inline">
            @csrf
            @if ($myFavoriteIds->contains($course->id))
              @method('DELETE')
              <button title="気になるから外す">★</button>
            @else
              <button title="気になるに入れる">☆</button>
            @endif
          </form>
          {{ $course->favorited_by_count }}人
        </td>
        <td>
          @if ($myStatuses->has($course->id))
            {{ ['pending' => '申請中', 'approved' => '承認', 'rejected' => '却下'][$myStatuses[$course->id]] }}
          @else
            <form method="POST" action="/entries" style="display: inline">
              @csrf
              <input type="hidden" name="course_id" value="{{ $course->id }}">
              <button>申請</button>
            </form>
          @endif
        </td>
      </tr>
    @endforeach
  </table>
@endif

<p><a href="/entries">自分の時間割に戻る</a></p>
