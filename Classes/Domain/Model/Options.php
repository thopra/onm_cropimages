<?php
namespace ONM\OnmCropimages\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Thomas Prangenberg <tpb@onm.de>, ONM
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 *
 * @package onm_cropimages
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Options extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * processed width
	 *
	 * @var \integer
	 */
	protected $width;

	/**
	 * processed height
	 *
	 * @var \string
	 */
	protected $height;

	/**
	 * top left x-axis coordinate (percentage)
	 *
	 * @var \integer
	 * @validate NotEmpty
	 */
	protected $cropX;

	/**
	 * top left y-axis coordinate (percentage)
	 *
	 * @var \integer
	 */
	protected $cropY;

	/**
	 * width (percentage)
	 *
	 * @var \string
	 */
	protected $cropWidth;

	/**
	 * height (percentage)
	 *
	 * @var \string
	 */
	protected $cropHeight;

	/**
	 * file
	 *
	 * @var \integer
	 */
	protected $file;

	/**
	 * Returns the width
	 *
	 * @return \integer $width
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * Sets the width
	 *
	 * @param \integer $width
	 * @return void
	 */
	public function setWidth($width) {
		$this->width = $width;
	}

	/**
	 * Returns the height
	 *
	 * @return \string $height
	 */
	public function getHeight() {
		return $this->height;
	}

	/**
	 * Sets the height
	 *
	 * @param \string $height
	 * @return void
	 */
	public function setHeight($height) {
		$this->height = $height;
	}

	/**
	 * Returns the cropX
	 *
	 * @return \integer $cropX
	 */
	public function getCropX() {
		return $this->cropX;
	}

	/**
	 * Sets the cropX
	 *
	 * @param \integer $cropX
	 * @return void
	 */
	public function setCropX($cropX) {
		$this->cropX = $cropX;
	}

	/**
	 * Returns the cropY
	 *
	 * @return \integer $cropY
	 */
	public function getCropY() {
		return $this->cropY;
	}

	/**
	 * Sets the cropY
	 *
	 * @param \integer $cropY
	 * @return void
	 */
	public function setCropY($cropY) {
		$this->cropY = $cropY;
	}

	/**
	 * Returns the cropWidth
	 *
	 * @return \string $cropWidth
	 */
	public function getCropWidth() {
		return $this->cropWidth;
	}

	/**
	 * Sets the cropWidth
	 *
	 * @param \string $cropWidth
	 * @return void
	 */
	public function setCropWidth($cropWidth) {
		$this->cropWidth = $cropWidth;
	}

	/**
	 * Returns the cropHeight
	 *
	 * @return \string $cropHeight
	 */
	public function getCropHeight() {
		return $this->cropHeight;
	}

	/**
	 * Sets the cropHeight
	 *
	 * @param \string $cropHeight
	 * @return void
	 */
	public function setCropHeight($cropHeight) {
		$this->cropHeight = $cropHeight;
	}

	/**
	 * Returns the file
	 *
	 * @return \integer
	 */
	public function getFile() {
		return $this->file;
	}

	/**
	 * Sets the file
	 *
	 * @param \integer $file
	 * @return void
	 */
	public function setFile($file) {
		$this->file = $file;
	}

	/**
	 * Get Typoscript Width Value
	 *
	 * @return string
	 */
	public function getTSValueWidth()
	{
		$tsValue =  $this->getWidth();

		$sign = 'c';
		if ( $this->getCropX() != 0 ) {
			if ( $this->getCropX() > 0) {
				$sign .= '+';
			}
			$tsValue .= $sign . $this->getCropX();
		} else {
			$tsValue .= $sign;
		}

		return $tsValue;
	}

	/**
	 * Get Typoscript Height Value
	 *
	 * @return string
	 */
	public function getTSValueHeight()
	{
		$tsValue =  $this->getHeight();

		$sign = 'c';
		if ( $this->getCropY() != 0 ) {
			if ( $this->getCropY() > 0) {
				$sign .= '+';
			}
			$tsValue .= $sign . $this->getCropY();
		} else {
			$tsValue .= $sign;
		}

		return $tsValue;
	}

}
?>