<?php

/**
 * Phrase file.
 *
 * @author Shadab Ansari
 * @package GeekPoint_AdminDAVi
 */
class GeekPoint_AdminDAVi_File_Phrase extends Sabre_DAV_File
{
	/**
	 * Addon ID corresponding to this directory
	 *
	 * @var string
	 */
	protected $_addonId;

	/**
	 * Phrase title
	 *
	 * @var string
	 */
	protected $_title;

	/**
	 * Complete phrase record
	 *
	 * @var array
	 */
	protected $_phrase = null;

	/**
	 * Phrase text
	 *
	 * @var string
	 */
	protected $_phraseText = null;

	/**
	 * Construct phrase for the given addon
	 *
	 * @param string $addonId
	 * @param string $title
	 * @param array $phrase
	 */
	public function __construct($addonId, $title, array $phrase = null)
	{
		$this->_addonId = strval($addonId);

		if ($phrase)
		{
			$this->_phrase = $phrase;
			$this->_title = $phrase['title'];
		}
		else
		{
			$this->_title = $title;
		}
	}

	/**
	 * Returns the name of the node
	 *
	 * @return string
	 */
	public function getName()
	{
		if ($this->_phrase && $this->_phrase['global_cache'])
		{
			return $this->_title . '.global.txt';
		}

		return $this->_title . '.txt';
	}

	/**
	 * Returns the last modification time
	 *
	 * @return int
	 */
	public function getLastModified()
	{
		return 0;
	}

	/**
	 * Returns the ETag for a file
	 *
	 * An ETag is a unique identifier representing the current version of the file.
	 * If the file changes, the ETag MUST change.
	 *
	 * @return string
	 */
	public function getETag()
	{
		$phraseText = $this->_getPhraseText();
		if ($phraseText === false)
		{
			return 'new';
		}
		else
		{
			return md5($phraseText);
		}
	}

	/**
	 * Returns the data
	 *
	 * @return string
	 */
	public function get()
	{
		$phraseText = $this->_getPhraseText();
		if ($phraseText === false)
		{
			return '';
		}
		else
		{
			return $phraseText;
		}
	}

	/**
	 * Returns the size of the file, in bytes.
	 *
	 * @return int
	 */
	public function getSize()
	{
		$phraseText = $this->_getPhraseText();
		if ($phraseText === false)
		{
			return 0;
		}
		else
		{
			return strlen($phraseText);
		}
	}

	/**
	 * Returns the mime-type for a file
	 *
	 * @return string
	 */
	public function getContentType()
	{
		return 'text/plain';
	}

	/**
	 * Returns the phrase text
	 *
	 * @return string|false
	 */
	protected function _getPhraseText()
	{
		if ($this->_phraseText !== null)
		{
			return $this->_phraseText;
		}

		if (!$this->_phrase)
		{
			$this->_phraseText = false;
		}
		else
		{
			$this->_phraseText = $this->_phrase['phrase_text'];
		}

		return $this->_phraseText;
	}
}