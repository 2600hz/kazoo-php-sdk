<?php

namespace Kazoo\Tests\Functional;

use Kazoo\Api\Data\Entity\Account;

/**
 * @group functional
 */
class AccountTest extends TestCase {

    /**
     * @test
     */
    public function testEmptyShell() {
        $account = $this->client->accounts()->new();
        $this->assertInstanceOf("Kazoo\\Api\\Data\\Entity\\Account", $account);
    }
    
//    public function testCreate(){
//        $account = $this->client->accounts()->new();
//        $account->title = "Blah";
//        $savedAccount = $this->client->accounts()->create($account);
//        
//        $this->assertInstanceOf("Kazoo\\Api\\Resource\\Account", $savedAccount);
//    }
//    
//    public function testRetrieveOne(){
//        $subaccount_id = 2;
//        $account = $this->client->accounts()->retrieve($subaccount_id);
//    }
//    
//    public function testUpdate(){
//        $subaccount_id = 2;
//        $account = $this->client->accounts()->retrieve($subaccount_id);
//        $account->title = "New Title";
//        $account->save();
//    }
//    
//    public function testDelete(){
//        $subaccount_id = 2;
//        $account = $this->client->accounts()->retrieve($subaccount_id);
//        $account->delete();
//    }
//    
//    public function testRetriveAll() {
//        $accounts = $this->client->api('accounts')->retrieve();
//        print_r($accounts);
//        die();
//    }
}
