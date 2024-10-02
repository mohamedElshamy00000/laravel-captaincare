<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

Breadcrumbs::for('admin.index', function (BreadcrumbTrail $trail): void {
    $trail->push('Dashboard', route('admin.index'));
});
Breadcrumbs::for('admin.users.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Users', route('admin.users.index'));
});
Breadcrumbs::for('admin.users.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.users.index');
    $trail->push('Add new user', route('admin.users.create'));
});
// Role
Breadcrumbs::for('admin.roles.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Roles', route('admin.roles.index'));
});
Breadcrumbs::for('admin.roles.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.roles.index');

    $trail->push('Add new role', route('admin.roles.create'));
});
Breadcrumbs::for('admin.roles.edit', function (BreadcrumbTrail $trail, Role $post): void {
    $trail->parent('admin.roles.index');

    $trail->push($post->name, route('admin.roles.edit', $post));
});
// Permission
Breadcrumbs::for('admin.permissions.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Permissions', route('admin.permissions.index'));
});
Breadcrumbs::for('admin.permissions.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.permissions.index');

    $trail->push('Add new permission', route('admin.permissions.create'));
});
Breadcrumbs::for('admin.permissions.edit', function (BreadcrumbTrail $trail, Permission $post): void {
    $trail->parent('admin.permissions.index');

    $trail->push($post->name, route('admin.permissions.edit', $post));
});
// profile
Breadcrumbs::for('admin.profile.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Profile', route('admin.profile.index'));
});
// change password
Breadcrumbs::for('admin.password.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Change Password', route('admin.password.index'));
});

// schools
Breadcrumbs::for('admin.schools.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Schools', route('admin.schools.index'));
});
Breadcrumbs::for('admin.schools.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.schools.index');
    $trail->push('Add new schools', route('admin.schools.create'));
});
Breadcrumbs::for('admin.schools.show', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Schools', route('admin.schools.index'));
    $trail->push('show school');
});
Breadcrumbs::for('admin.schools.edit', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Schools', route('admin.schools.index'));
    $trail->push('Edit school');
});

// classes

Breadcrumbs::for('admin.school.classes.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Schools', route('admin.schools.index'));
    $trail->push('add school calss');
});
Breadcrumbs::for('admin.school.classes.edit', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Schools', route('admin.schools.index'));
    $trail->push('Edit school calss');
});

// groups
Breadcrumbs::for('admin.groups.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('All group');
});
Breadcrumbs::for('admin.groups.show', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Show group');
});
Breadcrumbs::for('admin.school.create.group', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Schools', route('admin.schools.index'));
    $trail->push('add new group');
});
Breadcrumbs::for('admin.groups.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('add new group');
});

Breadcrumbs::for('admin.groups.edit', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Edit Group');
});


// Driver
Breadcrumbs::for('admin.drivers.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Drivers', route('admin.drivers.index'));
});
Breadcrumbs::for('admin.drivers.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.drivers.index');
    $trail->push('Add new drivers', route('admin.drivers.create'));
});
Breadcrumbs::for('admin.drivers.edit', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.drivers.index');
    $trail->push('edit drivers', 'admin.drivers.update');
});
Breadcrumbs::for('admin.drivers.show', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('show driver');
});
Breadcrumbs::for('admin.driver.add.car', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('add car to driver');
});
Breadcrumbs::for('admin.cars.edit', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Edit car');
});

// fathers
Breadcrumbs::for('admin.fathers.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Fathers', route('admin.fathers.index'));
});
Breadcrumbs::for('admin.fathers.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.fathers.index');
    $trail->push('Add new fathers', route('admin.fathers.create'));
});
Breadcrumbs::for('admin.fathers.edit', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.fathers.index');
    $trail->push('edit fathers', 'admin.fathers.update');
});
Breadcrumbs::for('admin.fathers.show', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('show Father');
});

// semester
Breadcrumbs::for('admin.semesters.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('semesters');
});
Breadcrumbs::for('admin.school.create.semester', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('create semester');
});

Breadcrumbs::for('admin.semesters.edit', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Edit Semester');
});

// school holidays
Breadcrumbs::for('admin.school.holiday.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('create holiday');
});
Breadcrumbs::for('admin.school.holiday.edit', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('edit holiday');
});
Breadcrumbs::for('admin.school.holiday.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('school holidays');
});
// official holidays
Breadcrumbs::for('admin.official.holiday.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('create holiday');
});
Breadcrumbs::for('admin.official.holiday.edit', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('edit holiday');
});
Breadcrumbs::for('admin.official.holiday.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('official holidays');
});



// plans

Breadcrumbs::for('admin.plans', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('plans');
});
Breadcrumbs::for('admin.subscription.create.plan', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Create plans');
});
Breadcrumbs::for('admin.subscription.plans.details', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('plan');
});
Breadcrumbs::for('admin.subscription.create.feature', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('plan feature');
});
Breadcrumbs::for('admin.subscription.edit.plan', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Edit plans');
});

//  invocie
Breadcrumbs::for('admin.subscription.invoices', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Invoices');
});
Breadcrumbs::for('admin.subscription.invoice.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Create Invoices');
});
Breadcrumbs::for('admin.subscription.invoice.edit', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Edit Invoices');
});

// setting

Breadcrumbs::for('admin.main.setting', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('main setting');
});
Breadcrumbs::for('admin.payment.form', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('test payment');
});
Breadcrumbs::for('admin.payment.initiate', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('test payment');
});


Breadcrumbs::for('admin.children.edit', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Edit children');
});


// trips
Breadcrumbs::for('admin.trips.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Trips');
});
