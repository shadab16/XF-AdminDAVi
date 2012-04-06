<?php

/**
 * Admin-template file.
 *
 * @author Shadab Ansari
 * @package GeekPoint_AdminDAVi
 */
class GeekPoint_AdminDAVi_File_AdminTemplate extends XenForo_SabreDav_File_AdminTemplate
{
	/**
	 * Addon ID corresponding to this directory
	 *
	 * @var string
	 */
	protected $_addonId;

	/**
	 * Construct admin-template for the given addon
	 *
	 * @param string $addonId
	 * @param string $title
	 * @param array $template
	 */
	public function __construct($addonId, $title, array $template = null)
	{
		parent::__construct($template, $title);
		$this->_addonId = strval($addonId);
	}

	/**
	 * Updates the data, supplied as a readable stream resource
	 *
	 * @see XenForo_SabreDav_File_AdminTemplate::put() Only one line changed in this overriden method
	 * @param resource $data
	 * @return void
	 */
	public function put($data)
	{
		if (!$this->_title || $this->_title[0] == '.' || $this->_title == 'Thumbs.db' || $this->_title == 'desktop.ini')
		{
			// don't save files that are likely temporary
			return;
		}

		$dw = XenForo_DataWriter::create('XenForo_DataWriter_AdminTemplate');
		if ($this->_template)
		{
			// only set this as the existing template if it truly exists in this style
			$dw->setExistingData($this->_template);
		}
		else
		{
			$dw->set('addon_id', $this->_addonId);
		}

		$dw->set('title', $this->_title);

		$properties = self::_getPropertiesInStyle(-1);

		$propertyChanges = self::_getPropertyModel()->translateEditorPropertiesToArray(
			stream_get_contents($data), $contents, $properties
		);
		$contents = self::_getTemplateModel()->replaceLinkRelWithIncludes($contents);

		$dw->set('template', $contents);

		XenForo_SabreDav_ErrorHandler::assertNoErrors($dw, 'save', 'Admin template');
		$dw->save();

		self::_getPropertyModel()->saveStylePropertiesInStyleFromTemplate(
			-1, $propertyChanges, $properties
		);
	}
}