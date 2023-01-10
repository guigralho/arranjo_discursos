<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use App\Models\Speech;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.

// Home
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('dashboard'));
});

Breadcrumbs::for('speeches.index', function (BreadcrumbTrail $trail) {
    $trail->push('Discursos', route('speeches.index'));
});

Breadcrumbs::for('speeches.create', function (BreadcrumbTrail $trail) {
    $trail->parent('speeches.index');
    $trail->push('Novo discurso', route('speeches.create'));
});

Breadcrumbs::for('speeches.show', function (BreadcrumbTrail $trail, $speech) {
    $speech = Speech::findOrFail($speech);
    $trail->parent('speeches.index');
    $trail->push($speech->theme, route('speeches.show', $speech->id));
});
