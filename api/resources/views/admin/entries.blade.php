<h1>履修の承認</h1>

@if ($errors->any())
  <ul>
    @foreach ($errors->all() as $message)
      <li>{{ $message }}</li>
    @endforeach
  </ul>
@endif

<h2>申請中（{{ count($pending) }}件）</h2>

@if (count($pending) === 0)
  <p>申請中の履修はありません</p>
@else
  <table border="1">
    <tr><th>申請日時</th><th>学生</th><th>授業名</th><th>受講者／定員</th><th></th></tr>
    @foreach ($pending as $entry)
      <tr>
        <td>{{ $entry->created_at->format('Y-m-d H:i') }}</td><td>{{ $entry->user->name }}</td><td>{{ $entry->course->name }}</td><td>{{ $entry->course->students_count }}／{{ $entry->course->capacity }}人</td>
        <td style="white-space: nowrap">
          <form method="POST" action="/admin/entries/{{ $entry->id }}/approve" style="display: inline">
            @csrf
            <button>承認</button>
          </form>
          <form method="POST" action="/admin/entries/{{ $entry->id }}/reject" style="display: inline">
            @csrf
            <button>却下</button>
          </form>
        </td>
      </tr>
    @endforeach
  </table>
@endif

<h2>最近決まった申請</h2>

@if (count($decided) === 0)
  <p>まだありません</p>
@else
  <table border="1">
    <tr><th>決まった日時</th><th>学生</th><th>授業名</th><th>状態</th></tr>
    @foreach ($decided as $entry)
      <tr>
        <td>{{ $entry->decided_at?->format('Y-m-d H:i') }}</td><td>{{ $entry->user->name }}</td><td>{{ $entry->course->name }}</td><td>{{ $entry->statusLabel() }}</td>
      </tr>
    @endforeach
  </table>
@endif

<p><a href="/entries">自分の時間割に戻る</a></p>
