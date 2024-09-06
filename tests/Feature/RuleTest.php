<?php

namespace Tests\Feature;

use App\Models\RuleParameterCombinationWater;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class RuleTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {   
        $user = User::first();
        $this->withoutExceptionHandling();
        $ruleParamCombination = RuleParameterCombinationWater::factory()->create();
        $response = $this->actingAs($user)
            ->delete("/parameters-combinations/$ruleParamCombination->id/remove-param-combination");
        
        $response->assertStatus(200);
    }
}
