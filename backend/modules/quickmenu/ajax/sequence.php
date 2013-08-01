<?php

/**
 * Alters the sequence of Quickmenu articles
 *
 * @author Bart Lagerweij <bart@reclame-mediabureau.nl>
 * @copyright Copyright 2013 by Lysander http://www.reclame-mediabureau.nl
 */
class BackendQuickmenuAjaxSequence extends BackendBaseAJAXAction
{
	public function execute()
	{
		parent::execute();

		// get parameters
		$newIdSequence = trim(SpoonFilter::getPostValue('new_id_sequence', null, '', 'string'));

		// list id
		$ids = (array) explode(',', rtrim($newIdSequence, ','));

		// loop id's and set new sequence
		foreach($ids as $i => $id)
		{
			$item['id'] = $id;
			$item['sequence'] = $i + 1;

			// update sequence
			if(BackendQuickmenuModel::exists($id)) BackendQuickmenuModel::update($item);
		}

		// success output
		$this->output(self::OK, null, 'sequence updated');
	}
}
