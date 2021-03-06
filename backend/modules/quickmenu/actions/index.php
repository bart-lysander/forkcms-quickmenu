<?php

/**
 * This is the index-action (default), it will display the overview of Quickmenu posts
 *
 * @author Bart Lagerweij <bart@reclame-mediabureau.nl>
 * @copyright Copyright 2013 by Lysander http://www.reclame-mediabureau.nl
 */
class BackendQuickmenuIndex extends BackendBaseActionIndex
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		parent::execute();
		$this->loadDataGrid();

		$this->parse();
		$this->display();
	}

	/**
	 * Load the dataGrid
	 */
	protected function loadDataGrid()
	{
		$this->dataGrid = new BackendDataGridDB(
			BackendQuickmenuModel::QRY_DATAGRID_BROWSE,
			array('active', BL::getWorkingLanguage())
		);

		// reform date
		$this->dataGrid->setColumnFunction(array('BackendDataGridFunctions', 'getLongDate'), array('[created_on]'), 'created_on', true);
		$this->dataGrid->setColumnHidden('page_id');

		// drag and drop sequencing
		$this->dataGrid->enableSequenceByDragAndDrop();

		// check if this action is allowed
		if(BackendAuthentication::isAllowedAction('edit'))
		{
			$this->dataGrid->addColumn(
				'edit', null, BL::lbl('Edit'),
				BackendModel::createURLForAction('edit') . '&amp;id=[id]',
				BL::lbl('Edit')
			);
			$this->dataGrid->setColumnURL(
				'title', BackendModel::createURLForAction('edit') . '&amp;id=[id]'
			);
		}
	}

	/**
	 * Parse the page
	 */
	protected function parse()
	{
		// parse the dataGrid if there are results
		$this->tpl->assign('dataGrid', (string) $this->dataGrid->getContent());
	}
}
