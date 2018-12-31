<?php


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

use App\User;
use App\Profile;
use App\Post;
use App\Category;
use App\Role;
use App\Portfolio;
use App\Tag;


/**
 * untuk membuat atau menambah user
 */
Route::get('/create_user', function () {
    $user = User::create([
        'name' => 'Putri',
        'email' => 'putriputri@gmail.com',
        'password' => bcrypt('password')
    ]);
    return $user;
});

/**
 * untuk membuat/ menambah profile 
 */
Route::get('/create_profile', function () {
    // $profile = Profile::create([
    //     'user_id' => 1,
    //     'phone' => '12345678',
    //     'address' => 'Jl.Pasar Baru No.42'
    // ]);

    $user = User::find(10);

    $user->profile()->create([
        'phone' => '333333',
        'address' => 'Olo Padang'
    ]);
    return $user;
});

/**
 * membuat profile untuk user dengan id 10
 */
Route::get('/create_user_profile', function () {
    $user = User::find(10);

    $profile = new Profile([
        'phone' => '08129888',
        'address' => 'Jl. Raya No.123'
    ]);

    $user->profile()->save($profile);
    return $user;

});

/**
 * untuk membaca data dari tabel user
 */
Route::get('/read_user', function () {
    $user = User::find(1);

    // return $user;  // untuk memanggil seluruh data pada id 1
    
    // return $user->profile->address;    // untuk memanggil data address pada id 1

    // untuk memanggil data menggunakan array
    $data =  [
        'name' => $user->name,
        'phone' => $user->profile->phone,
        'address' => $user->profile->address
    ];
    return $data;

});

/**
 * untuk mencari user yang memiliki data profile yang dimaksud
 */
Route::get('/read_profile', function () {
    $profile = Profile::where('phone','08129888')->first();  

    // return $profile->user->name;    // untuk menampilkan data nama user dari pemanggilan data phone profile

    // untuk menampilkan beberapa data menggunakan array 
    $data = [
        'name' =>$profile->user->name,
        'email' =>$profile->user->email,
        'phone' =>$profile->phone,
        'address' =>$profile->address
    ];
    return $data;

});

/**
 * untuk mengubah data profile yang di buat
 */
Route::get('/update_profile', function () {
    $user = User::find(10);

    $data = [
        'phone' =>'00000000',
        'address' =>'Painan'
    ];

    $user->profile()->update($data);

    return $user;
});

// menghapus data yang berelesi dengan suatu user (menghapus data profile)
Route::get('/delete_profile', function () {
    $user = User::find(10);
    $user->profile()->delete();

    return $user;
});

/**
 * me-create user dan profile dengan satu url saja
 */
Route::get('/create_post', function () {
    // $user = User::create([
    //     'name'=> 'Sansan',
    //     'email' => 'sansan@gmail.com',
    //     'password' => bcrypt('password')
    // // ]);

    $user = User::findOrFail(2);

    /**
     * membuat data post dari id user yang dibuat diatas
     */
    $user->posts()->create([
        'title'=>'Isi Title Post Baru Milik Member 1',
        'body'=>'Hello World, Ini Body Post Baru Milik Member 1'
    ]);
    return 'Success';
});

/**
 * Mengakses data post
 */
Route::get('/read_posts', function () {
    $user = User::find(1);

    $posts = $user->posts()->get();

    foreach ($posts as $post) {
        $data [] = [
            'name' =>$post->user->name,
            'post_id' =>$post->id,
            'title' =>$post->title,
            'body' =>$post->body
        ];
    }
    return $data;
}); 

/**
 * Mengupdate data post yang dimiliki suatu user
 */
Route::get('/update_post', function () {
    $user = User::findOrFail(1);

    // $user->posts()->where('id', 2)->update([
    //      'title' => 'Ini Title Post Update 2',
    //      'body' => 'Ini Isian Body Post Update 2'
    // ]);

    /**
     * mengupdate seluruh data
     */
    $user->posts()->update([
        'title' => 'Ini Title Post',
        'body' => 'Ini Isian Body Post yang sudah diupdate'
    ]); 

    return 'Success'; 
});

/**
 * menghapus suatu post yang dimiliki suatu user
 */
Route::get('/delete_post', function () {
    $user = User::find(1);

    $user->posts()->whereId(2)->delete();

    return 'Success';
});

Route::get('/create_categories', function () {
    // $post = Post::findOrFail(1);

    // $post->categories()->create([
    //     'slug' => str_slug('PHP', '-'),
    //     'category' => 'PHP'
    // ]);

    // return 'Success';

    $user = User::create([
        'name' => 'Melan',
        'email' => 'melan@gmail.com',
        'password' => bcrypt('password')
    ]);
    
    $user->posts()->create([
        'title' => 'New Title',
        'body' => 'New Body Content'
    ])->categories()->create([
        'slug' => str_slug('New Category', '-'),
        'category' => 'New Category'
    ]);

    return 'Success';
});

/**
 * Menampilkan data category berdasarkan post
 */
Route::get('/read_category', function () {
    $post = Post::find(2);

    $categories = $post->categories;
    foreach ($categories as $category) {
        echo $category->slug . '</br>';
    }

    /**
     * Data mana saja yang memiliki category dengan nilai id tertentu
     */

    // $category = Category::find(1);

    // $posts = $category->post;

    // foreach ($posts as $post) {
    //    echo $post->title . '</br>';
    // }

});

/**
 * Menambah beberapa data category untuk tabel post
 */
Route::get('/attach', function () {
    $post = Post::find(3);
    $post->categories()->attach([1,2,3]);

    return 'Success';
});

/**
 * menghapus id category pada suatu post
 * jika ingin menghapus semua data pada suatu id tidak perlu tuliskan parameter id-nya
 * jika ingin menghapus data lebih dari satu kita bisa gunakan array [id,id] 
 */
Route::get('/detach', function () {
    $post =Post::find(3);
    $post->categories()->detach(1);

    return 'Success';
});

/**
 * mengupdate data category pada suatu post tanpa menggunakan 2 method bersamaan
 * gunakan method sync 
 * sync harus menggunakan array
 */
Route::get('/sync', function () {
    $post = Post::find(3);
    $post->categories()->sync([1,3]);

    return 'Success';
});

Route::get('/role/posts', function () {
    $role = Role::find(1);
    return $role->posts;
});

/**
 * membuat relasi
 */

 Route::get('/comment/create', function () {
    //  $post = Post::find(1);
    //  $post->comments()->create([
    //     'user_id' => 2,
    //     'content' => 'Balasan dari Respon user ID 1' 
    //  ]);

     $portfolio = Portfolio::find(1);
     $portfolio->comments()->create([
        'user_id' => 2,
        'content' => 'Balasan dari Portfolio Respon user ID 1' 
     ]);

     return 'Success';
 });

 Route::get('/comment/read', function () {
     /**
      * untuk mengisi yang post
      */
    //  $post = Post::findOrFail(1);
    //  $comments = $post->comments;
    //  foreach ($comments as $comment){
    //      echo $comment->user->name . '-' . $comment->content .'('. $comment->commentable->title .') <br>';
    //  }


     /**
      * untuk yang portfolio
      */
     $portfolio = Portfolio::findOrFail(1);
     $comments = $portfolio->comments;
     foreach ($comments as $comment){
         echo $comment->user->name . '-' . $comment->content .'('. $comment->commentable->title .') <br>';
     }

    //  return $comments;
 });

 /**
  * Mengupdate suatu komentar
  */
Route::get('/comment/update', function () {
    // $post = Post::find(1);
    // $comment = $post->comments()->where('id', 1)->first();
    // $comment->update([
    //     'content' => 'Komentar telah disunting',
    // ]);

    /**
     * untuk update data di portfolio
     */
    $portfolio = Portfolio::find(1);
    $comment = $portfolio->comments()->where('id', 3)->first();
    $comment->update([
        'content' => 'Komentar telah disunting Portfolio',
    ]);

    return 'Success';
  });

/**
 * menghapus comment
 */
Route::get('/comment/delete', function () {
    // $post = Post::find(1);
    // $comment = $post->comments()->where('id', 1)->delete();

    $post = Post::find(1);
    $post->comments()->where('id', 2)->delete();

    return 'Success';
});

Route::get('/tag/read', function () {
    // $post = Post::find(1);

    // foreach ($post->tags as $tag) {
    //     echo $tag->name . '<br>'; 
    // }

    /** 
     * untuk portfolio
     */
    $portfolio = Portfolio::find(1);

    foreach ($portfolio->tags as $tag) {
        echo $tag->name . '<br>'; 
    }
});
    
/**
 * Untuk menambah data TAG
 */
Route::get('/tag/attach', function () {
    $post = Post::find(1);
    $post->tags()->attach([5,7,8,1]);

    /**
     * portfolio
     */
    // $portfolio = Portfolio ::find(1);
    // $portfolio->tags()->attach([4,6]);

    return 'Success';

});

Route::get('/tag/detach', function () {
    // $post = Post::find(1);
    // $post->tags()->detach([1,3]);

    /**
     * portfolio
     */
    $portfolio = Portfolio ::find(1);
    $portfolio->tags()->detach([2,4]);

    return 'Success';
});

Route::get('/tag/sync', function () {
    $post = Post::find(1);
    $post->tags()->sync([8]);

    return 'Success';
});