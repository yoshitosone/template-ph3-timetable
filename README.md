# POSSE大学 時間割アプリ（PH3）

PH3 で育てていく、架空の「POSSE大学」の時間割アプリです。PH2 で作った時間割アプリを、画面（React と TypeScript）と API（Laravel）に分けて作り直します。

`api/` には PH2 を最後まで終えた時点の Laravel が入っているので、PH2 の進み具合に関係なく全員が同じ地点から始められます。PH3 の16週で使うものは最初から全部入っているため、途中でこのテンプレートから更新を取り込む必要はありません。

## フォルダの構成

| フォルダ・ファイル | 中身 |
|---|---|
| `api/` | Laravel。PH2 の `src/` にあたる。W5 からここを API に作り変える |
| `front/` | React と TypeScript の画面。Vite で動かす。W1〜W4 の練習もここで行う |
| `docker/` | `api/` を動かすコンテナの設定（nginx・PHP・PostgreSQL の初期データ） |
| `compose.yaml` | `api/` を動かすコンテナの組み合わせ。リポジトリの一番上で `docker compose` を実行する |

`front/` はコンテナに入れず、自分の PC の Node.js（mise で入れる）で動かします。

## 準備

### api（Laravel）

リポジトリの一番上で実行します。

```bash
docker compose up -d --build
docker compose exec app composer install
docker compose exec app cp .env.example .env
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed --class=TagSeeder
docker compose exec app php artisan db:seed --class=AdminSeeder
docker compose exec db createdb -U posse -T timetable timetable_test
```

最後の行は、テスト用のデータベース `timetable_test` を開発用の `timetable` から複製するコマンドです。psql で `timetable` に接続していると複製できないので、先に閉じてから実行してください。

テスト用のデータベースを作り直したいとき（テストに `RefreshDatabase` を使って `relation "courses" does not exist` と出たときなど）は、テスト用の `timetable_test` だけを消してから、もう一度複製します。開発用の `timetable` は消えません。

```bash
docker compose exec db dropdb -U posse --if-exists timetable_test
docker compose exec db createdb -U posse -T timetable timetable_test
```

| URL | 中身 |
|---|---|
| http://localhost:8080 | 時間割アプリ（PH2 の完成時点の画面） |
| http://localhost:8025 | Mailpit（アプリが送ったメールを見る画面） |

テストは `docker compose exec app php artisan test` で実行できます。

### front（React と TypeScript）

`front/` に入って実行します。

```bash
cd front
mise install
pnpm install
```

`pnpm dev` で開発用のサーバーが立ち上がり、http://localhost:5173 で画面を開けます。`pnpm typecheck` で型のチェックだけを行います。

## PR を出すとき

AI エージェントに PR を作ってもらうと、作る前に「PR 前の説明チェック」が始まります。PR の差分と課題の仕様について3問聞かれるので、自分の言葉で答えてください。最後に出る記録を PR 本文に貼ります。

スキルの本体は次の2か所にあります。中身は同じです。片方を直したら、もう片方にも同じ変更を入れてください。

- `.agents/skills/posse-pr-check/SKILL.md`（Codex・GitHub Copilot が読む）
- `.claude/skills/posse-pr-check/SKILL.md`（Claude Code・GitHub Copilot が読む）
