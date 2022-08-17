<?php

use App\Models\User;
use App\Models\Preference;
use App\Models\Course;
use App\Models\Permission;
use App\Models\Image;
use App\Models\Comment;
use App\Models\Tag;
use Illuminate\Support\Facades\Route;

Route::get('/many-to-many-polymorphic', function () {
    // $user = User::first();

    // Tag::create(['name' => 'tag1', 'color' => 'blue']);
    // Tag::create(['name' => 'tag2', 'color' => 'red']);
    // Tag::create(['name' => 'tag3', 'color' => 'green']);

    // $user->tags()->attach(2);

    $course = Course::first();

    $course->tags()->attach(2);

    dd($course->tags);
});

Route::get('/one-to-many-polymorphic', function () {
    $course = Course::first();

    // $course->comments()->create([
    //     'subject' => 'Novo Comentário 2',
    //     'content' => 'Apenas um comentário legal',
    // ]);

    // dd($course->comments);

    $comment = Comment::find(1);

    dd($comment->commentable);
});

Route::get('/one-to-one-polymorphic', function () {
    $user = User::first();

    $data = ['path' => 'path/nome-image2.png'];

    if ($user->image) {
        $user->image->update($data);
    } else {
        $user->image->create($data);
    }

    dd($user->image->path);
});

Route::get('/many-to-many-pivot', function () {
    $user = User::with('permissions')->find(1);
    // $user->permissions()->attach([
    //     1 => ['active' => false],
    //     3 => ['active' => false],
    // ]);

        echo "<b>{$user->name}</b><br>";
    foreach ($user->permissions as $permission) {
        echo "{$permission->name} - {$permission->pivot->active} <br>";
    }
});

Route::get('/many-to-many', function () {
  $user = User::with('permissions')->find(1);

  $permission = Permission::find(1);
  // $user->permissions()->save($permission);

//   $user->permissions()->saveMany([
//       Permission::find(1),
//       Permission::find(3),
//       Permission::find(2),
//     ]);

    // $user->permissions()->sync([2]);
    $user->permissions()->attach([1, 3]);

    $user->refresh();

    dd($user->permissions);
});

Route::get('/one-to-one', function() {
    $user = User::with('preference')->find(2);

    $data = [
        'background_color' => '#000',
    ];

    if ($user->preference) {
        $user->preference()->update($data);
    } else {
        // $user->preference()->create($data);
        $preference = new Preference($data);
        $user->preference()->save($preference);
    }

    $preference = $user->preference;

    $user->refresh();

    dd($user->preference);
});

Route::get('/one-to-many', function () {
    // $course = Course::create(['name' => 'Curso de Laravel']);
    $course = Course::with('modules.lessons')->first();

    echo $course->name;
    echo '<br>';
    foreach ($course->modules as $module) {
        echo "Modulo {$module->name} <br>";

        foreach ($module->lessons as $lesson) {
            echo "Aula {$lesson->name} <br>";
        }
    }

    dd($course);

    $data = [
        'name' => 'Modulo x2'
    ];

    $course->modules()->create($data);

    // $course->modules()->get();
    $modules = $course->modules;

    dd($modules);

});


Route::get('/', function () {
    return view('welcome');
});
