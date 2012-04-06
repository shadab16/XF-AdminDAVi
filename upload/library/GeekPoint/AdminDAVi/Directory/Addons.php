<?php

/**
 * Addon-specific directory.
 *
 * @author Shadab Ansari
 * @package GeekPoint_AdminDAVi
 */
class GeekPoint_AdminDAVi_Directory_Addons extends Sabre_DAV_Directory
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
		return array(
			new GeekPoint_AdminDAVi_Directory_AdminTemplates($this->_addonId),
			new GeekPoint_AdminDAVi_Directory_PublicTemplates($this->_addonId),
			// new GeekPoint_AdminDAVi_Directory_Phrases($this->_addonId),
		);
	}

	/**
	 * Returns the name of the node
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->_addonId;
	}
}