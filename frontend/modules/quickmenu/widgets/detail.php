<?php

/**
 * This is a widget with the Quickmenu-categories
 *
 * @author Bart Lagerweij <bart@reclame-mediabureau.nl>
 * @copyright Copyright 2013 by Lysander http://www.reclame-mediabureau.nl
 */
class FrontendQuickmenuWidgetDetail extends FrontendBaseWidget
{
	/**
	 * Execute the extra
	 */
	public function execute()
	{
		parent::execute();
		$this->loadTemplate();
		$this->parse();
	}

	/**
	 * Parse
	 */
	private function parse()
	{

		$items = FrontendQuickmenuModel::getAllByCategory((int) $this->data['id']);

        $pageId = Spoon::get('page')->getId();
        foreach ($items as &$item) {
            if ($pageId == $item['page_id']) {
                $item['selected'] = true;
            }
        }

        // assign comments
		$this->tpl->assign('widgetQuickmenuDetail', $items);
	}
}
