# Laravel `sync()` のdetachデバッグラボ

`belongsToMany` 関係でロールを一つ追加するつもりで `sync([$roleId])` を呼び、渡していない既存ロールまで外してしまう挙動を再現する。

| 項目 | 内容 |
|---|---|
| Laravel | 13.25.0 |
| PHP | 8.3.6 |
| DB | SQLite（テスト時はインメモリ） |
| 対象テスト | `php artisan test tests/Feature/SyncDetachTest.php` |

初期状態では、追加操作が既存ロールを保持するべきテストが失敗する。修正後は `syncWithoutDetaching()` を使い、追加だけという業務契約を回帰テストで固定する。
