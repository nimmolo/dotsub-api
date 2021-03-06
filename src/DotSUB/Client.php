<?php namespace Lti\DotsubAPI;

use Lti\DotsubAPI\Http\Http_REST;
use Lti\DotsubAPI\IO\IO_ProgressMonitorInterface;

/**
 * Handles client configuration, which is handed to the request as needed.
 *
 *
 */
class Client
{
    /**
     * @var Config
     */
    private $config;
    private $io;
    private $hasCredentials = false;

    /**
     *
     * @param string $config
     */
    public function __construct($config = "")
    {
        if (!function_exists('curl_exec')) {
            trigger_error("This code needs CURL to handle HTTP Requests.", E_ERROR);
        }
        $this->config = new Config($config);
    }

    public function getClientCredentials()
    {
        return $this->config->getClientCredentials();
    }

    public function getClientUsername()
    {
        return $this->config->getClientUsername();
    }

    public function getIo()
    {
        if (!isset($this->io)) {
            $class = 'Lti\DotsubAPI\IO\\' . $this->config->getIoClass();
            $this->io = new $class($this->getProgressMonitor());
        }
        return $this->io;
    }

    public function setClientCredentials($username, $password)
    {
        $this->config->setClientCredentials($username, $password);
        $this->hasCredentials = true;
    }

    public function setProgressMonitor(IO_ProgressMonitorInterface $progressMonitor)
    {
        $this->config->setProgressMonitor($progressMonitor);
    }

    public function getClientProject()
    {
        return $this->config->getClientProject();
    }

    public function getProgressMonitor()
    {
        return $this->config->getProgressMonitor();
    }

    public function setClientProject($project)
    {
        $this->config->setClientProject($project);
    }

    public function execute($request, $format = true)
    {
        return Http_REST::execute($this, $request, $format);
    }

    public function hasCredentials()
    {
        $u = $this->config->getClientCredentials();
        return (!empty($u[0]) && !empty($u[1]));
    }

    public function getConfig(){
        return $this->config->getConfig();
    }
}