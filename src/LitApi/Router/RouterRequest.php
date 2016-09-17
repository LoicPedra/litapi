<?php

namespace Router;

class RouterRequest
{

    private $args = array();

    private $request_method;

    private $data;

    private $url;

    /**
     * RouterRequest constructor.
     * @param array $args
     * @param $type
     */
    public function __construct(array $args, $type)
    {
        $this->args = $args;
        $this->request_method = $type;

        $this->url = "/" . $_GET['url'];

        switch ($this->request_method)
        {
            case "GET":
                unset($_GET['url']);
                $this->data = $_GET;
                break;
            case "POST":
                $this->data = $_POST;
                break;
            default:
                parse_str(file_get_contents("php://input"), $vars);
                $this->data = $vars;
                break;

        }
    }

    /**
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * @return mixed
     */
    public function getRequestMethod()
    {
        return $this->request_method;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

}

?>