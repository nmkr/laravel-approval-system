<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$approverModel = config('approvals.approver_model');
$requesterModel = config('approvals.requester_model');

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define($approverModel, function (Faker\Generator $faker) {
    return [
        'name'     => $faker->name,
        'email'    => $faker->safeEmail,
        'password' => bcrypt($faker->password)
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define($requesterModel, function (Faker\Generator $faker) {
    return [
        'name'     => $faker->name,
        'email'    => $faker->safeEmail,
        'password' => bcrypt($faker->password)
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\Swatkins\Approvals\Models\Widget::class, function (Faker\Generator $faker) use ($requesterModel) {
    return [
        'name'     => $faker->word,
        'owner_id' => factory($requesterModel)->create()->id
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\Swatkins\Approvals\Models\Approval::class, function (Faker\Generator $faker) use ($approverModel) {
    $widget = factory(\Swatkins\Approvals\Models\Widget::class)->create();
    return [
        'approver_id'     => factory($approverModel)->create()->id,
        'approvable_type' => \Swatkins\Approvals\Models\Widget::class,
        'approvable_id'   => $widget->id,
        'approved'        => false,
        'last_activity'   => null,
    ];
});
