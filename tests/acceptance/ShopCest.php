<?php


class FirstCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function frontpageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Shop');  
    }

    public function addToCart(AcceptanceTester $I)
    {
        $I->sendPOST('/api/shop/addtocart', ['id' => '1', 'qty' => '1']);
        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"success":true}');
    }

    public function removeFromCart(AcceptanceTester $I)
    {
        $I->sendPOST('/api/shop/addtocart', ['id' => '1', 'qty' => '1']);
        $I->seeResponseContains('{"success":true}');

        $I->sendPOST('/api/shop/removeitem', ['id' => '1']);
        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"success":true}');
    }

    public function checkout(AcceptanceTester $I)
    {
        $I->sendPOST('/api/shop/addtocart', ['id' => '1', 'qty' => '1']);
        $I->seeResponseContains('{"success":true}');

        $I->amOnPage('/shop/checkout');
        $I->see('Kindle');
    }

    public function stripe(AcceptanceTester $I)
    {
        $I->sendPOST('/api/shop/teststripe', []);
        $I->seeResponseContains('{"success":true}');
    }

}
