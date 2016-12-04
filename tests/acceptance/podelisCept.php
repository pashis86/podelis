<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Perform actions and see result.');

$I->amOnPage('/');
$I->see('Log in');
$I->click('Log in');
$I->fillField('_username', 'alvydas');
$I->fillField('_password', '123456');
$I->click('login_in_btn');
$I->wait(1);
$I->see('alvydas');

$I->click('Zend test');
$I->wait(1);
$I->click('Submit');
$I->wait(1);
$I->see("You've scored");

/*$I->click('Create a new post');
$I->fillField('post[title]', 'NFQ Akademija title');
$I->fillField('post[summary]', 'NFQ Akademija summary');
$I->fillField('post[content]', 'NFQ Akademija content');
$I->executeJS('window.scrollTo(0,document.body.scrollHeight);');
$I->click('Create post');
$I->see('NFQ Akademija title');
$I->executeJS('window.scrollTo(0,document.body.scrollHeight);');
$I->click('Show', '#main tbody tr:nth-last-child(1)');
$I->see('NFQ Akademija content');
$I->click('form input[value="Delete post"]');
$I->waitForElementVisible('#confirmationModal #btnYes', 2);
$I->click("#confirmationModal #btnYes");
$I->see('Post deleted successfully!');
$I->wait(50);