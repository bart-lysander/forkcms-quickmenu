<?php

/**
 * Installer for the Quickmenu module
 *
 * @author Bart Lagerweij <bart@reclame-mediabureau.nl>
 * @copyright Copyright 2013 by Lysander http://www.reclame-mediabureau.nl
 */
class QuickmenuInstaller extends ModuleInstaller
{
	public function install()
	{
		// import the sql
		$this->importSQL(dirname(__FILE__) . '/data/install.sql');

		// install the module in the database
		$this->addModule('quickmenu');

		// install the locale, this is set here beceause we need the module for this
		$this->importLocale(dirname(__FILE__) . '/data/locale.xml');

		$this->setModuleRights(1, 'quickmenu');

		$this->setActionRights(1, 'quickmenu', 'index');
		$this->setActionRights(1, 'quickmenu', 'add');
		$this->setActionRights(1, 'quickmenu', 'edit');
		$this->setActionRights(1, 'quickmenu', 'delete');
		$this->setActionRights(1, 'quickmenu', 'sequence');
		$this->setActionRights(1, 'quickmenu', 'categories');
		$this->setActionRights(1, 'quickmenu', 'add_category');
		$this->setActionRights(1, 'quickmenu', 'edit_category');
		$this->setActionRights(1, 'quickmenu', 'delete_category');
		$this->setActionRights(1, 'quickmenu', 'sequence_categories');

		$navigationModulesId = $this->setNavigation(null, 'Modules');
		$navigationQuickmenuId = $this->setNavigation($navigationModulesId, 'Quickmenu');
		$this->setNavigation(
			$navigationQuickmenuId, 'Quickmenu', 'quickmenu/index',
			array('quickmenu/add', 'quickmenu/edit')
		);
		$this->setNavigation(
			$navigationQuickmenuId, 'Categories', 'quickmenu/categories',
			array('quickmenu/add_category', 'quickmenu/edit_category')
		);
	}
}
