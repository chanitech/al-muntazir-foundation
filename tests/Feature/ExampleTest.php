<?php

namespace Tests\Feature;

use Tests\Concerns\RefreshesWebsiteDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshesWebsiteDatabase;

    /**
     * The homepage (public website) loads.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
