<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Controller for the API REST
 * Heavily based on https://github.com/SupersonicAds/kohana-restful-api/
 * 
 * Abstract Controller class for RESTful controller mapping. Supports GET, PUT,
 * POST, and DELETE. By default, these methods will be mapped to these actions:
 *
 * GET
 * :  Mapped to the "index" action, lists all objects
 *
 * POST
 * :  Mapped to the "create" action, creates a new object
 *
 * PUT
 * :  Mapped to the "update" action, update an existing object
 *
 * DELETE
 * :  Mapped to the "delete" action, delete an existing object
 *
 * Additional methods can be supported by adding the method and action to
 * the `$_action_map` property.
 * 
 * @author      Chema <chema@open-classifieds.com>
 * @package     OC
 * @copyright   (c) 2009-2015 Open Classifieds Team
 * @license     GPL v3
 * *
 */

class Api_Controller extends Kohana_Controller {

    /**
     * REST types
     *
     * @var array
     */
    protected $_action_map = array
    (
        HTTP_Request::GET    => 'index',
        HTTP_Request::PUT    => 'update',
        HTTP_Request::POST   => 'create',
        HTTP_Request::DELETE => 'delete',
    );

    /**
     * Should non-200 response codes be suppressed.
     * @see https://blog.apigee.com/detail/restful_api_design_tips_for_handling_exceptional_behavior
     *
     * @var boolean
     */
    protected $_suppress_response_codes;

    /**
     * The output format to be used (JSON, XML etc.).
     *
     * @var string
     */
    public $output_format;

    /**
     * The request's parameters.
     *
     * @var array
     */
    protected $_params;


    public function __construct($request, $response)
    {
        parent::__construct($request, $response);

    }

    /**
     * Checks the requested method against the available methods. If the method
     * is supported, sets the request action from the map. If not supported,
     * and an alternative action wasn't set, the "invalid" action will be called.
     */
    public function before()
    {
        $this->_overwrite_method();
        $method = $this->request->method();
        $action_requested = $this->request->action();

        //nasty trick to swap action for the ID, so we can use just 1 route.
        if (is_numeric($action_requested) AND !method_exists($this,'action_'.$action_requested) )
        {
            $this->request->set_param('id',$action_requested);
            $action_requested = FALSE;
        }
            
        $this->_init_params();

        // Get output format from route file extension.
        $this->output_format = $this->request->param('format');

        // Set response code suppressing.
        $this->_suppress_response_codes = isset($this->_params['suppressResponseCodes']) && 'true' === $this->_params['suppressResponseCodes'];

        //in case we pass the action via url Leave the action as is.
        // This enables support for arbitrary non-REST actions.
        if ($action_requested AND $action_requested !== 'index')
        {
            // 404 action doesnt exists!
            if (!method_exists($this,'action_'.$action_requested))
                $this->error(__('Method not found ').$action_requested,404);
        }
        //method doesnt exists
        elseif (!isset($this->_action_map[$method]))
        {
            $this->request->action('invalid');
        }
        //map the method into an action
        else
        {
            $this->request->action($this->_action_map[$method]);
        }

    }

    /**
     * Adds a cache control header.
     */
    public function after()
    {
        if (in_array($this->request->method(), array
        (
            HTTP_Request::PUT,
            HTTP_Request::POST,
            HTTP_Request::DELETE
        )))
        {
            $this->response->headers('cache-control', 'no-cache, no-store, max-age=0, must-revalidate');
        }
    }

    /**
     * Handling of output data set in action methods with $this->rest_output($data).
     *
     * @param array|object $data
     * @param int $code
     */
    protected function rest_output($data = array(), $code = 200)
    {        
        // Handle an empty and valid response.
        if (empty($data) AND 200 == $code)
        {
            $data = array
            (
                'code'  => 404,
                'error' => 'No records found',
            );
            $code = 404;
        }

        if ($this->_suppress_response_codes)
        {
            $this->response->status(200);
            $data['responseCode'] = $code;
        }
        else
        {
            $this->response->status($code);
        }

        $mime = File::mime_by_ext($this->output_format);

        $format_method = '_format_' . $this->output_format;
        // If the format method exists, call and return the output in that format
        if (method_exists($this, $format_method))
        {
            $output_data = $this->$format_method($data);
            $this->response->headers('content-type', File::mime_by_ext($this->output_format));
            $this->response->headers('content-length', (string) strlen($output_data));

            // Support attachment header
            if (isset($this->_params['attachment']) && Valid::regex($this->_params['attachment'], '/^[-\pL\pN_, ]++$/uD'))
            {
                $this->response->headers('content-disposition', 'attachment; filename='. $this->_params['attachment'] .'.'. $this->output_format);
            }

            $this->response->body($output_data);
        }
        else
        {
            // Report an error.
            $this->response->status(500);
            throw new Kohana_Exception('Unknown format method requested');
        }
    }

    /**
     * Format the output data to JSON.
     */
    private function _format_json($data = array())
    {
        // Support JSONP requests.
        if ( ($callback = $this->request->query('callback')) && 200 == $this->response->status())
        {
            return $callback .'('. json_encode($data) .')';
        }
        else
        {
            return json_encode($data);
        }
    }

    /**
     * Format the output data to XML.
     * @TODO Improve this implementation (or maybe not, because XML is dead).
     */
    private function _format_xml($data = array())
    {
        return Arr::to_xml($data, new SimpleXMLElement('<root/>'), 'item');
    }

    /**
     * Format the output data to CSV.
     * Requires the data to be a 2-dimensional array.
     * 1-dimension arrays are also supported, by converting them to 2-dimensions.
     *
     * @TODO This doesn't really work well with arrays, requires deeper inspection.
     */
    private function _format_csv($data = array())
    {
        $contents = '';

        if (!empty($data))
        {
            // Create a title row. Support 1-dimension arrays.
            $first_row = reset($data);
            if (is_array($first_row))
            {
                $titles = array_keys($first_row);
            }
            else
            {
                $titles = array_keys($data);
            }
            array_unshift($data, $titles);

            $handle = fopen('php://temp', 'r+');
            foreach ($data as $line)
            {
                fputcsv($handle, (array) $line);
            }
            rewind($handle);
            while (!feof($handle))
            {
                $contents .= fread($handle, 8192);
            }
            fclose($handle);
        }

        return $contents;
    }

    /**
     * Call a View to format the data as HTML.
     */
    private function _format_html($data = array())
    {
        // Support a fallback View for errors.
        if (isset($data['error']))
        {
            $data['responseCode'] = $this->response->status();
            $view_name = 'error';
        }
        else
        {
            $view_name = strtolower($this->request->directory());
            if ($view_name) $view_name .= '/';
            $view_name .= strtolower($this->request->controller() .'/'. $this->request->action());
        }

        try
        {
            return (string) View::factory($view_name, array('data' => $data));
        }
        catch (View_Exception $e)
        {
            // Fall back to an empty string.
            // This way we don't have to satisfy *all* API requests as HTML.
            return "<pre>".print_r($data,1)."</pre>";
        }
    }


    /**
     * Implements support for setting the request method via a GET parameter.
     * @see https://blog.apigee.com/detail/restful_api_design_tips_for_handling_exceptional_behavior
     */
    private function _overwrite_method()
    {
        if (HTTP_Request::GET == $this->request->method() && ($method = $this->request->query('method')))
        {
            switch (strtoupper($method))
            {
                case HTTP_Request::POST:
                case HTTP_Request::PUT:
                case HTTP_Request::DELETE:
                    $this->request->method($method);
                    break;

                default:
                    break;
            }
        }
        else
        {
            // Try fetching method from HTTP_X_HTTP_METHOD_OVERRIDE before falling back on the detected method.
            $this->request->method( Arr::get($_SERVER, 'HTTP_X_HTTP_METHOD_OVERRIDE', $this->request->method()) );
        }
    }


    /**
     * Initializes the request params array based on the current request.
     * @TODO support other exotic methods.
     */
    private function _init_params()
    {
        $this->_params = array();

        switch ($this->request->method())
        {
            case HTTP_Request::POST:
            case HTTP_Request::PUT:
            case HTTP_Request::DELETE:
                if (isset($_SERVER['CONTENT_TYPE']) && false !== strpos($_SERVER['CONTENT_TYPE'], 'application/json'))
                {
                    $parsed_body = json_decode($this->request->body(), true);
                }
                else
                {
                    parse_str($this->request->body(), $parsed_body);
                }
                $this->_params = array_merge((array) $parsed_body, (array) $this->request->post());
                $this->request->post($this->_params);

                // No break because all methods should support query parameters by default.
            case HTTP_Request::GET:
                $this->_params = array_merge((array) $this->request->query(), $this->_params);
                $this->request->query($this->_params);
                break;

            default:
                break;
        }
    }

    
    /**
     * Generate an error message.
     *
     * @param string|Exception $exception
     * @param int $code
     */
    protected function _error($exception, $code = 0)
    {
        if (is_a($exception, 'Exception'))
        {
            $message = $exception->getMessage();
            $code = $exception->getCode();
            // Fetch field from HTTP Exceptions.
            $field = method_exists($exception, 'getField') ? $exception->getField() : null;
        }
        else
        {
            $message = (string) $exception;
            $field = null;
        }

        // Support fallback on default HTTP error messages.
        if (!$message && isset(Response::$messages[$code]))
        {
            $message = Response::$messages[$code];
        }

        $output = array
        (
            'code'  => $code,
            'error' => $message,
        );
        if ($field)
        {
            $output['field'] = $field;
        }
        $this->rest_output($output, $code);

        // This is here just to avoid going to the real action when the error is in before().
        // @TODO find a better solution.
        $this->request->action('error');
    }

    /**
     * See comment in _error().
     */
    public function action_error() {}

    /**
     * Sends a 405 "Method Not Allowed" response and a list of allowed actions.
     */
    public function action_invalid()
    {
        // Send the "Method Not Allowed" response
        $this->response->status(405)
            ->headers('Allow', implode(', ', array_keys($this->_action_map)));
    }


} // End api