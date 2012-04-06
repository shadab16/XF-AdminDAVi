<?php

/**
 * Public-template file.
 *
 * @author Shadab Ansari
 * @package GeekPoint_AdminDAVi
 */
class GeekPoint_AdminDAVi_File_PublicTemplate extends XenForo_SabreDav_File_Template
{
	/**
	 * Addon ID corresponding to this directory
	 *
	 * @var string
	 */
	protected $_addonId;

	/**
	 * Construct public-template for the given addon+style set
	 *
	 * @param string $addon
	 * @param array $style
	 * @param string $title
	 * @param array $template
	 */
	public function __construct($addon, array $style, $title, array $template = null)
	{
		parent::__construct($template, $style, $title);
		$this->_addonId = strval($addon);
	}

	/**
	 * Updates the data, supplied as a readable stream resource
	 *
	 * @see XenForo_SabreDav_File_Template::put() Only one line changed in this overriden method
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

		$dw = XenForo_DataWriter::create('XenForo_DataWriter_Template');
		if ($this->_template && $this->_template['style_id'] == $this->_style['style_id'])
		{
			// only set this as the existing template if it truly exists in this style
			$dw->setExistingData($this->_template);
		}
		else
		{
			$dw->set('style_id', $this->_style['style_id']);
			$dw->set('addon_id', $this->_addonId);
		}

		$dw->set('title', $this->_title);

		$properties = self::_getPropertiesInStyle($this->_style['style_id']);

		$propertyChanges = self::_getPropertyModel()->translateEditorPropertiesToArray(
			stream_get_contents($data), $contents, $properties
		);
		$contents = self::_getTemplateModel()->replaceLinkRelWithIncludes($contents);
		$dw->set('template', $contents);

		if ($dw->isChanged('title') || $dw->isChanged('template') || $dw->get('style_id') > 0)
		{
			$dw->updateVersionId();
		}

		XenForo_SabreDav_ErrorHandler::assertNoErrors($dw, 'save', 'Public template');
		$dw->save();

		self::_getPropertyModel()->saveStylePropertiesInStyleFromTemplate(
			$this->_style['style_id'], $propertyChanges, $properties
		);
	}
}