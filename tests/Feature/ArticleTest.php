<?php

namespace Tests\Feature;

use App\Article;

use App\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;


    // 引数がNULLのケースのテスト
    public function testIsLikedByNull()
    {
        // ここでは、factory(Article::class)->create()とすることで、ファクトリによって生成されたArticleモデルがデータベースに保存される
        // createメソッドは保存したモデルのインスタンスを返すので、これが変数$articleに代入される
        $article = factory(Article::class)->create();

        $result = $article->isLikedBy(null);

        // ここでの$thisはTestCaseクラスを継承したArticleTestクラスを指す
        // TestCaseクラスはassertFalseメソッドを持つ。assertFalseメソッドは引数がfalseかどうかをテストするメソッド。
        // https://phpunit.readthedocs.io/ja/latest/assertions.html#assertfalse
        $this->assertFalse($result);
    }

    public function testIsLikedByTheUser()
    {
        // ファクトリで作成したArticleモデルをデータベースに保存するとともに、インスタンスを変数$articleに代入
        $article = factory(Article::class)->create();
        // ファクトリで作成したUserモデルをデータベースに保存するとともに、インスタンスを変数＄userに代入している
        $user    = factory(User::class)->create();

        // 記事にいいねをする
        $article->likes()->attach($user);

        // Articleクラスのインスタンスが代入された$articleでisLikedByメソッドを使用する
        // $userはこの$articleをいいねしたユーザーであるので、$resultにはtrueが代入されるはず
        $result = $article->isLikedBy($user);

        // $resultがtrueかどうかをテストする
        $this->assertTrue($result);
    }

    public function testIsLikedByAnother()
    {
        // ファクトリで生成した各モデルをデータベースに保存し、インスタンスを変数に代入している
        $article = factory(Article::class)->create();
        $user    = factory(User::class)->create();
        $another = factory(User::class)->create();

        // 自分ではない他人が記事にいいねをする
        // 変数$anotherに代入されたUserモデルのインスタンスが、$articleをいいねしている状態を作り出している
        $article->likes()->attach($another);

        $result = $article->isLikedBy($user);

        // $resultに正しくfalseが入っているか？のテストを行う
        $this->assertFalse($result);
    }

    public function testAa()
    {
        $a = false;

        $this->assertFalse($a);
    }
}

