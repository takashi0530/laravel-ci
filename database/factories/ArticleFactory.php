<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Article;
use Faker\Generator as Faker;
use App\User;

// メモ fakerはテストデータを自動的に作成してくれる、メールアドレス、人名等

$factory->define(Article::class, function (Faker $faker) {
    return [

        // ※ id, created_at,updated_at は自動的に決まるので不要

        // 記事タイトル
        'title' => $faker->text(50),

        // 記事内容 （ラテン語でランダムな文字列500文字が生成）
        'body' => $faker->text(500),

        // 記事を投稿したユーザーID
        // 外部キー成約 参照先のモデルを生成するfactory関数を返す無名関数をセットする
        'user_id' => function() {
            return factory(User::class);
        }


    ];
});
