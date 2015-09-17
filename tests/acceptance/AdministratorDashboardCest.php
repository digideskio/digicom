<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_weblinks
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use \AcceptanceTester;

class AdministratorCategoriesCest
{
    public function administratorVisitDashboard(AcceptanceTester $I)
    {
        $I->am('Administrator');
        $I->wantToTest('Visit Digicom Dashboard in /administrator/');

        $I->doAdministratorLogin();

        $I->amGoingTo('Navigate to Dashboard page in /administrator/');
        $I->amOnPage('/administrator/index.php?option=com_digicom');
        // $I->waitForText('Digicom Dashboard','30',['css' => 'h1']);
        $I->expectTo('See Dashboard');
        $I->checkForPhpNoticesOrWarnings();
        $I->see('Dashboard',['id' => 'page-title']);
    }

    // public function administratorCreateCategory(AcceptanceTester $I)
    // {
    //     $I->am('Administrator');
    //     $I->wantToTest('Category creation in /administrator/');
    //
    //     $I->doAdministratorLogin();
    //
    //     $I->amGoingTo('Navigate to Categories page in /administrator/');
    //     $I->amOnPage('/administrator/index.php?option=com_digicom&view=categories&extension=com_digicom');
    //     $I->waitForText('Digicom Categories Manager','30',['css' => 'h1']);
    //     $I->expectTo('see categories page');
    //     $I->checkForPhpNoticesOrWarnings();
    //
    //     $I->amGoingTo('try to save a category with a filled title');
    //     $I->click(['xpath'=> "//button[@onclick=\"Joomla.submitbutton('category.add')\"]"]);
    //     $I->waitForText('Add Category','30',['css' => 'h1']);
    //     $I->fillField(['id' => 'jform_title'],'automated testing' . rand(1,100));
    //     $I->click(['xpath'=> "//button[@onclick=\"Joomla.submitbutton('category.apply')\"]"]);
    //     $I->expectTo('see a success message after saving the category');
    //     $I->see('Item successfully saved',['id' => 'system-message-container']);
    // }
    //
    // public function administratorCreateCategoryWithoutTitleFails(AcceptanceTester $I)
    // {
    //     $I->am('Administrator');
    //     $I->wantToTest('Category creation in /administrator/ without title');
    //
    //     $I->doAdministratorLogin();
    //
    //     $I->amGoingTo('Navigate to Categories page in /administrator/');
    //     $I->amOnPage('/administrator/index.php?option=com_digicom&view=categories&extension=com_digicom');
    //     $I->waitForText('Digicom Categories Manager','30',['css' => 'h1']);
    //     $I->expectTo('see categories page');
    //
    //     $I->amGoingTo('try to save a category with empty title and it should fail');
    //     $I->click(['xpath'=> "//button[@onclick=\"Joomla.submitbutton('category.add')\"]"]);
    //     $I->waitForText('Add Category','30',['css' => 'h1']);
    //     $I->click(['xpath'=> "//button[@onclick=\"Joomla.submitbutton('category.apply')\"]"]);
    //     $I->expectTo('see an error when trying to save a category without title');
    //     $I->see('Invalid field:  Title',['id' => 'system-message-container']);
    // }
}
