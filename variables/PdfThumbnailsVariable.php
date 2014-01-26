<?php

namespace Craft;

/**
 * Pdf Thumbnails Variable
 */
class PdfThumbnailsVariable
{
    public function getUserImageUrl($assetID, $width = 200, $height = 200)
    {
        //Craft::log("JDR test logging", 'info', true, 'plugin'); 
        return UrlHelper::getResourceUrl('pdfthumb/'.$assetID.'/'.$width.'/'.$height.'/');
    }
}
