<?php

/**
 * Admin-templates directory.
 *
 * @author Shadab Ansari
 * @package GeekPoint_AdminDAVi
 */
class GeekPoint_AdminDAVi_Directory_AdminTemplates extends Sabre_DAV_Directory
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
		/* @var $adminTemplateModel XenForo_Model_AdminTemplate */
		$adminTemplateModel = XenForo_Model::create('XenForo_Model_AdminTemplate');
		$addon = $this->_addonId;

		$output = array();
		foreach ($adminTemplateModel->getAdminTemplatesByAddOn($addon) as $title => $template)
		{
			$output[] = new GeekPoint_AdminDAVi_File_AdminTemplate($addon, $title, $template);
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
		if (substr($title, -5) == '.html')
		{
			$title = substr($title, 0, -5);
		}

		/* @var $adminTemplateModel XenForo_Model_AdminTemplate */
		$adminTemplateModel = XenForo_Model::create('XenForo_Model_AdminTemplate');
		$template = $adminTemplateModel->getAdminTemplateByTitle($title);

		if (!$template || $template['addon_id'] != $this->_addonId)
		{
			throw new Sabre_DAV_Exception_FileNotFound('Template not found: ' . $title);
		}

		return new GeekPoint_AdminDAVi_File_AdminTemplate($this->_addonId, $title, $template);
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
		if (substr($title, -5) == '.html')
		{
			$title = substr($title, 0, -5);
		}

		$file = new GeekPoint_AdminDAVi_File_AdminTemplate($this->_addonId, $title);
		$file->put($data);
	}

	/**
	 * Returns the name of the node
	 *
	 * @return string
	 */
	public function getName()
	{
		return GeekPoint_AdminDAVi_Directory_Root::ADMIN_TEMPLATES;
	}
}