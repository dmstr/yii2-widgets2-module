<?php
// @group mandatory
$I = new CliTester($scenario);
$I->runShellCommand('yii copy-widgets/language en de');
$I->seeInShellOutput('1 widgets has been copied into language de');

$I->runShellCommand('yii copy-widgets/language de ru');
$I->seeInShellOutput('1 widgets has been copied into language ru');
