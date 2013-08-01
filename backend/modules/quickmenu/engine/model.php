<?php

/**
 * In this file we store all generic functions that we will be using in the Quickmenu module
 *
 * @author Bart Lagerweij <bart@reclame-mediabureau.nl>
 * @copyright Copyright 2013 by Lysander http://www.reclame-mediabureau.nl
 */
class BackendQuickmenuModel
{
    /**
     *
     */
    const QRY_DATAGRID_BROWSE =
        'SELECT q.id, p.title, c.title as category, page_id, UNIX_TIMESTAMP(q.created_on) AS created_on, q.sequence
         FROM quickmenu AS q
         INNER JOIN pages AS p ON (p.id=q.page_id AND p.language = q.language AND p.status = ?)
         INNER JOIN quickmenu_categories c ON c.id = q.category_id
         WHERE q.language = ?
         ORDER BY q.sequence';

    /**
     *
     */
    const QRY_DATAGRID_BROWSE_CATEGORIES =
        'SELECT c.id, c.title, COUNT(i.id) AS num_items, c.sequence
         FROM quickmenu_categories AS c
         LEFT OUTER JOIN quickmenu AS i ON (c.id = i.category_id AND i.language = ?)
         GROUP BY c.id
         ORDER BY c.sequence ASC';

    /**
     * Delete a certain item
     *
     * @param int $id
     */
    public static function delete($id)
    {
        BackendModel::getContainer()->get('database')->delete('quickmenu', 'id = ?', (int)$id);
    }

    /**
     * Delete a specific category
     *
     * @param int $id
     */
    public static function deleteCategory($id)
    {
        $db = BackendModel::getContainer()->get('database');
        $item = self::getCategory($id);

        // build extra
        $extra = array('id' => $item['extra_id'],
            'module' => 'quickmenu',
            'type' => 'widget',
            'action' => 'detail');

        // delete extra
        $db->delete('modules_extras', 'id = ? AND module = ? AND type = ? AND action = ?', array($extra['id'], $extra['module'], $extra['type'], $extra['action']));

        if (!empty($item)) {
            $db->delete('quickmenu_categories', 'id = ?', array((int)$id));
            $db->update('quickmenu', array('category_id' => null), 'category_id = ?', array((int)$id));
        }
    }

    /**
     * Checks if a certain item exists
     *
     * @param int $id
     * @return bool
     */
    public static function exists($id)
    {
        return (bool)BackendModel::getContainer()->get('database')->getVar(
            'SELECT 1
             FROM quickmenu AS i
             WHERE i.id = ?
             LIMIT 1',
            array((int)$id)
        );
    }

    /**
     * Does the category exist?
     *
     * @param int $id
     * @return bool
     */
    public static function existsCategory($id)
    {
        return (bool)BackendModel::getContainer()->get('database')->getVar(
            'SELECT 1
             FROM quickmenu_categories AS i
             WHERE i.id = ?
             LIMIT 1',
            array((int)$id));
    }

    /**
     * Fetches a certain item
     *
     * @param int $id
     * @return array
     */
    public static function get($id)
    {
        return (array)BackendModel::getContainer()->get('database')->getRecord(
            'SELECT i.*
             FROM quickmenu AS i
             WHERE i.id = ?',
            array((int)$id)
        );
    }

    /**
     * Get all the categories
     *
     * @param bool[optional] $includeCount
     * @return array
     */
    public static function getCategories($includeCount = false)
    {
        $db = BackendModel::getContainer()->get('database');

        if ($includeCount) {
            return (array)$db->getPairs(
                'SELECT i.id, CONCAT(i.title, " (",  COUNT(p.category_id) ,")") AS title
                 FROM quickmenu_categories AS i
                 LEFT OUTER JOIN quickmenu AS p ON i.id = p.category_id
                 GROUP BY i.id'
            );
        }

        return (array)$db->getPairs(
            'SELECT i.id, i.title
             FROM quickmenu_categories AS i'
        );
    }

    /**
     * Fetch a category
     *
     * @param int $id
     * @return array
     */
    public static function getCategory($id)
    {
        return (array)BackendModel::getContainer()->get('database')->getRecord(
            'SELECT i.*
             FROM quickmenu_categories AS i
             WHERE i.id = ?',
            array($id)
        );
    }

    /**
     * Get the maximum sequence for a category
     *
     * @return int
     */
    public static function getMaximumCategorySequence()
    {
        return (int)BackendModel::getContainer()->get('database')->getVar(
            'SELECT MAX(i.sequence)
             FROM quickmenu_categories AS i'
        );
    }

    /**
     * Get the maximum Quickmenu sequence.
     *
     * @return int
     */
    public static function getMaximumSequence()
    {
        return (int)BackendModel::getContainer()->get('database')->getVar(
            'SELECT MAX(i.sequence)
             FROM quickmenu AS i'
        );
    }

    /**
     * Insert an item in the database
     *
     * @param array $item
     * @return int
     */
    public static function insert(array $item)
    {
        /* @var $db SpoonDatabase */
        $db = BackendModel::getContainer()->get('database');

        $item['created_on'] = BackendModel::getUTCDate();
        $item['id'] = (int)$db->insert('quickmenu', $item);

        return $item['id'];

    }

    /**
     * Insert a category in the database
     *
     * @param array $item
     * @return int
     */
    public static function insertCategory(array $item)
    {
        /* @var $db SpoonDatabase */
        $db = BackendModel::getContainer()->get('database');

        $item['created_on'] = BackendModel::getUTCDate();

        // build extra
        $extra = array(
            'module' => 'quickmenu',
            'type' => 'widget',
            'label' => 'Quickmenu',
            'action' => 'detail',
            'data' => null,
            'hidden' => 'N',
            'sequence' => $db->getVar(
                'SELECT MAX(i.sequence) + 1
                 FROM modules_extras AS i
                 WHERE i.module = ?',
                array('quickmenu')
            )
        );

        if (is_null($extra['sequence'])) $extra['sequence'] = $db->getVar(
            'SELECT CEILING(MAX(i.sequence) / 1000) * 1000
             FROM modules_extras AS i'
        );

        // insert extra
        $item['extra_id'] = $db->insert('modules_extras', $extra);
        $extra['id'] = $item['extra_id'];

        $item['id'] = $db->insert('quickmenu_categories', $item);

        // update extra (item id is now known)
        $extra['data'] = serialize(
            array(
                'id' => $item['id'],
                'extra_label' => $item['title'],
            )
        );
        $db->update(
            'modules_extras',
            $extra,
            'id = ? AND module = ? AND type = ? AND action = ?',
            array($extra['id'], $extra['module'], $extra['type'], $extra['action'])
        );


    }

    /**
     * Updates an item
     *
     * @param array $item
     */
    public static function update(array $item)
    {
        $item['edited_on'] = BackendModel::getUTCDate();

        BackendModel::getContainer()->get('database')->update(
            'quickmenu', $item, 'id = ?', (int)$item['id']
        );
    }

    /**
     * @param array $item
     * @return int
     */
    public static function updateCategory(array $item)
    {
        /* @var $db SpoonDatabase */
        $db = BackendModel::getContainer()->get('database');

        $item['edited_on'] = BackendModel::getUTCDate();

        // build extra
        $extra = array(
            'id' => $item['extra_id'],
            'module' => 'quickmenu',
            'type' => 'widget',
            'label' => 'QuickMenu',
            'action' => 'detail',
            'data' => serialize(
                array(
                    'id' => $item['id'],
                    'extra_label' => $item['title'],
                )
            ),
            'hidden' => 'N');

        // update extra
        $db->update('modules_extras', $extra, 'id = ? AND module = ? AND type = ? AND action = ?', array($extra['id'], $extra['module'], $extra['type'], $extra['action']));

        $affectedRows = $db->update('quickmenu_categories', $item, 'id = ?', array($item['id']));

        return $affectedRows;

    }

}
