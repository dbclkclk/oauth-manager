<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class TeachproViewTestss extends JViewLegacy {

    protected $items;
    protected $state;
    protected $pagination;

    public function display($tpl = null) {

        $model = JModelItem::getInstance('Subjectstudentss', 'TeachproModel', array('ignore_request' => true));

        $this->setModel($model, true);

        $mod = $this->getModel();

        $this->items = $mod->getPaidForStudentSubject();

        parent::display($tpl);
    }

    public function checkAndSaveTransection($txn, $custom) {

        $paymentModel = JModelItem::getInstance('Payments', 'TeachproModel', array('ignore_request' => true));

        if ($paymentModel->checkAndSaveTransection($txn, $custom)) {

            return true;
        } else {
            return false;
        }
    }


    public function getEnrollmentStatus($studentSubjectId) {

        $enrollment = JModelForm::getInstance('EnrollmentForm', 'TeachproModel', array('ignore_request' => true));

        $enrollmentStatus = $enrollment->getEnrollmentStatus($studentSubjectId);

        return $enrollmentStatus;
    }

    public function isTestTaken($studentSubjectId) {

        $tests = JModelItem::getInstance('Testss', 'TeachproModel', array('ignore_request' => true));

        $testId = $tests->getRecentTestIdByStudentSubjectId($studentSubjectId);

        // If test is taken
        if ($testId) {

            $testStatus = $tests->getTestStatus($testId, $studentSubjectId);
        } else {

            // Test is not taken.
            $testStatus = 0;
        }


        return $testStatus;
    }

    /*     * **************************************************************************************************************************************************** */
}
