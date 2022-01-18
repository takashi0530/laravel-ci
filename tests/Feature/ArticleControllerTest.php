<?php

namespace Tests\Feature;

// Userモデルを使用する
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{

    // メソッド名をtest始まりにしたくない場合、以下のようにdocumentに @test を記述する
    /**
     * @test
     */
    // public function initialBalanceShouldBe0()
    // {
    //     $this->assertSame(0, $this->ba->getBalance());
    // }




    // TestCaseクラスを継承したクラスでRefreshDatabaseトレイトを使用すると、データベースをリセットする
    use RefreshDatabase;

    // PHPUnitのルールとしてメソッド名の最初にtest~とつける必要がある
    public function testIndex()
    {
        $response = $this->get(route('articles.index'));

        $response->assertStatus(400))
            ->assertViewIs('articles.index');
    }

    // 未ログイン時にログイン画面に遷移されるかどうかのテスト (guest:未ログインユーザーのこと)
    // 以下メソッドのテスト方法   docker-compose exec app vendor/bin/phpunit --filter=guest
    public function testGuestCreate()
    {
        $response = $this->get(route('articles.create'));

        // assertRedirectメソッド ： 引数として渡したURLにリダイレクトされたかどうかをてテストする
        // route('login') ： ログイン画面のURLを返す
        $response->assertRedirect(route('login'));
    }


    // Userモデルのファクトリは以下にある
    // └──laravel-ci
    //     └── database
    //         └── factories
    //             └── Userfactory.php
    // 以下のテスト実行    docker-compose exec app vendor/bin/phpunit --filter=auth
    // テストの書き方のパターン Arrange-Act-Assert（AAA）に従ってテストを実行している（準備・実行・検証）
    public function testAuthCreate()
    {
        // 【テストに必要なUserモデルを準備】
        // factory テストに必要なモデルのインスタンスをファクトリというものを利用して生成できる
        // factory(User::class)->create()  でファクトリによって生成されたUserモデルがデータベースに保存される
        $user = factory(User::class)->create();

        // 【ログインして記事投稿画面にアクセスすることを実行】
        // actingAsメソッド  引数として渡したUserモデルにてログインした状態を作り出す
        // その上でget(route('articles.create')) を行うことで、ログイン済みの状態で記事投稿画面へアクセスしたことになり、そのレスポンスは$responseに代入される
        $response = $this->actingAs($user)
            ->get(route('articles.create'));

        // 【レスポンスを検証する】
        // リダイレクト等はされないため、HTTPステータスコードとしては200が返ってくるはずなので、assertStatus(200)でこれをテストする   ※リダイレクトの場合は302が返る
        // assertVIewIs で記事投稿画面のviewが使用されているかをテストする
        $response->assertStatus(200)
            ->assertViewIs('articles.create');
    }


}
