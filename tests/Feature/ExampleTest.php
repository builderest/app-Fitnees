<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_application_renders_welcome_screen(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
