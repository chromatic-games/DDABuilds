<?php

// TODO CHECK IF UNUSED
/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 04.06.2017
 * Time: 19:11
 */
class ScreenshotlayerParser
{
    private $buildlink;

    /**
     * @return mixed
     */
    public function getBuildlink()
    {
        return $this->buildlink;
    }

    /**
     * @param mixed $buildlink
     */
    public function setBuildlink($buildlink)
    {
        $this->buildlink = $buildlink;
    }

    /**
     * @return bool|string
     */
    public function getScreenshotLink()
    {
        $handle = curl_init('https://screenshotlayer.com/');
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
        $html = curl_exec($handle);
        libxml_use_internal_errors(true); // Prevent HTML errors from displaying
        $doc = new DOMDocument();
        $doc->loadHTML($html);
        $finder = new DomXPath($doc);
        $name = "scl_request_secret";
        $nodes = $finder->query("//input[contains(concat(' ', normalize-space(@name), ' '), ' $name ')]");

        $scl_request_secret = $nodes->item(0)->attributes->item(2)->nodeValue;
        $hash = md5($this->buildlink . $scl_request_secret);
        $api_query = "https://screenshotlayer.com/php_helper_scripts/scl_api.php?secret_key=" . $hash . "&url=" . urlencode($this->buildlink);
        return $api_query;
    }
}