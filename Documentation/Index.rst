..  Editor configuration
	...................................................
	* utf-8 with BOM as encoding
	* tab indent with 4 characters for code snippet.
	* optional: soft carriage return preferred.

.. Includes roles, substitutions, ...
.. include:: _IncludedDirectives.rst

=================
Extension Name
=================

:Extension name: Crop Images
:Extension key: onm_cropimages
:Version: 
:Description: manuals covering TYPO3 extension "Crop Images"
:Language: en
:Author: Thomas Prangenberg
:Creation: 2013-03-08
:Generation: 11:00
:Licence: Open Content License available from `www.opencontent.org/opl.shtml <http://www.opencontent.org/opl.shtml>`_

The content of this document is related to TYPO3, a GNU/GPL CMS/Framework available from `www.typo3.org
<http://www.typo3.org/>`_

**Table of Contents**

.. toctree::
	:maxdepth: 2

	ProjectInformation
	UserManual
	AdministratorManual
	DeveloperCorner
	RestructuredtextHelp

.. STILL TO ADD IN THIS DOCUMENT
	@todo: add section about how screenshots can be automated. Pointer to PhantomJS could be added.
	@todo: explain how documentation can be rendered locally and remotely.
	@todo: explain what files should be versionned and what not (_build, Makefile, conf.py, ...)


What does it do?
=================

This extension enables backend users to modify the visible section of all cropped images that have already been processed by the Typo3 frontend. 
This includes all images within the Typo3 virtual filesystem that are received via FAL and used as imgResource (for example by getting it via TS or the <f:image> Viewhelper in Fluid.)

PLEASE BE AWARE THAT THIS EXTENSION IS STILL CONSIDERED EXPERIMENTAL
Use in production environment is not recommended.
