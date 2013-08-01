<?php

/**
 * In this file we store all generic functions that we will be using in the Quickmenu module
 *
 * @author Bart Lagerweij <bart@reclame-mediabureau.nl>
 * @copyright Copyright 2013 by Lysander http://www.reclame-mediabureau.nl
 */
class FrontendQuickmenuModel
{
	/**
	 * @param $categoryId
	 * @return array
	 */
	public static function getAllByCategory($categoryId)
	{
		$items = (array)FrontendModel::getContainer()->get('database')->getRecords(
			'SELECT q.*, p.title, m.url
			 FROM quickmenu AS q
			 LEFT JOIN pages AS p ON (p.id=q.page_id AND p.language = q.language AND p.status = ?)
	         LEFT JOIN meta AS m ON p.meta_id = m.id
			 WHERE q.category_id = ? AND q.language = ?
			 ORDER BY q.sequence ASC, q.id DESC',
			array('active', $categoryId, FRONTEND_LANGUAGE));

		// no results?
		if (empty($items)) {
			return array();
		}

        // return
		return $items;
	}
}
