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
        return 'http://github.com/johndryan';
    }


    /**
     * Adds support for PDF rewrite of resource paths.
     *
     * @param string $path
     * @return string|null
     */
    public function getResourcePath($path)
    {
        // TODO: Make variables editable via CMS?
        $urlFlag = 'pdfthumb/';
        $thumbsDirName = '_thumbs';

        // Are they requesting a PDF thumbnail?
        if (strncmp($path, $urlFlag, strlen($urlFlag)) === 0)
        {
            //Explode the path url
            $parts = array_merge(array_filter(explode('/', $path)));

            //Is it long enough? If not, exit
            if (count($parts) < 2)
            {
                return;
            }

            // TODO: assume default if relevant parts aren't numbers
            $assetId = $parts[1];
            if (count($parts) > 2) $height = $parts[2];
            if (count($parts) > 3) $width = $parts[3];
            if (count($parts) > 4) $whichPdfPage = $parts[4];
            if (count($parts) > 5) $destFiletype = $parts[5];

            if (!isset($height) || !is_numeric($height)) $height = 320;
            if (!isset($width) || !is_numeric($width)) $width = 240;
            if (!isset($whichPdfPage) || !is_numeric($whichPdfPage)) $whichPdfPage = 1;
            if (!isset($destFiletype) || $destFiletype != ('jpg' || 'jpeg' || 'gif' || 'png')) $destFiletype = 'jpg';

            $whichPdfPage -= 1;
            $dimensions = $width .'x'. $height;

            // GET THE PDF
            //TODO: add error handling if none found

            $sourcePdf = craft() -> assets -> getFileById($assetId);

            //Only proceed if it's a PDF
            if ($sourcePdf->kind != 'pdf')
            {
                // TODO: add default PDF icon thumbnail if can't process? 
                return;
            }

            $assetName = $sourcePdf->filename;
            $sourceFolder = craft() -> assets -> getFolderById($sourcePdf->folderId);
            // TODO: More robust way to get CRAFT_BASE_PATH
            $webRootPath = $_SERVER['DOCUMENT_ROOT'];
            $sourceUrl = craft() -> assets -> getUrlForFile($sourcePdf);
            $sourceFullPath = $webRootPath.$sourceUrl;
            //$sourceFullPath = IOHelper::getRealPath($sourceUrl);
            // Does the source file exist?
            if (IOHelper::fileExists($sourceFullPath) == false)
            {
                Craft::log("Uh-oh, your source file doesn't exist!", 'error', true, 'plugin');
                return;
            }
            $sourcePath = str_replace($assetName,"",$sourceFullPath);
            $destFilename = str_replace('.', '_', $assetName).'_'.$dimensions.'_thumb.'.$destFiletype;
            $destFullPath = $sourcePath.$thumbsDirName.'/'.$destFilename;

            // Have we already resized this image at this size?
            $destFullPath;

            if (IOHelper::fileExists($destFullPath) != false)
            {
                // Yup, we already have it. Serve it up
                return $destFullPath;
            }
            else
            {
                // LET'S MAKE THE THUMB
                // Make the thumbs directory if it doesn't already exist
                IOHelper::ensureFolderExists($sourcePath.$thumbsDirName);

                //Create the thumbnail with Imagick
                try
                {
                    $tempImg = new \Imagick();
                    $tempImg->setResolution( 72, 72 );
                    $tempImg->readImage( $sourceFullPath.'['.$whichPdfPage.']' );
                    $tempImg->cropThumbnailImage( $width, $height );
                    $tempImg = $tempImg->flattenImages();
                    //$tempImg->adaptiveSharpenImage(2,1);
                    $tempImg->writeImage($destFullPath);
                }
                catch(ImagickException $e)
                {
                    Craft::log('Imagick Error: '.$e->getMessage(), 'error', true, 'plugin');
                    return;
                }

                return $destFullPath;
            }
        }
    }

}
