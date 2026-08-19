<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusFareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::flush();
    }

    public function test_authenticated_user_can_update_the_fare(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('bus.update-fare'), [
            'valor_onibus' => 42.5,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('settings', ['key' => 'valor_onibus', 'value' => 42.5]);
    }

    public function test_fare_update_validates_the_value(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put(route('bus.update-fare'), ['valor_onibus' => -1])
            ->assertSessionHasErrors('valor_onibus');

        $this->actingAs($user)
            ->put(route('bus.update-fare'), ['valor_onibus' => 'abc'])
            ->assertSessionHasErrors('valor_onibus');
    }

    public function test_guest_can_not_update_the_fare(): void
    {
        $this->put(route('bus.update-fare'), ['valor_onibus' => 42.5])
            ->assertRedirect(route('login'));
    }

    public function test_bus_total_and_saldo_use_the_stored_fare(): void
    {
        Setting::query()->update(['value' => '10']);
        Setting::flush();

        $bus = new Bus;
        $bus->friday = 1;
        $bus->saturday = 1;
        $bus->sunday = 0;
        $bus->amount = 25;

        $this->assertEquals(20, $bus->total);
        $this->assertEquals(5, $bus->saldo);
    }
}
