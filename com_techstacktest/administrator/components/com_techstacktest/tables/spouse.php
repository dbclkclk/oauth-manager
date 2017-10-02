<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Teachpro
 * @author     TechStack Solutions Ltd <sajendramanandhar@gmail.com>
 * @copyright  2016 TeachPro
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

class TeachproTableSpouse extends JTable {

    public function __construct(&$db) {

        parent::__construct('#__teachpro_spouse', 'id', $db);
    }

}