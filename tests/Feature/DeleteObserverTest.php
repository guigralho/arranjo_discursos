<?php

namespace Tests\Feature;

use App\Models\Speech;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteObserverTest extends TestCase
{
    use RefreshDatabase;

    public function test_soft_delete_records_the_user_who_deleted(): void
    {
        $user = User::factory()->create();
        $speech = $this->createSpeech();

        $this->actingAs($user);
        $speech->delete();

        $this->assertSoftDeleted('speeches', ['id' => $speech->id, 'user_deleted_id' => $user->id]);
    }

    public function test_force_delete_removes_the_record(): void
    {
        $user = User::factory()->create();
        $speech = $this->createSpeech();

        $this->actingAs($user);
        $speech->forceDelete();

        $this->assertDatabaseMissing('speeches', ['id' => $speech->id]);
    }

    private function createSpeech(): Speech
    {
        $speech = new Speech;
        $speech->number = fake()->numberBetween(1, 200);
        $speech->theme = fake()->sentence();
        $speech->save();

        return $speech;
    }
}
