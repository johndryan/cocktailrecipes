<?php

/**
 * PDF Thumbnail Generator for Craft CMS
 *
 * @package   PDF Thumbnails
 * @author    John Ryan
 * @link      https://github.com/johndryan/pdfthumbnails
 */

namespace Craft;

class PdfThumbnailsPlugin extends BasePlugin
{
    public function getName()
    {
        return Craft::t('PDF Thumbnails');
    }

    public function getVersion()
    {
        return '0.1';
    }

    public function getDeveloper()
    {
        return 'John Ryan';
    }

    public function getDeveloperUrl()
    {
        return 'http://johndryan.me';
    }
}
