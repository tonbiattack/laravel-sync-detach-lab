<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Role;
use App\Models\TeamMember;
use App\Services\RoleAdder;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

final class SyncDetachTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $schema = app('db')->connection()->getSchemaBuilder();
        $schema->dropIfExists('member_role');
        $schema->dropIfExists('roles');
        $schema->dropIfExists('team_members');

        $schema->create('team_members', static function (Blueprint $table): void {
            $table->id();
            $table->string('name');
        });
        $schema->create('roles', static function (Blueprint $table): void {
            $table->id();
            $table->string('name');
        });
        $schema->create('member_role', static function (Blueprint $table): void {
            $table->foreignId('member_id');
            $table->foreignId('role_id');
            $table->primary(['member_id', 'role_id']);
        });
    }

    public function test_sync_replaces_the_entire_role_set_when_that_is_the_explicit_contract(): void
    {
        $member = TeamMember::query()->create(['name' => 'Aki']);
        $reader = Role::query()->create(['name' => 'reader']);
        $writer = Role::query()->create(['name' => 'writer']);
        $member->roles()->sync([$reader->id, $writer->id]);

        self::assertSame([$reader->id, $writer->id], $member->roles()->orderBy('roles.id')->pluck('roles.id')->all());
    }

    public function test_adding_a_role_does_not_detach_existing_roles(): void
    {
        $member = TeamMember::query()->create(['name' => 'Aki']);
        $reader = Role::query()->create(['name' => 'reader']);
        $writer = Role::query()->create(['name' => 'writer']);
        $member->roles()->attach($reader->id);

        app(RoleAdder::class)->addRole($member, $writer->id);

        self::assertSame(
            [$reader->id, $writer->id],
            $member->roles()->orderBy('roles.id')->pluck('roles.id')->all(),
            '追加操作が既存ロールをdetachしてはならない'
        );
    }
}
