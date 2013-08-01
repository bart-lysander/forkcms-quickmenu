<?php

/**
 * This is the edit-action, it will display a form with the item data to edit
 *
 * @author Bart Lagerweij <bart@reclame-mediabureau.nl>
 * @copyright Copyright 2013 by Lysander http://www.reclame-mediabureau.nl
 */
class BackendQuickmenuEdit extends BackendBaseActionEdit
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		parent::execute();

		$this->loadData();
		$this->loadForm();
		$this->validateForm();

		$this->parse();
		$this->display();
	}

	/**
	 * Load the item data
	 */
	protected function loadData()
	{
		$this->id = $this->getParameter('id', 'int', null);
		if ($this->id == null || !BackendQuickmenuModel::exists($this->id)) {
			$this->redirect(
				BackendModel::createURLForAction('index') . '&error=non-existing'
			);
		}

		$this->record = BackendQuickmenuModel::get($this->id);
	}

	/**
	 * Load the form
	 */
	protected function loadForm()
	{
		// create form
		$this->frm = new BackendForm('edit');

		$this->frm->addDropdown('page_id', BackendPagesModel::getPagesForDropdown(), $this->record['page_id']);

		// get categories
		$categories = BackendQuickmenuModel::getCategories();
		$this->frm->addDropdown('category_id', $categories, $this->record['category_id']);
	}

	/**
	 * Parse the page
	 */
	protected function parse()
	{
		parent::parse();

		// get url
		$url = BackendModel::getURLForBlock($this->URL->getModule(), 'detail');
		$url404 = BackendModel::getURL(404);

		// parse additional variables
		if ($url404 != $url) $this->tpl->assign('detailURL', SITE_URL . $url);

		$this->tpl->assign('item', $this->record);
	}

	/**
	 * Validate the form
	 */
	protected function validateForm()
	{
		if ($this->frm->isSubmitted()) {
			$this->frm->cleanupFields();

			// validation
			$fields = $this->frm->getFields();

			$fields['page_id']->isFilled(BL::err('FieldIsRequired'));
			$fields['category_id']->isFilled(BL::err('FieldIsRequired'));

			if ($this->frm->isCorrect()) {
				$item['id'] = $this->id;
				$item['page_id'] = $fields['page_id']->getValue();
//				$item['sequence'] = BackendQuickmenuModel::getMaximumSequence() + 1;
				$item['category_id'] = $this->frm->getField('category_id')->getValue();
				$item['language'] = BL::getWorkingLanguage();

				BackendQuickmenuModel::update($item);
				$item['id'] = $this->id;

				BackendModel::triggerEvent(
					$this->getModule(), 'after_edit', $item
				);
				$this->redirect(
					BackendModel::createURLForAction('index') . '&report=edited&highlight=row-' . $item['id']
				);
			}
		}
	}
}
