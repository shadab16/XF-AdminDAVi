<?php

/**
 * Phrases directory.
 *
 * @author Shadab Ansari
 * @package GeekPoint_AdminDAVi
 */
class GeekPoint_AdminDAVi_Directory_Phrases extends Sabre_DAV_Directory
{
	/**
	 * Addon ID corresponding to this directory
	 *
	 * @var string
	 */
	protected $_addonId;

	/**
	 * Construct directory for the given addon
	 *
	 * @param string $addonId
	 */
	public function __construct($addonId)
	{
		$this->_addonId = strval($addonId);
	}

	/**
	 * Returns an array with all the child nodes
	 *
	 * @return Sabre_DAV_INode[]
	 */
	public function getChildren()
	{
		/* @var $phraseModel XenForo_Model_Phrase */
		$phraseModel = XenForo_Model::create('XenForo_Model_Phrase');
		$addon = $this->_addonId;

		$output = array();
		foreach ($phraseModel->getMasterPhrasesInAddOn($addon) as $title => $phrase)
		{
			$output[] = new GeekPoint_AdminDAVi_File_Phrase($addon, $title, $phrase);
		}

		return $output;
	}

	/**
	 * Returns a child object, by its name.
	 *
	 * @param string $name
	 * @throws Sabre_DAV_Exception_FileNotFound
	 * @return Sabre_DAV_INode
	 */
	public function getChild($title)
	{
		if (substr($title, -4) == '.txt')
		{
			$title = substr($title, 0, -4);
		}

		return parent::getChild($title);

		throw new Sabre_DAV_Exception_FileNotFound('Phrase not found: ' . $title);
	}

	/**
	 * Creates a new file in the directory
	 *
	 * @param string $title Name of the file
	 * @param string $data Initial payload
	 * @throws Sabre_DAV_Exception_PermissionDenied
	 * @return void
	 */
	public function createFile($title, $data = null)
	{
		throw new Sabre_DAV_Exception_PermissionDenied('Permission denied to create file (' . $name . ')');
	}

	/**
	 * Returns the name of the node
	 *
	 * @return string
	 */
	public function getName()
	{
		return GeekPoint_AdminDAVi_Directory_Root::PHRASES;
	}
}