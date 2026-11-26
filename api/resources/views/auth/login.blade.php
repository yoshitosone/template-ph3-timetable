<h1>ログイン</h1>

@if ($errors->any())
  <ul>
    @foreach ($errors->all() as $message)
      <li>{{ $message }}</li>
    @endforeach
  </ul>
@endif

<form method="POST" action="/login">
  @csrf
  <p><label>メールアドレス <input name="email" value="{{ old('email') }}"></label></p>
  <p><label>パスワード <input type="password" name="password"></label></p>
  <button>ログイン</button>
</form>

<p><a href="/register">ユーザー登録はこちら</a></p>
