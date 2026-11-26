<h1>ユーザー登録</h1>

@if ($errors->any())
  <ul>
    @foreach ($errors->all() as $message)
      <li>{{ $message }}</li>
    @endforeach
  </ul>
@endif

<form method="POST" action="/register">
  @csrf
  <p><label>名前 <input name="name" value="{{ old('name') }}"></label></p>
  <p><label>メールアドレス <input name="email" value="{{ old('email') }}"></label></p>
  <p><label>パスワード（8文字以上） <input type="password" name="password"></label></p>
  <p><label>パスワード（確認用） <input type="password" name="password_confirmation"></label></p>
  <button>登録</button>
</form>

<p><a href="/login">ログインはこちら</a></p>
