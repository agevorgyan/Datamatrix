<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_unauthenticated_user_can_access_homepage_in_guest_mode(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Դատամատրիքսի գեներացման և տպագրության համակարգ');
    }
}
