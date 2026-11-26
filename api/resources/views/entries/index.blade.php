<h1>{{ auth()->user()->name }}さんの時間割</h1>

<form method="POST" action="/logout">
  @csrf
  <button>ログアウト</button>
</form>

@if (count($announcements) > 0)
  <h2>新しいお知らせ</h2>
  <ul>
    @foreach ($announcements as $announcement)
      <li>{{ $announcement->created_at->format('m/d') }} <a href="/courses/{{ $announcement->course->id }}">{{ $announcement->course->name }}</a>：{{ $announcement->title }}</li>
    @endforeach
  </ul>
@endif

@if (count($entries) === 0)
  <p>まだ授業が登録されていません</p>
@else
  <table border="1">
    <tr><th>曜日</th><th>時限</th><th>授業名</th><th>担当教員</th><th>教室</th><th>受講者</th><th>状態</th><th></th></tr>
    @foreach ($entries as $entry)
      <tr>
        <td>{{ ['', '月', '火', '水', '木', '金'][$entry->course->day] }}</td><td>{{ $entry->course->period }}</td><td>{{ $entry->course->name }}</td><td>{{ $entry->course->teacher ?? '担当未定' }}</td><td>{{ $entry->course->room }}</td><td>{{ $entry->course->students_count }}／{{ $entry->course->capacity }}人</td><td>{{ $entry->statusLabel() }}</td>
        <td>
          @if ($entry->status === 'pending')
            <form method="POST" action="/entries/{{ $entry->id }}" style="display: inline">
              @csrf
              @method('DELETE')
              <button>取り消す</button>
            </form>
          @endif
        </td>
      </tr>
    @endforeach
  </table>
@endif

<p><a href="/entries/create">授業を申請する</a> <a href="/courses">授業一覧</a>
  @can('decide-enrollments')
    <a href="/admin/entries">履修の承認</a>
  @endcan
</p>
