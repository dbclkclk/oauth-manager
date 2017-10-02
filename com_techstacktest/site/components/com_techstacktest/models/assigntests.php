<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TeachProModelAssigntests extends JModelItem
{
    
    public function getChildList($userId) 
    {
	$db= JFactory::getDBO();
	$query = $db->getQuery(true);
	$query->select('id,firstname,lastname');
	$query->from('#__teachpro_student');
	$query->where("userid = ". $userId);
	$db->setQuery((string) $query);
	$result = $db->loadObjectList();
        return $result;
    }

    public function getSubjectList() 
    {
	$db= JFactory::getDBO();
	$query = $db->getQuery(true);
	$query->select('id,subject_name');
	$query->from('#__teachpro_subject');
	$db->setQuery((string) $query);
	$result = $db->loadObjectList();
        return $result;
    }

    public function getAllGrade() 
    {
    	$db= JFactory::getDBO();
	$query = $db->getQuery(true);
	$query->select('DISTINCT grade');
	$query->from('#__teachpro_subject');
	$db->setQuery((string) $query);
	$result = $db->loadObjectList();
        return $result;
    }

     public function filteredSubjectList($gradeId) 
    {
	$db= JFactory::getDBO();
	$query = $db->getQuery(true);
	$query->select('id,subject_name');
	$query->from('#__teachpro_subject');
	$query->where('grade = '. (int)$gradeId);
	$db->setQuery((string) $query);
	$result = $db->loadObjectList();
        return $result;
    }

}
    