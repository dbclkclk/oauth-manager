<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Teachpro
 * @author     TechStack Solutions Ltd <sajendramanandhar@gmail.com>
 * @copyright  2016 TeachPro
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for creating dispaying parent proposal, score card and score verbiage.
 *
 * @since  1.6
 */
class TeachproViewPdf extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;
    protected $params;
    
    public function display() {
        
//        echo 'hello';exit;
        $parentProposal = $this->getParentProposal();
        $scoreCard = $this->getScoreCard();
        $scoreVerbiage = $this->getScoreVerbiage();
        
        
    }
    
    private function getParentProposal() {
        
        return $this->loadTemplate('parentproposal');
    }
    
    private function getScoreCard() {
        
        return $this->loadTemplate('scorecard');
    }
    
    private function getScoreVerbiage() {
        
        return $this->loadTemplate('scoreverbiage');
    }

}
