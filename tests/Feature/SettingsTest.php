<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Setting;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function testSetContactsVisibility() {
        $this->actingAs(User::factory()->make());

        $response = $this->post('/admin/set-visibility', [
            'isPublic' => 0
        ]);

        $this->assertTrue($response->getContent() === '1');
    }

    public function testContactsVisibilityOff() {
        Setting::factory()->create([
            'value' => 0
        ]);

        $response = $this->get('/contacts');

        $response->assertStatus(404);
    }

    public function testContactsVisibilityOn() {
        Setting::factory()->create([
            'value' => 1
        ]);

        $response = $this->get('/contacts');

        $response->assertStatus(200);
    }
}
