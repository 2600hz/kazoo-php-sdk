<?php

namespace Kazoo\Tests\Functional;

use \Kazoo\Tests\Common\FunctionalTest;

/**
 * @group functional
 */
class ServicePlanTest extends FunctionalTest
{
    /**
     * @test
     */
    public function testListServicePlan() {
        $service_plans = $this->getSDK()->Account()->ServicePlans();

        $this->assertInstanceOf("\\Kazoo\\Api\\Collection\\ServicePlans", $service_plans);

        return $service_plans;
    }

    public function testAvailableServicePlan() {
        $service_plans = $this->getSDK()->Account()->ServicePlans()->available();

        $this->assertInstanceOf("\\Kazoo\\Api\\Collection\\ServicePlans", $service_plans);

        return $service_plans[0];
    }

     /**
      * @test
      * @depends testAvailableServicePlan
      */
    public function testGetServicePlan($service_plan) {

        $service_plan = $this->getSDK()->Account()->ServicePlan($service_plan->id);

        $this->assertInstanceOf("\\Kazoo\\Api\\Entity\\ServicePlan", $service_plan);
        $this->assertTrue((strlen($service_plan->getId()) > 0));

        return $service_plan->getId();
    }
}
