<?php namespace Lti\DotsubAPI\Service;

use Lti\DotsubAPI\Service;

class Service_Video extends Service
{
    // MANDATORY FIELDS
    // The title of this video.
    private $title;
    // A description of this video. This can not be more than 800 characters.
    private $description;
    // The source language of the video. This is the 3 letter ISO code that can
    // be found using the languages API call.
    private $language;
    // The ID of the license of this video. License ID's can be obtained from
    // our license API.
    private $license;
    // The file you are uploading. This should be included as part of a
    // multi-part post request.
    private $file;
    // A url to the file to be uploaded to DotSUB. This must be a url directly
    // to a video file not a webpage that contains a video.
    private $url;

    // OPTIONAL FIELDS
    // The country code of this video. Ex: CA, US, CZ
    private $country;
    // The genre of this video. This will default to none if omitted.
    private $genre;
    // The producer of this video.
    private $producer;
    // The director of this video.
    private $director;
    // This value determines if the video is publicly listed on DotSUB. This
    // value defaults to false.
    private $publicity;
    // This value determines if a video is listed on the public listing pages
    // (Latest, Most Viewed, Videos from User). Defaults to 'true'. This option
    // is only used if publicity is 'true'.
    private $publicallyViewable;
    // The external ID you wish to use for this video.
    private $externalIdentifier;
    // The ID of the project you want to post this video to. Please consult the
    // Project API to get a listing of the projects available to you.
    private $project;
    // This determines if a video will default to the user that is specified on
    // the project. Default 'true' if project is present.
    private $postToProjectAsDefaultUser;

    public function __construct($object, $isUpload)
    {
        if (is_object($object)) {
            $o = get_object_vars($object);
        } else {
            $o = $object;
        }
        foreach ($o as $key => $value) {
            if (!empty($value)) {
                $this->$key = $value;
            }
        }

        if ($isUpload) {
            if (!is_readable(str_replace("@", "", $this->file))) {
                throw new Service_Exception("A readable file must be given for upload.");
            }
            if (strpos($this->file, "@") === false) {
                $this->file = "@" . $this->file;
            }
            // Apparently this parameter is needed for a succesful upload.
            // @TODO Ask dotSUB about this, it's not in the documentation.
            $this->fileNotAttached = "false";

        }
    }

    public function getVars()
    {
        $o = get_object_vars($this);

        $ar = array();
        foreach ($o as $key => $value) {
            if (!empty($value)) {
                $ar[$key] = $value;
            }
        }
        return $ar;
    }

    public function setProject($projectId)
    {
        $this->project = $projectId;
    }
}