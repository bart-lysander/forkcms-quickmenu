<?php

/**
 * This is the add-action, it will display a form to create a new item
 *
 * @author Bart Lagerweij <bart@reclame-mediabureau.nl>
 * @copyright Copyright 2013 by Lysander http://www.reclame-mediabureau.nl
 */
class BackendQuickmenuAdd extends BackendBaseActionAdd
{
	/**
	 * Execute the actions
	 */
	public function execute()
	{
		parent::execute();

		$this->loadForm();
		$this->validateForm();

		$this->parse();
		$this->display();
	}

	/**
	 * Load the form
	 */
	protected function loadForm()
	{
		$this->frm = new BackendForm('add');

		$this->frm->addDropdown(
			'page_id', BackendPagesModel::getPagesForDropdown(
				BL::getWorkingLanguage()
			), null
		);

		// get categories
		$categories = BackendQuickmenuModel::getCategories();
		$this->frm->addDropdown('category_id', $categories);
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
				// build the item
				$item['page_id'] = $fields['page_id']->getValue();
				$item['sequence'] = BackendQuickmenuModel::getMaximumSequence() + 1;
				$item['category_id'] = $this->frm->getField('category_id')->getValue();
				$item['language'] = BL::getWorkingLanguage();

				// insert it
				$item['id'] = BackendQuickmenuModel::insert($item);

				BackendModel::triggerEvent(
					$this->getModule(), 'after_add', $item
				);
				$this->redirect(
					BackendModel::createURLForAction('index') . '&report=added&highlight=row-' . $item['id']
				);
			}
		}
	}
}
