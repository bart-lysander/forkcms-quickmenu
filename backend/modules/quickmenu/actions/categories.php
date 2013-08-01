<?php

/**
 * This is the categories-action, it will display the overview of categories
 *
 * @author Bart Lagerweij <bart@reclame-mediabureau.nl>
 * @copyright Copyright 2013 by Lysander http://www.reclame-mediabureau.nl
 */
class BackendQuickmenuCategories extends BackendBaseActionIndex
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
    private function loadDataGrid()
    {
        $this->dataGrid = new BackendDataGridDB(
            BackendQuickmenuModel::QRY_DATAGRID_BROWSE_CATEGORIES,
            BL::getWorkingLanguage()
        );

        // check if this action is allowed
        if (BackendAuthentication::isAllowedAction('edit_category')) {
            $this->dataGrid->addColumn(
                'edit', null, BL::lbl('Edit'),
                BackendModel::createURLForAction('edit_category') . '&amp;id=[id]',
                BL::lbl('Edit')
            );
            $this->dataGrid->setColumnURL(
                'title', BackendModel::createURLForAction('edit_category') . '&amp;id=[id]'
            );
        }

        // sequence
        $this->dataGrid->enableSequenceByDragAndDrop();
        $this->dataGrid->setAttributes(array('data-action' => 'sequence_categories'));
    }

    /**
     * Parse & display the page
     */
    protected function parse()
    {
        $this->tpl->assign('dataGrid', (string)$this->dataGrid->getContent());
    }
}
