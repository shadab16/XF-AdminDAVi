<?php

/**
 * Root directory.
 *
 * @author Shadab Ansari
 * @package GeekPoint_AdminDAVi
 */
class GeekPoint_AdminDAVi_Directory_Root extends Sabre_DAV_Directory
{
	const PHRASES          = 'Phrases';
	const PUBLIC_TEMPLATES = 'Public_Templates';
	const ADMIN_TEMPLATES  = 'Admin_Templates';

	/**
	 * Returns an array with all the child nodes
	 *
	 * @return Sabre_DAV_INode[]
	 */
	public function getChildren()
	{
		/* @var $addonModel XenForo_Model_AddOn */
		$addonModel = XenForo_Model::create('XenForo_Model_AddOn');
		$output = array();

		foreach ($addonModel->getAllAddOns() as $addon)
		{
			$output[] = new GeekPoint_AdminDAVi_Directory_Addons($addon['addon_id']);
		}

		$output[] = new GeekPoint_AdminDAVi_Directory_Addons('XenForo');

		return $output;
	}

	/**
	 * Returns the name of the node
	 *
	 * @return string
	 */
	public function getName()
	{
		return '';
	}
}