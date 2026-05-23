<?php

namespace Tests\Feature;

use Tests\TestCase;

class SkeletonTest extends TestCase
{
    public function test_home_page_is_available(): void
    {
        $response = $this->get('/');

        $response->assertOk();
    }
}

