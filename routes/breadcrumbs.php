<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use App\Models\Bus;
use App\Models\Passenger;
use App\Models\Schedule;
use App\Models\Speaker;
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

//
Breadcrumbs::for('speakers.index', function (BreadcrumbTrail $trail) {
    $trail->push('Oradores', route('speakers.index'));
});

Breadcrumbs::for('speakers.create', function (BreadcrumbTrail $trail) {
    $trail->parent('speakers.index');
    $trail->push('Novo orador', route('speakers.create'));
});

Breadcrumbs::for('speakers.show', function (BreadcrumbTrail $trail, $speaker) {
    $speaker = Speaker::findOrFail($speaker);
    $trail->parent('speakers.index');
    $trail->push($speaker->name, route('speakers.show', $speaker->id));
});

//
Breadcrumbs::for('schedules.index', function (BreadcrumbTrail $trail) {
    $trail->push('Arranjos', route('schedules.index'));
});

Breadcrumbs::for('schedules.create', function (BreadcrumbTrail $trail) {
    $trail->parent('schedules.index');
    $trail->push('Novo arranjo', route('schedules.create'));
});

Breadcrumbs::for('schedules.show', function (BreadcrumbTrail $trail, $schedule) {
    $schedule = Schedule::findOrFail($schedule);
    $trail->parent('schedules.index');
    $trail->push($schedule->congregation, route('schedules.show', $schedule->id));
});

//
Breadcrumbs::for('bus.index', function (BreadcrumbTrail $trail) {
    $trail->push('Ônibus', route('bus.index'));
});

Breadcrumbs::for('bus.create', function (BreadcrumbTrail $trail) {
    $trail->parent('bus.index');
    $trail->push('Novo passageiro', route('bus.create'));
});

Breadcrumbs::for('bus.show', function (BreadcrumbTrail $trail, $bu) {
    $bus = Bus::findOrFail($bu);
    $trail->parent('bus.index');
    $trail->push($bus->passenger->name, route('bus.show', $bus->id));
});

//
Breadcrumbs::for('passengers.index', function (BreadcrumbTrail $trail) {
    $trail->push('Passageiros', route('passengers.index'));
});

Breadcrumbs::for('passengers.create', function (BreadcrumbTrail $trail) {
    $trail->parent('passengers.index');
    $trail->push('Novo passageiro', route('passengers.create'));
});

Breadcrumbs::for('passengers.show', function (BreadcrumbTrail $trail, $passenger) {
    $passenger = Passenger::findOrFail($passenger);
    $trail->parent('passengers.index');
    $trail->push($passenger->name, route('passengers.show', $passenger->id));
});
