<?php
/**
 * AnalyticsClient provides the PHP based language binding to the https based API of ZohoAnalytics.
 */

namespace App\Helpers\Zoho\ZohoAnalyticsPHPClient;
class AnalyticsClient
{
    /**
     * @var const CLIENT_VERSION It contain the API client version.It is a constant one.
     */
    const CLIENT_VERSION = "2.5.0";
    /**
     * @var string $analytics_server_url The base request API URL.
     */
    private $analytics_server_url = "https://analyticsapi.zoho.eu";
    /**
     * @var string $accounts_server_url Account URL.
     */
    private $accounts_server_url = "https://accounts.zoho.eu";
    /**
     * @var boolean $proxy It will indicate whether the proxy is set or not.
     */
    private $proxy = FALSE;
    /**
     * @var string $proxy_host The hostname/ip address of the proxy-server.
     */
    private $proxy_host;
    /**
     * @var int $proxy_port The proxy server port.
     */
    private $proxy_port;
    /**
     * @var string $proxy_user_name The user name for proxy-server authentication.
     */
    private $proxy_user_name;
    /**
     * @var string $proxy_password The password for proxy-server authentication.
     */
    private $proxy_password;
    /**
     * @var string $proxy_type Can be any one ( HTTP , HTTPS , BOTH ).Specify "BOTH" if same configuration can be used for both HTTP and HTTPS.
     */
    private $proxy_type;
    /**
     * @var int $connection_timeout It is a time value until a connection is established.
     */
    private $connection_timeout;
    /**
     * @var int $read_timeout It is a time value until waiting to read data.
     */
    private $read_timeout;

    /**
     * @internal The client ID string that has been obtained during the client registration.
     */
    private $client_id;

    /**
     * @internal The client secret string that has been obtained during the client registration.
     */
    private $client_secret;

    /**
     * @internal The refresh token string that has been obtained through accounts API
     */
    private $refresh_token;

    /**
     * @internal The access token string that has been obtained through accounts API
     */
    private $access_token;

    /**
     * Creates a new Zoho AnalyticsClient instance.
     * @param string $client_id Client id of the user.
     * @param string $client_secret Client secret of the user.
     * @param string $refresh_token Refresh token of the user.
     * <a href="https://www.zoho.com/analytics/api/#oauth" target="_blank">click here</a> to know about OAuth.
     * @link
     */
    function __construct($client_id, $client_secret, $refresh_token) {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->refresh_token = $refresh_token;
    }

    /**
     * Returns a new OrgAPI instance.
     * @param string $org_id The ID of the organization.
     * @return OrgAPI instance.
     */
    function getOrgInstance($org_id) {
        return new OrgAPI($this, $org_id);
    }

    /**
     * Returns a new WorkspaceAPI instance.
     * @param string $org_id The ID of the organization.
     * @param string $workspace_id The ID of the workspace.
     * @return WorkspaceAPI instance.
     */
    function getWorkspaceInstance($org_id, $workspace_id) {
        return new WorkspaceAPI($this, $org_id, $workspace_id);
    }

    /**
     * Returns a new ViewAPI instance.
     * @param string $org_id The ID of the organization.
     * @param string $workspace_id The ID of the workspace.
     * @param string $view_id The ID of the view.
     * @return ViewAPI instance.
     */
    function getViewInstance($org_id, $workspace_id, $view_id) {
        return new ViewAPI($this, $org_id, $workspace_id, $view_id);
    }

    /**
     * Returns a new BulkAPI instance.
     * @param string $org_id The ID of the organization.
     * @param string $workspace_id The ID of the workspace.
     * @return BulkAPI instance.
     */
    function getBulkInstance($org_id, $workspace_id) {
        return new BulkAPI($this, $org_id, $workspace_id);
    }

    /**
     * Returns list of all accessible organizations.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Organization list.
     */
    function getOrgs() {
        $endpoint = "/restapi/v2/orgs";
        $response = $this->sendAPIRequest("GET", $endpoint, NULL, NULL);
        return $response["data"]["orgs"];
    }

    /**
     * Returns list of all accessible workspaces.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Workspace list.
     */
    function getWorkspaces() {
        $endpoint = "/restapi/v2/workspaces";
        $response = $this->sendAPIRequest("GET", $endpoint, NULL, NULL);
        return $response["data"];
    }

    /**
     * Returns list of owned workspaces.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Workspace list.
     */
    function getOwnedWorkspaces() {
        $endpoint = "/restapi/v2/workspaces/owned";
        $response = $this->sendAPIRequest("GET", $endpoint, NULL, NULL);
        return $response["data"]["workspaces"];
    }

    /**
     * Returns list of shared workspaces.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Workspace list.
     */
    function getSharedWorkspaces() {
        $endpoint = "/restapi/v2/workspaces/shared";
        $response = $this->sendAPIRequest("GET", $endpoint, NULL, NULL);
        return $response["data"]["workspaces"];
    }

    /**
     * Returns list of recently accessed views.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() View list.
     */
    function getRecentViews() {
        $endpoint = "/restapi/v2/recentviews";
        $response = $this->sendAPIRequest("GET", $endpoint, NULL, NULL);
        return $response["data"]["views"];
    }

    /**
     * Returns list of all accessible dashboards.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Dashboard list.
     */
    function getDashboards() {
        $endpoint = "/restapi/v2/dashboards";
        $response = $this->sendAPIRequest("GET", $endpoint, NULL, NULL);
        return $response["data"];
    }

    /**
     * Returns list of owned dashboards.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Dashboard list.
     */
    function getOwnedDashboards() {
        $endpoint = "/restapi/v2/dashboards/owned";
        $response = $this->sendAPIRequest("GET", $endpoint, NULL, NULL);
        return $response["data"]["views"];
    }

    /**
     * Returns list of shared dashboards.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Dashboard list.
     */
    function getSharedDashboards() {
        $endpoint = "/restapi/v2/dashboards/shared";
        $response = $this->sendAPIRequest("GET", $endpoint, NULL, NULL);
        return $response["data"]["views"];
    }

    /**
     * Returns details of the specified workspace.
     * @param string $workspace_id The ID of the workspace.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Workspace details.
     */
    function getWorkspaceDetails($workspace_id) {
        $endpoint = "/restapi/v2/workspaces/" . $workspace_id;
        $response = $this->sendAPIRequest("GET", $endpoint, NULL, NULL);
        return $response["data"]["workspaces"];
    }

    /**
     * Returns details of the specified view.
     * @param string $view_id The ID of the view.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() View details.
     */
    function getViewDetails($view_id, $config = array()) {
        $endpoint = "/restapi/v2/views/" . $view_id;
        $response = $this->sendAPIRequest("GET", $endpoint, NULL, NULL);
        return $response["data"]["views"];
    }

    /**
     * Returns the timeout until a connection is established.A value of zero means the timeout is not used.
     * @return int Connection timeout limit.
     */
    function getConnectionTimeout() {
        return $this->connection_timeout;
    }

    /**
     * Sets the timeout until a connection is established. A value of zero means the timeout is not used. The default value is 15000.
     * @param int $time_limit An integer value.
     */
    function setConnectionTimeout($time_limit) {
        $this->connection_timeout = $time_limit;
    }

    /**
     * Returns the timeout until waiting to read data. A value of zero means the timeout is not used. The default value is 15000.
     * @return int Read timeout limit.
     */
    function getReadTimeout() {
        return $this->read_timeout;
    }

    /**
     * Sets the timeout until waiting to read data. A value of zero means the timeout is not used. The default value is 15000.
     * @param int $time_limit An integer value.
     */
    function setReadTimeout($time_limit) {
        $this->read_timeout = $time_limit;
    }

    /**
     * Used to specify the proxy server details.
     * @param string $proxy_host The hostname/ip address of the proxy-server.
     * @param int $proxy_port The proxy server port.
     * @param string $proxy_type Can be any one ( HTTP , HTTPS , BOTH ).Specify "BOTH" if same configuration can be used for both HTTP and HTTPS.
     * @param string $proxy_user_name The user name for proxy-server authentication.
     * @param string $proxy_password The password for proxy-server authentication.
     */
    function setProxy($proxy_host, $proxy_port, $proxy_type, $proxy_user_name, $proxy_password) {
        $this->proxy = TRUE;
        $this->proxy_host = $proxy_host;
        $this->proxy_port = $proxy_port;
        $this->proxy_user_name = $proxy_user_name;
        $this->proxy_password = $proxy_password;
        $this->proxy_type = $proxy_type;
    }


    /**
     * @internal Send batch import API request and get response from the server.
     */
    protected function sendBatchImportAPIRequest($endpoint, $file_path, $batch_size, $config, $request_headers)
    {
        $post_fields = array();
        $file = file_get_contents($file_path);
        $file_content = explode(PHP_EOL, $file);
        $file_header = $file_content[0];
        $total_lines = count($file_content);
        $total_batch_count = ceil($total_lines/$batch_size);
        $config["batchKey"] = "start";

        for ($i = 0; $i < $total_batch_count; $i++)
        {
            $batch = array_merge(array($file_header), array_slice( $file_content, ($batch_size * $i)+1, $batch_size) );
            $config["isLastBatch"] = ($i==($total_batch_count - 1)) ? "true" : "false";

            $temp_file = tmpfile();
            fwrite($temp_file, implode(PHP_EOL, $batch));
            $post_fields['FILE'] = new CURLFile(stream_get_meta_data($temp_file)['uri']);
            $post_fields["CONFIG"] = json_encode($config);

            $request_url = $this->analytics_server_url . $endpoint;

            if($this->access_token == null) {
                $this->regenerateAnalyticsOAuthToken();
            }

            $respArr = $this->submitImportRequest("POST", $request_url, $request_headers, $this->access_token, $post_fields);

            $resp_code = $respArr["statusCode"];
            $resp_content = $respArr["response"];

            if(substr($resp_code, 0, 1) != '2' && $resp_content != FALSE && $this->isOAuthExpired($resp_content)) {
                $this->regenerateAnalyticsOAuthToken();
                $respArr = $this->submitImportRequest("POST", $request_url, $request_headers, $this->access_token, $post_fields);
                $resp_code = $respArr["statusCode"];
                $resp_content = $respArr["response"];
            }

            if(substr($resp_code, 0, 1) != '2') {
                throw new ServerException($resp_content, FALSE);
            }

            //For success - no response case
            if($resp_content == FALSE) {
                return;
            }


            $json_response = json_decode($resp_content, TRUE);
            if(json_last_error() != JSON_ERROR_NONE) {
                $resp_content = stripslashes($resp_content);
                $json_response = json_decode($resp_content, TRUE);
            }
            if(json_last_error()) {
                throw new ParseException("Returned JSON format is not proper. Could possibly be version mismatch");
            }

            $response = $json_response;
            $config["batchKey"] = $response["data"]["batchKey"];
            sleep(2);
        }

        return $response;
    }

    /**
     * @internal Send import API request and get response from the server.
     */
    protected function sendImportAPIRequest($endpoint, $post_fields, $request_headers) {
        $request_url = $this->analytics_server_url . $endpoint;
        $response = NULL;

        if($this->access_token == null) {
            $this->regenerateAnalyticsOAuthToken();
        }

        $respArr = $this->submitImportRequest("POST", $request_url, $request_headers, $this->access_token, $post_fields);

        $resp_code = $respArr["statusCode"];
        $resp_content = $respArr["response"];

        if(substr($resp_code, 0, 1) != '2' && $resp_content != FALSE && $this->isOAuthExpired($resp_content)) {
            $this->regenerateAnalyticsOAuthToken();
            $respArr = $this->submitImportRequest("POST", $request_url, $request_headers, $this->access_token, $post_fields);
            $resp_code = $respArr["statusCode"];
            $resp_content = $respArr["response"];
        }

        if(substr($resp_code, 0, 1) != '2') {
            throw new ServerException($resp_content, FALSE);
        }

        //For success - no response case
        if($resp_content == FALSE) {
            return;
        }


        $json_response = json_decode($resp_content, TRUE);
        if(json_last_error() != JSON_ERROR_NONE) {
            $resp_content = stripslashes($resp_content);
            $json_response = json_decode($resp_content, TRUE);
        }
        if(json_last_error()) {
            throw new ParseException("Returned JSON format is not proper. Could possibly be version mismatch");
        }

        return $json_response;
    }

    /**
     * @internal Send import API request and get response from the server.
     */
    protected function submitImportRequest($request_method, $request_uri, $request_headers, $access_token = NULL, $post_fields = NULL) {

        if($access_token != NULL) {
            $request_headers[]  = "Authorization: Zoho-oauthtoken " . $access_token;
        }
        $request_headers[] = "User-Agent: Analytics PHP Client v" . self::CLIENT_VERSION;

        $HTTP_request = curl_init();
        curl_setopt($HTTP_request, CURLOPT_URL, $request_uri);
        curl_setopt($HTTP_request, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($HTTP_request, CURLOPT_FOLLOWLOCATION, TRUE);

        //Setting request method
        curl_setopt($HTTP_request, CURLOPT_CUSTOMREQUEST, $request_method);

        //Setting request header
        curl_setopt($HTTP_request, CURLOPT_HTTPHEADER, $request_headers);

        //Setting connection and read timeout
        curl_setopt($HTTP_request, CURLOPT_CONNECTTIMEOUT, $this->connection_timeout);
        curl_setopt($HTTP_request, CURLOPT_TIMEOUT, $this->read_timeout);

        //Adding post fileds data
        if($post_fields != NULL) {
            curl_setopt($HTTP_request, CURLOPT_POST, 1);
            curl_setopt($HTTP_request, CURLOPT_POSTFIELDS, $post_fields);
        }

        //Setting proxy configuration
        if($this->proxy == TRUE) {
            curl_setopt($HTTP_request, CURLOPT_PROXY, $this->proxy_host);
            curl_setopt($HTTP_request, CURLOPT_PROXYTYPE, $this->proxy_type);
            curl_setopt($HTTP_request, CURLOPT_PROXYPORT, $this->proxy_port);
            curl_setopt($HTTP_request, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
            curl_setopt($HTTP_request, CURLOPT_PROXYUSERPWD, "$this->proxy_user_name:$this->proxy_password");
        }

        $HTTP_response = curl_exec($HTTP_request);
        $HTTP_status_code = curl_getinfo($HTTP_request, CURLINFO_HTTP_CODE);

        if($HTTP_response == FALSE && substr($HTTP_status_code, 0, 1) != '2'){
            throw new IOException(curl_error($HTTP_request), $HTTP_status_code);
        }
        $respObj = array();
        $respObj["statusCode"] = $HTTP_status_code;
        $respObj["response"] = $HTTP_response;
        curl_close($HTTP_request);

        return $respObj;
    }

    /**
     *@internal Send the export API request.
     */
    protected function sendExportAPIRequest($file_path, $endpoint, $config, $request_headers) {
        $request_url = $this->analytics_server_url . $endpoint;
        $response = NULL;

        if ($config != NULL) {
            $config = array_diff($config, array(""));
            if(is_array($config) && count($config) > 0) {
                $parameters =  "CONFIG=" . urlencode(json_encode($config));
                $request_url = $request_url . "?" . $parameters;
            }
        }

        if($this->access_token == null) {
            $this->regenerateAnalyticsOAuthToken();
        }

        $respArr = $this->submitRequest("GET", $request_url, $request_headers, $this->access_token);

        $resp_code = $respArr["statusCode"];
        $resp_content = $respArr["response"];

        if(substr($resp_code, 0, 1) != '2' && $resp_content != FALSE && $this->isOAuthExpired($resp_content)) {
            $this->regenerateAnalyticsOAuthToken();
            $respArr = $this->submitRequest("GET", $request_url, $request_headers, $this->access_token);
            $resp_code = $respArr["statusCode"];
            $resp_content = $respArr["response"];
        }

        if(substr($resp_code, 0, 1) != '2') {
            throw new ServerException($resp_content, FALSE);
        }

        file_put_contents($file_path, $resp_content);
    }

    /**
     *@internal Send the API request.
     */
    protected function sendAPIRequest($request_method, $endpoint, $config, $request_headers) {
        $request_url = $this->analytics_server_url . $endpoint;
        $response = NULL;

        if ($config != NULL) {
            $config = array_diff($config, array(""));
            if(is_array($config) && count($config) > 0) {
                $parameters =  "CONFIG=" . urlencode(json_encode($config));
                $request_url = $request_url . "?" . $parameters;
            }
        }

        if($this->access_token == null) {
            $this->regenerateAnalyticsOAuthToken();
        }

        $respArr = $this->submitRequest($request_method, $request_url, $request_headers, $this->access_token);

        $resp_code = $respArr["statusCode"];
        $resp_content = $respArr["response"];

        if(substr($resp_code, 0, 1) != '2' && $resp_content != FALSE && $this->isOAuthExpired($resp_content)) {
            $this->regenerateAnalyticsOAuthToken();
            $respArr = $this->submitRequest($request_method, $request_url, $request_headers, $this->access_token);
            $resp_code = $respArr["statusCode"];
            $resp_content = $respArr["response"];
        }

        if(substr($resp_code, 0, 1) != '2') {
            throw new ServerException($resp_content, FALSE);
        }

        //For success - no response case
        if($resp_content == FALSE) {
            return;
        }

        $json_response = json_decode($resp_content, TRUE);
        if(json_last_error() != JSON_ERROR_NONE) {
            $resp_content = stripslashes($resp_content);
            $json_response = json_decode($resp_content, TRUE);
        }
        if(json_last_error()) {
            throw new ParseException("Returned JSON format is not proper. Could possibly be version mismatch");
        }

        return $json_response;
    }

    /**
     * @internal Send request and get response from the server.
     */
    protected function submitRequest($request_method, $request_uri, $request_headers, $access_token = NULL) {
        $post_fields = NULL;

        if($access_token != NULL) {
            $request_headers[]  = "Authorization: Zoho-oauthtoken " . $access_token;
        }
        $request_headers[] = "User-Agent: Analytics PHP Client v" . self::CLIENT_VERSION;

        $HTTP_request = curl_init();
        curl_setopt($HTTP_request, CURLOPT_URL, $request_uri);
        curl_setopt($HTTP_request, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($HTTP_request, CURLOPT_FOLLOWLOCATION, TRUE);

        //Setting request method
        curl_setopt($HTTP_request, CURLOPT_CUSTOMREQUEST, $request_method);

        //Setting request header
        curl_setopt($HTTP_request, CURLOPT_HTTPHEADER, $request_headers);

        //Setting connection and read timeout
        curl_setopt($HTTP_request, CURLOPT_CONNECTTIMEOUT, $this->connection_timeout);
        curl_setopt($HTTP_request, CURLOPT_TIMEOUT, $this->read_timeout);

        //Adding post fileds data
        if($post_fields != NULL) {
            curl_setopt($HTTP_request, CURLOPT_POST, 1);
            curl_setopt($HTTP_request, CURLOPT_POSTFIELDS, $post_fields);
        }

        //Setting proxy configuration
        if($this->proxy == TRUE) {
            curl_setopt($HTTP_request, CURLOPT_PROXY, $this->proxy_host);
            curl_setopt($HTTP_request, CURLOPT_PROXYTYPE, $this->proxy_type);
            curl_setopt($HTTP_request, CURLOPT_PROXYPORT, $this->proxy_port);
            curl_setopt($HTTP_request, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
            curl_setopt($HTTP_request, CURLOPT_PROXYUSERPWD, "$this->proxy_user_name:$this->proxy_password");
        }

        $HTTP_response = curl_exec($HTTP_request);
        $HTTP_status_code = curl_getinfo($HTTP_request, CURLINFO_HTTP_CODE);

        if($HTTP_response == FALSE && substr($HTTP_status_code, 0, 1) != '2'){
            throw new IOException(curl_error($HTTP_request), $HTTP_status_code);
        }
        $respObj = array();
        $respObj["statusCode"] = $HTTP_status_code;
        $respObj["response"] = $HTTP_response;
        curl_close($HTTP_request);

        return $respObj;
    }

    /**
     *@internal For getting OAuth token to invoke API.
     */
    private function regenerateAnalyticsOAuthToken() {
        $endpoint = $this->accounts_server_url . "/oauth/v2/token";
        $parameters = "client_id=" . $this->client_id . "&client_secret=" . $this->client_secret . "&refresh_token=" . $this->refresh_token . "&grant_type=refresh_token";

        $request_url = $endpoint . "?" . $parameters;
        $respArr = $this->submitRequest("POST", $request_url, NULL, NULL);
        $oauth_status_code = $respArr["statusCode"];
        $oauth_response = $respArr["response"];
        if($oauth_response != FALSE) {
            if($oauth_status_code == 200) {
                $json_response = json_decode($oauth_response, TRUE);
                if(json_last_error() != JSON_ERROR_NONE){
                    $oauth_response = stripslashes($oauth_response);
                    $json_response = json_decode($oauth_response, TRUE);
                }
                if(json_last_error()) {
                    throw new ParseException("Regenerate OAuthToken response is not proper. Invalid JSON data. Response - " . $oauth_response);
                }
                if(array_key_exists("access_token", $json_response)) {
                    $this->access_token = $json_response["access_token"];
                    return ;
                }
            }
            throw new ServerException($oauth_response, TRUE);
        }
        else {
            throw new Exception("Internal error occurred.");
        }
    }

    /**
     *@internal For validating OAuth token.
     */
    private function isOAuthExpired($resp_content) {
        $json_response = json_decode($resp_content, TRUE);
        if(json_last_error() != JSON_ERROR_NONE){
            $resp_content = stripslashes($resp_content);
            $json_response = json_decode($resp_content, TRUE);
        }
        if(json_last_error()) {
            throw new ParseException("API response is not proper. Invalid JSON data. Response - " . $resp_content);
        }
        $error_code = $json_response["data"]["errorCode"];
        if($error_code == 8535) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }
}

class OrgAPI extends AnalyticsClient
{
    private $ac;
    private $req_headers = array();

    /**
     * Creates a new OrgAPI instance.
     * @param AnalyticsClient $ac AnalyticsClient instance.
     * @param string $org_id The ID of the organization.
     */
    function __construct($ac, $org_id) {
        $this->ac = $ac;
        $this->req_headers[] = "ZANALYTICS-ORGID: " . $org_id;
    }

    /**
     * Returns a new BulkAPI instance.
     * @param string $workspace_name Name of the workspace.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string Created workspace id.
     */
    function createWorkspace($workspace_name, $config = array()) {
        $endpoint = "/restapi/v2/workspaces";
        $config["workspaceName"] = $workspace_name;
        $response = $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
        return $response["data"]["workspaceId"];
    }

    /**
     * Returns list of admins for a specified organization.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Organization admin list.
     */
    function getAdmins() {
        $endpoint = "/restapi/v2/orgadmins";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"]["orgAdmins"];
    }

    /**
     * Returns subscription details of the specified organization.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Subscription details.
     */
    function getSubscriptionDetails() {
        $endpoint = "/restapi/v2/subscription";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"]["subscription"];
    }

    /**
     * Returns resource usage details of the specified organization.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Resource details.
     */
    function getResourceDetails() {
        $endpoint = "/restapi/v2/resources";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"]["resourceDetails"];
    }

    /**
     * Returns list of users for the specified organization.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() User list.
     */
    function getUsers() {
        $endpoint = "/restapi/v2/users";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"]["users"];
    }

    /**
     * Add users to the specified organization.
     * @param array() $email_ids The email address of the users to be added.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function addUsers($email_ids, $config = array()) {
        $endpoint = "/restapi/v2/users";
        $config["emailIds"] = $email_ids;
        $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
    }

    /**
     * Remove users from the specified organization.
     * @param array() $email_ids The email address of the users to be removed.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function removeUsers($email_ids, $config = array()) {
        $endpoint = "/restapi/v2/users";
        $config["emailIds"] = $email_ids;
        $this->ac->sendAPIRequest("DELETE", $endpoint, $config, $this->req_headers);
    }

    /**
     * Activate users in the specified organization.
     * @param array() $email_ids The email address of the users to be activated.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function activateUsers($email_ids, $config = array()) {
        $endpoint = "/restapi/v2/users/active";
        $config["emailIds"] = $email_ids;
        $this->ac->sendAPIRequest("PUT", $endpoint, $config, $this->req_headers);
    }

    /**
     * Deactivate users in the specified organization.
     * @param array() $email_ids The email address of the users to be deactivated.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function deactivateUsers($email_ids, $config = array()) {
        $endpoint = "/restapi/v2/users/inactive";
        $config["emailIds"] = $email_ids;
        $this->ac->sendAPIRequest("PUT", $endpoint, $config, $this->req_headers);
    }

    /**
     * Change role for the specified users.
     * @param array() $email_ids The email address of the users.
     * @param string $role New role for the users.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function changeUserRole($email_ids, $role, $config = array()) {
        $endpoint = "/restapi/v2/users/role";
        $config["emailIds"] = $email_ids;
        $config["role"] = $role;
        $this->ac->sendAPIRequest("PUT", $endpoint, $config, $this->req_headers);
    }

    /**
     * Returns details of the specified workspace/view.
     * @param string $workspace_name Name of the workspace.
     * @param string $view_name Name of the view.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Workspace (or) View meta details.
     */
    function getMetaDetails($workspace_name, $view_name) {
        $endpoint = "/restapi/v2/metadetails";
        $config = array();
        $config["workspaceName"] = $workspace_name;
        if ($view_name != NULL) {
            $config["viewName"] = $view_name;
        }
        $response = $this->ac->sendAPIRequest("GET", $endpoint, $config, $this->req_headers);
        return $response["data"];
    }
}

class WorkspaceAPI extends AnalyticsClient
{
    private $ac;
    private $workspace_end_point;
    private $req_headers = array();

    /**
     * Creates a new WorkspaceAPI instance.
     * @param AnalyticsClient $ac AnalyticsClient instance.
     * @param string $org_id The ID of the organization.
     * @param string $workspace_id The ID of the workspace.
     */
    function __construct($ac, $org_id, $workspace_id) {
        $this->ac = $ac;
        $this->workspace_end_point = "/restapi/v2/workspaces/" . $workspace_id;
        $this->req_headers[] = "ZANALYTICS-ORGID: " . $org_id;
    }

    /**
     * Copy the specified workspace from one organization to another or within the organization.
     * @param string $new_workspace_name Name of the new workspace.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @param string $dest_org_id Id of the organization where the destination workspace is present. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string Copied workspace id.
     */
    function copy($new_workspace_name, $config = array(), $dest_org_id=NULL) {
        $config["newWorkspaceName"] = $new_workspace_name;
        $request_headers = $this->req_headers;
        if ($dest_org_id != NULL) {
            $request_headers[]  = "ZANALYTICS-DEST-ORGID: " . $dest_org_id;
        }
        $response = $this->ac->sendAPIRequest("POST", $this->workspace_end_point, $config, $request_headers);
        return $response["data"]["workspaceId"];
    }

    /**
     * Rename a specified workspace in the organization.
     * @param string $workspace_name New name for the workspace.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function rename($workspace_name, $config = array()) {
        $config["workspaceName"] = $workspace_name;
        $this->ac->sendAPIRequest("PUT", $this->workspace_end_point, $config, $this->req_headers);
    }

    /**
     * Delete a specified workspace in the organization.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function delete() {
        $this->ac->sendAPIRequest("DELETE", $this->workspace_end_point, NULL, $this->req_headers);
    }

    /**
     * Returns the secret key of the specified workspace.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string Workspace secret key.
     */
    function getSecretKey($config = array()) {
        $endpoint = $this->workspace_end_point . "/secretkey";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, $config, $this->req_headers);
        return $response["data"]["workspaceKey"];
    }

    /**
     * Adds a specified workspace as favorite.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function addFavorite() {
        $endpoint = $this->workspace_end_point . "/favorite";
        $this->ac->sendAPIRequest("POST", $endpoint, NULL, $this->req_headers);
    }

    /**
     * Remove a specified workspace from favorite.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function removeFavorite() {
        $endpoint = $this->workspace_end_point . "/favorite";
        $this->ac->sendAPIRequest("DELETE", $endpoint, NULL, $this->req_headers);
    }

    /**
     * Adds a specified workspace as default.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function addDefault() {
        $endpoint = $this->workspace_end_point . "/default";
        $this->ac->sendAPIRequest("POST", $endpoint, NULL, $this->req_headers);
    }

    /**
     * Remove a specified workspace from default.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function removeDefault() {
        $endpoint = $this->workspace_end_point . "/default";
        $this->ac->sendAPIRequest("DELETE", $endpoint, NULL, $this->req_headers);
    }

    /**
     * Returns list of admins for the specified workspace.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Workspace admin list.
     */
    function getAdmins() {
        $endpoint = $this->workspace_end_point . "/admins";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"]["workspaceAdmins"];
    }

    /**
     * Add admins for the specified workspace.
     * @param array() $email_ids The email address of the admin users to be added.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function addAdmins($email_ids, $config = array()) {
        $endpoint = $this->workspace_end_point . "/admins";
        $config["emailIds"] = $email_ids;
        $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
    }

    /**
     * Remove admins from the specified workspace.
     * @param array() $email_ids The email address of the admin users to be removed.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function removeAdmins($email_ids, $config = array()) {
        $endpoint = $this->workspace_end_point . "/admins";
        $config["emailIds"] = $email_ids;
        $this->ac->sendAPIRequest("DELETE", $endpoint, $config, $this->req_headers);
    }

    /**
     * Returns shared details of the specified workspace.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Workspace share info.
     */
    function getShareInfo() {
        $endpoint = $this->workspace_end_point . "/share";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"];
    }

    /**
     * Share views to the specified users.
     * @param array() $view_ids View ids which to be shared.
     * @param array() $email_ids The email address of the users to whom the views need to be shared.
     * @param array() $permissions Contains permission details.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function shareViews($view_ids, $email_ids, $permissions, $config = array()) {
        $endpoint = $this->workspace_end_point . "/share";
        $config["viewIds"] = $view_ids;
        $config["emailIds"] = $email_ids;
        $config["permissions"] = $permissions;
        $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
    }

    /**
     * Remove shared views for the specified users.
     * @param array() $view_ids View ids whose sharing needs to be removed.
     * @param array() $email_ids The email address of the users to whom the sharing need to be removed.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function removeShare($view_ids, $email_ids, $config = array()) {
        $endpoint = $this->workspace_end_point . "/share";
        $config["emailIds"] = $email_ids;
        if ($view_ids != NULL) {
            $config["viewIds"] = $view_ids;
        }
        $this->ac->sendAPIRequest("DELETE", $endpoint, $config, $this->req_headers);
    }

    /**
     * Returns shared details of the specified views.
     * @param array() $view_ids View ids for which sharing details are required.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function getSharedDetailsForViews($view_ids) {
        $endpoint = $this->workspace_end_point . "/share/shareddetails";
        $config = array();
        $config["viewIds"] = $view_ids;
        $response = $this->ac->sendAPIRequest("GET", $endpoint, $config, $this->req_headers);
        return $response["data"]["sharedDetails"];
    }

    /**
     * Returns list of all accessible folders for the specified workspace.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Folder list.
     */
    function getFolders() {
        $endpoint = $this->workspace_end_point . "/folders";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"]["folders"];
    }

    /**
     * Create a folder in the specified workspace.
     * @param string $folder_name Name of the folder to be created.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string Created folder id.
     */
    function createFolder($folder_name, $config = array()) {
        $endpoint = $this->workspace_end_point . "/folders";
        $config["folderName"] = $folder_name;
        $response = $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
        return $response["data"]["folderId"];
    }

    /**
     * Rename a specified folder in the workspace.
     * @param string $folder_id Id of the folder.
     * @param string $folder_name New name for the folder.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function renameFolder($folder_id, $folder_name, $config = array()) {
        $endpoint = $this->workspace_end_point . "/folders/" . $folder_id;
        $config["folderName"] = $folder_name;
        $this->ac->sendAPIRequest("PUT", $endpoint, $config, $this->req_headers);
    }

    /**
     * Swaps the hierarchy of a parent folder and a subfolder.
     * @param string $folder_id Id of the folder.
     * @param int $hierarchy New hierarchy for the folder. (0 - Parent; 1 - Child)
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function changeFolderHierarchy($folder_id, $hierarchy, $config = array()) {
        $endpoint = $this->workspace_end_point . "/folders/" . $folder_id . "/move";
        $config["hierarchy"] = $hierarchy;
        $this->ac->sendAPIRequest("PUT", $endpoint, $config, $this->req_headers);
    }

    /**
     * Place the folder above the reference folder.
     * @param string $folder_id Id of the folder.
     * @param string $reference_folder_id Id of the reference folder.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function changeFolderPosition($folder_id, $reference_folder_id, $config = array()) {
        $endpoint = $this->workspace_end_point . "/folders/" . $folder_id . "/reorder";
        $config["reference_folder_id"] = $reference_folder_id;
        $this->ac->sendAPIRequest("PUT", $endpoint, $config, $this->req_headers);
    }

    /**
     * Place the folder above the reference folder.
     * @param string $folder_id Id of the folder.
     * @param string $referenceFolderId Id of the reference folder.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function moveViewsToFolder($folder_id, $view_ids, $config = array()) {
        $endpoint = $this->workspace_end_point . "/views/movetofolder";
        $config["viewIds"] = $view_ids;
        $config["folderId"] = $folder_id;
        $this->ac->sendAPIRequest("PUT", $endpoint, $config, $this->req_headers);
    }

    /**
     * Delete a specified folder in the workspace.
     * @param string $folder_id Id of the folder.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function deleteFolder($folder_id) {
        $endpoint = $this->workspace_end_point . "/folders/" . $folder_id;
        $this->ac->sendAPIRequest("DELETE", $endpoint, NULL, $this->req_headers);
    }

    /**
     * Returns list of all accessible views for the specified workspace.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() View list.
     */
    function getViews() {
        $endpoint = $this->workspace_end_point . "/views";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"]["views"];
    }

    /**
     * Create a table in the specified workspace.
     * @param array() $table_design Table structure.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string Created table id.
     */
    function createTable($table_design) {
        $endpoint = $this->workspace_end_point . "/tables";
        $config = array();
        $config["tableDesign"] = $table_design;
        $response = $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
        return $response["data"]["viewId"];
    }

    /**
     * Create a new query table in the workspace.
     * @param string $sql_query SQL query to construct the query table.
     * @param string $query_table_name Name of the query table to be created.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string Created query table id.
     */
    function createQueryTable($sql_query, $query_table_name, $config = array()) {
        $endpoint = $this->workspace_end_point . "/querytables";
        $config["sqlQuery"] = $sql_query;
        $config["queryTableName"] = $query_table_name;
        $response = $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
        return $response["data"]["viewId"];
    }

    /**
     * Update the mentioned query table in the workspace.
     * @param string $view_id Id of the query table to be updated.
     * @param string $sql_query SQL query to construct the query table.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function editQueryTable($view_id, $sql_query, $config = array()) {
        $endpoint = $this->workspace_end_point . "/querytables/" . $view_id;
        $config["sqlQuery"] = $sql_query;
        $this->ac->sendAPIRequest("PUT", $endpoint, $config, $this->req_headers);
    }

    /**
     * Copy the specified views from one workspace to another workspace.
     * @param array() $view_ids The id of the views to be copied.
     * @param string $dest_workspace_id The destination workspace id.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @param string $dest_org_id Id of the organization where the destination workspace is present. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() View list.
     */
    function copyViews($view_ids, $dest_workspace_id, $config = array(), $dest_org_id=NULL) {
        $endpoint = $this->workspace_end_point . "/views/copy";
        $config["viewIds"] = $view_ids;
        $config["destWorkspaceId"] = $dest_workspace_id;
        $request_headers = $this->req_headers;
        if ($dest_org_id != NULL) {
            $request_headers[]  = "ZANALYTICS-DEST-ORGID: " . $dest_org_id;
        }
        $response = $this->ac->sendAPIRequest("POST", $endpoint, $config, $request_headers);
        return $response["data"]["views"];
    }

    /**
     * Returns list of groups for the specified workspace.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Group list.
     */
    function getGroups() {
        $endpoint = $this->workspace_end_point . "/groups";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"]["groups"];
    }

    /**
     * Get the details of the specified group.
     * @param string $group_id Id of the group.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Details of the specified group.
     */
    function getGroupDetails($group_id) {
        $endpoint = $this->workspace_end_point . "/groups/" . $group_id;
        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"]["groups"];
    }

    /**
     * Create a group in the specified workspace.
     * @param string $group_name Name of the group.
     * @param array() $email_ids The email address of the users to be added to the group.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string Created group id.
     */
    function createGroup($group_name, $email_ids, $config = array()) {
        $endpoint = $this->workspace_end_point . "/groups";
        $config["groupName"] = $group_name;
        $config["emailIds"] = $email_ids;
        $response = $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
        return $response["data"]["groupId"];
    }

    /**
     * Rename a specified group.
     * @param string $group_id Id of the group.
     * @param string $new_group_name New name for the group.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function renameGroup($group_id, $new_group_name, $config = array()) {
        $endpoint = $this->workspace_end_point . "/groups/" . $group_id;
        $config["groupName"] = $new_group_name;
        $this->ac->sendAPIRequest("PUT", $endpoint, $config, $this->req_headers);
    }

    /**
     * Delete a specified group.
     * @param string $group_id Id of the group.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function deleteGroup($group_id) {
        $endpoint = $this->workspace_end_point . "/groups/" . $group_id;
        $this->ac->sendAPIRequest("DELETE", $endpoint, NULL, $this->req_headers);
    }

    /**
     * Add users to the specified group.
     * @param string $group_id Id of the group.
     * @param array() $email_ids The email address of the users to be added to the group.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function addGroupMembers($group_id, $email_ids, $config = array()) {
        $endpoint = $this->workspace_end_point . "/groups/" . $group_id . "/members";
        $config["emailIds"] = $email_ids;
        $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
    }

    /**
     * Remove users from the specified group.
     * @param string $group_id Id of the group.
     * @param array() $email_ids The email address of the users to be removed from the group.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function removeGroupMembers($group_id, $email_ids, $config = array()) {
        $endpoint = $this->workspace_end_point . "/groups/" . $group_id . "/members";
        $config["emailIds"] = $email_ids;
        $this->ac->sendAPIRequest("DELETE", $endpoint, $config, $this->req_headers);
    }

    /**
     * Create a slideshow in the specified workspace.
     * @param string $slide_name Name of the slideshow.
     * @param array() $view_ids Ids of the view to be included in the slideshow.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string Created slideshow id.
     */
    function createSlideshow($slide_name, $view_ids, $config = array()) {
        $endpoint = $this->workspace_end_point . "/slides";
        $config["slideName"] = $slide_name;
        $config["viewIds"] = $view_ids;
        $response = $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
        return $response["data"]["slideId"];
    }

    /**
     * Update details of the specified slideshow.
     * @param string $slide_id Id of the slideshow.
     * @param array() $config Contains the control configurations
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string Created slideshow id.
     */
    function updateSlideshow($slide_id, $config = array()) {
        $endpoint = $this->workspace_end_point . "/slides/" . $slide_id;
        $endpoint = $this->slide_end_point;
        $this->ac->sendAPIRequest("PUT", $endpoint, $config, $this->req_headers);
    }

    /**
     * Delete a specified slideshow in the workspace.
     * @param string $slide_id Id of the slideshow.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function deleteSlideshow($slide_id) {
        $endpoint = $this->workspace_end_point . "/slides/" . $slide_id;
        $this->ac->sendAPIRequest("DELETE", $endpoint, NULL, $this->req_headers);
    }

    /**
     * Returns list of slideshows for the specified workspace.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Slideshow list.
     */
    function getSlideshows() {
        $endpoint = $this->workspace_end_point . "/slides";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"]["slideshows"];
    }

    /**
     * Returns details of the specified slideshow.
     * @param string $slide_id Id of the slideshow.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Slideshow details.
     */
    function getSlideshowDetails($slide_id) {
        $endpoint = $this->workspace_end_point . "/slides/" . $slide_id;
        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"]["slideInfo"];
    }

    /**
     * Returns slide URL to access the specified slideshow.
     * @param string $slide_id Id of the slideshow.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Slideshow URL.
     */
    function getSlideshowUrl($slide_id, $config = array()) {
        $endpoint = $this->workspace_end_point . "/slides/" . $slide_id . "/publish";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, $config, $this->req_headers);
        return $response["data"]["slideUrl"];
    }

    /**
     * Enable workspace to the specified white label domain.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function enableDomainAccess() {
        $endpoint = $this->workspace_end_point . "/wlaccess";
        $this->ac->sendAPIRequest("POST", $endpoint, NULL, $this->req_headers);
    }

    /**
     * Disable workspace from the specified white label domain.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function disableDomainAccess() {
        $endpoint = $this->workspace_end_point . "/wlaccess";
        $this->ac->sendAPIRequest("DELETE", $endpoint, NULL, $this->req_headers);
    }

    /**
     * Create a variable in the workspace.
     * @param string $variable_name Name of the variable to be created.
     * @param string $variable_datatype Datatype of the variable to be created.
     * @param string $variable_type Type of the variable to be created.
     * @param array() $config Contains the control parameters.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string Created variable id.
     */
    function createVariable($variable_name, $variable_datatype, $variable_type, $config = array()) {
        $endpoint = $this->workspace_end_point . "/variables";
        $config["variableName"] = $variable_name;
        $config["variableDataType"] = $variable_datatype;
        $config["variableType"] = $variable_type;
        $response = $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
        return $response["data"]["variableId"];
    }

    /**
     * Update details of the specified variable in the workspace.
     * @param string $variable_id Id of the variable.
     * @param string $variable_name New name for the variable.
     * @param string $variable_datatype New datatype for the variable.
     * @param string $variable_type New type for the variable.
     * @param array() $config Contains the control parameters.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function updateVariable($variable_id, $variable_name, $variable_datatype, $variable_type, $config = array()) {
        $endpoint = $this->workspace_end_point . "/variables/" . $variable_id;
        $config["variableName"] = $variable_name;
        $config["variableDataType"] = $variable_datatype;
        $config["variableType"] = $variable_type;
        $this->ac->sendAPIRequest("PUT", $endpoint, $config, $this->req_headers);
    }

    /**
     * Delete the specified variable in the workspace.
     * @param string $variable_id Id of the variable.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function deleteVariable($variable_id) {
        $endpoint = $this->workspace_end_point . "/variables/" . $variable_id;
        $this->ac->sendAPIRequest("DELETE", $endpoint, NULL, $this->req_headers);
    }

    /**
     * Returns list of variables for the specified workspace.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Variables list.
     */
    function getVariables() {
        $endpoint = $this->workspace_end_point . "/variables";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"]["variables"];
    }

    /**
     * Returns details of the specified variable.
     * @param string $variable_id Id of the variable.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Variable details.
     */
    function getVariableDetails($variable_id) {
        $endpoint = $this->workspace_end_point . "/variables/" . $variable_id;
        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"];
    }

    /**
     * Make the specified folder as default.
     * @param string $folder_id Id of the folder.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function makeDefaultFolder($folder_id) {
        $endpoint = $this->workspace_end_point . "/folders/" . $folder_id . "/default";
        $this->ac->sendAPIRequest("PUT", $endpoint, NULL, $this->req_headers);
    }

    /**
     * Returns list of datasources for the specified workspace.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Datasource list.
     */
    function getDatasources() {
        $endpoint = $this->workspace_end_point . "/datasources";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"]["dataSources"];
    }

    /**
     * Initiate data sync for the specified datasource.
     * @param string $datasource_id Id of the datasource.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function syncData($datasource_id, $config=array()) {
        $endpoint = $this->workspace_end_point . "/datasources/" . $datasource_id . "/sync";
        $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
    }

    /**
     * Update connection details for the specified datasource.
     * @param string $datasource_id Id of the datasource.
     * @param array() $config Contains the control parameters.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function updateDatasourceConnection($datasource_id, $config=array()) {
        $endpoint = $this->workspace_end_point . "/datasources/" . $datasource_id;
        $this->ac->sendAPIRequest("PUT", $endpoint, $config, $this->req_headers);
    }

    /**
     * Returns list of all views available in trash for the specified workspace.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Trash view list.
     */
    function getTrashViews() {
        $endpoint = $this->workspace_end_point . "/trash";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"]["views"];
    }

    /**
     * Restore the specified view from trash.
     * @param string $view_id Id of the view.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function restoreTrashView($view_id, $config=array()) {
        $endpoint = $this->workspace_end_point . "/trash/" . $view_id;
        $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
    }

    /**
     * Delete the specified view permanently from trash.
     * @param string $view_id Id of the view.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function deleteTrashView($view_id, $config=array()) {
        $endpoint = $this->workspace_end_point . "/trash/" . $view_id;
        $this->ac->sendAPIRequest("DELETE", $endpoint, $config, $this->req_headers);
    }

    /**
     * Returns list of users for the specified workspace.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() User list.
     */
    function getWorkspaceUsers() {
        $endpoint = $this->workspace_end_point . "/users";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"]["users"];
    }

    /**
     * Add users to the specified workspace.
     * @param array() $email_ids The email address of the users to be added.
     * @param string $role Role of the user to be added.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function addWorkspaceUsers($email_ids, $role, $config = array()) {
        $endpoint = $this->workspace_end_point . "/users";
        $config["emailIds"] = $email_ids;
        $config["role"] = $role;
        $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
    }

    /**
     * Remove users from the specified workspace.
     * @param array() $email_ids The email address of the users to be removed.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function removeWorkspaceUsers($email_ids, $config = array()) {
        $endpoint = $this->workspace_end_point . "/users";
        $config["emailIds"] = $email_ids;
        $this->ac->sendAPIRequest("DELETE", $endpoint, $config, $this->req_headers);
    }

    /**
     * Change users staus in the specified workspace.
     * @param array() $email_ids The email address of the users.
     * @param string $operation New status for the users ( Values -  activate | deactivate )
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function changeWorkspaceUserStatus($email_ids, $operation, $config = array()) {
        $endpoint = $this->workspace_end_point . "/users/status";
        $config["emailIds"] = $email_ids;
        $config["operation"] = $operation;
        $this->ac->sendAPIRequest("PUT", $endpoint, $config, $this->req_headers);
    }

    /**
     * Change role of the specified users in the workspace.
     * @param array() $email_ids The email address of the users.
     * @param string $role Name of the role.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function changeWorkspaceUserRole($email_ids, $role, $config = array()) {
        $endpoint = $this->workspace_end_point . "/users/role";
        $config["emailIds"] = $email_ids;
        $config["role"] = $role;
        $this->ac->sendAPIRequest("PUT", $endpoint, $config, $this->req_headers);
    }

    /**
     * Export the mentioned table (or) view data.
     * @param array() $view_ids Ids of the view to be exported.
     * @param string $file_path Path of the file where the data exported to be stored. ( Should be in 'atpt' format )
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function exportAsTemplate($view_ids, $file_path, $config = array()) {
        $endpoint = $this->data_end_point . "/template/data";
        $config["viewIds"] = $view_ids;

        $this->ac->sendExportAPIRequest($file_path, $endpoint, $config, $this->req_headers);
    }

    /**
     * Returns list of email schedules available in the specified workspace.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() List of email schedules.
     */
    function getEmailSchedules() {
        $endpoint = $this->workspace_end_point . "/emailschedules";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"]["emailSchedules"];
    }

    /**
     * Create an email schedule in the specified workspace.
     * @param string $schedule_name Name of the email schedule.
     * @param array() $view_ids View ids to be mailed.
     * @param string $format The format in which the data has to be mailed.
     * @param array() $email_ids The recipients' email addresses for sending views.
     * @param array() $schedule_details Contains schedule frequency, date, and time info.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string Created email schedule id.
     */
    function createEmailSchedule($schedule_name, $view_ids, $format, $email_ids, $schedule_details, $config = array()) {
        $endpoint = $this->workspace_end_point . "/emailschedules";
        $config["scheduleName"] = $schedule_name;
        $config["viewIds"] = $view_ids;
        $config["exportType"] = $format;
        $config["emailIds"] = $email_ids;
        $config["scheduleDetails"] = $schedule_details;
        $response = $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
        return $response["data"]["scheduleId"];
    }

    /**
     * Update configurations of the specified email schedule in the workspace.
     * @param string $schedule_id Id for the email schedule.
     * @param array() $config Contains the control configurations.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string Updated schedule id.
     */
    function updateEmailSchedule($schedule_id, $config) {
        $endpoint = $this->workspace_end_point . "/emailschedules/" . $schedule_id;
        $response = $this->ac->sendAPIRequest("PUT", $endpoint, $config, $this->req_headers);
        return $response["data"]["scheduleId"];
    }

    /**
     * Trigger configured email schedules instantly.
     * @param string $schedule_id Id for the email schedule.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function triggerEmailSchedule($schedule_id) {
        $endpoint = $this->workspace_end_point . "/emailschedules/" . $schedule_id;
        $this->ac->sendAPIRequest("POST", $endpoint, NULL, $this->req_headers);
    }

    /**
     * Update email schedule status.
     * @param string $schedule_id Id for the email schedule.
     * @param string $operation New status for the schedule (Values - activate | deactivate).
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function changeEmailScheduleStatus($schedule_id, $operation) {
        $endpoint = $this->workspace_end_point . "/emailschedules/" . $schedule_id . "/status";
        $config = array("operation" => $operation);
        $this->ac->sendAPIRequest("PUT", $endpoint, $config, $this->req_headers);
    }

    /**
     * Delete the specified email schedule in the workspace.
     * @param string $schedule_id Id for the email schedule.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function deleteEmailSchedule($schedule_id) {
        $endpoint = $this->workspace_end_point . "/emailschedules/" . $schedule_id;
        $this->ac->sendAPIRequest("DELETE", $endpoint, NULL, $this->req_headers);
    }


}

class ViewAPI extends AnalyticsClient
{
    private $ac;
    private $view_end_point;
    private $req_headers = array();

    /**
     * Creates a new ViewAPI instance.
     * @param AnalyticsClient $ac AnalyticsClient instance.
     * @param string $org_id The ID of the organization.
     * @param string $workspace_id The ID of the workspace.
     * @param string $view_id The ID of the view.
     */
    function __construct($ac, $org_id, $workspace_id, $view_id) {
        $this->ac = $ac;
        $this->view_end_point = "/restapi/v2/workspaces/" . $workspace_id . "/views/" . $view_id;
        $this->req_headers[] = "ZANALYTICS-ORGID: " . $org_id;
    }

    /**
     * Add a single row in the specified table.
     * @param array() $column_values Contains the values for the row. The column names are the key.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Column Names and Added Row Values.
     */
    function addRow($column_values, $config = array()) {
        $endpoint = $this->view_end_point . "/rows";
        $config["columns"] = $column_values;
        $response = $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
        return $response["data"];
    }

    /**
     * Update rows in the specified table.
     * @param array() $column_values Contains the values for the row. The column names are the key.
     * @param array() $criteria The criteria to be applied for updating data. Only rows matching the criteria will be updated. Should be null for update all rows.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Updated Columns List and Updated Rows Count.
     */
    function updateRow($column_values, $criteria, $config = array()) {
        $endpoint = $this->view_end_point . "/rows";
        $config["columns"] = $column_values;
        if ($criteria != NULL) {
            $config["criteria"] = $criteria;
        }
        $response = $this->ac->sendAPIRequest("PUT", $endpoint, $config, $this->req_headers);
        return $response["data"];
    }

    /**
     * Delete rows in the specified table.
     * @param array() $criteria The criteria to be applied for deleting data. Only rows matching the criteria will be deleted. Should be null for delete all rows.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return int Deleted rows count.
     */
    function deleteRow($criteria, $config = array()) {
        $endpoint = $this->view_end_point . "/rows";
        if ($criteria != NULL) {
            $config["criteria"] = $criteria;
        }
        $response = $this->ac->sendAPIRequest("DELETE", $endpoint, $config, $this->req_headers);
        return $response["data"]["deletedRows"];
    }

    /**
     * Rename a specified view in the workspace.
     * @param string $view_name New name of the view.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function rename($view_name, $config = array()) {
        $config["viewName"] = $view_name;
        $this->ac->sendAPIRequest("PUT", $this->view_end_point, $config, $this->req_headers);
    }

    /**
     * Delete a specified view in the workspace.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function delete($config = array()) {
        $this->ac->sendAPIRequest("DELETE", $this->view_end_point, $config, $this->req_headers);
    }

    /**
     * Copy a specified view within the workspace.
     * @param string $new_view_name The name of the new view.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string Created view id.
     */
    function saveAs($new_view_name, $config = array()) {
        $endpoint = $this->view_end_point . "/saveas";
        $config["viewName"] = $new_view_name;
        $response = $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
        return $response["data"]["viewId"];
    }

    /**
     * Copy the specified formulas from one table to another within the workspace or across workspaces.
     * @param string $formulaNames  The name of the formula columns to be copied.
     * @param string $dest_workspace_id The id of the destination workspace.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @param string $dest_org_id Id of the organization where the destination workspace is present. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function copyFormulas($formulaNames, $dest_workspace_id, $config = array(), $dest_org_id=NULL) {
        $endpoint = $this->view_end_point . "/formulas/copy";
        $config["formulaColumnNames"] = $formulaNames;
        $config["destWorkspaceId"] = $dest_workspace_id;
        $request_headers = $this->req_headers;
        if ($dest_org_id != NULL) {
            $request_headers[]  = "ZANALYTICS-DEST-ORGID: " . $dest_org_id;
        }
        $this->ac->sendAPIRequest("POST", $endpoint, $config, $request_headers);
    }

    /**
     * Adds a specified view as favorite.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function addFavorite() {
        $endpoint = $this->view_end_point . "/favorite";
        $this->ac->sendAPIRequest("POST", $endpoint, NULL, $this->req_headers);
    }

    /**
     * Remove a specified view from favorite.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function removeFavorite() {
        $endpoint = $this->view_end_point . "/favorite";
        $this->ac->sendAPIRequest("DELETE", $endpoint, NULL, $this->req_headers);
    }

    /**
     * Create reports for the specified table based on the reference table.
     * @param string $ref_view_id  The ID of the reference view.
     * @param string $folder_id The folder id where the views to be saved.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function createSimilarViews($ref_view_id, $folder_id, $config = array()) {
        $endpoint = $this->view_end_point . "/similarviews";
        $config["referenceViewId"] = $ref_view_id;
        $config["folderId"] = $folder_id;
        $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
    }

    /**
     * Auto generate reports for the specified table.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function autoAnalyse($config = array()) {
        $endpoint = $this->view_end_point . "/autoanalyse";
        $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
    }

    /**
     * Returns permissions for the specified view.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Permission details.
     */
    function getMyPermissions() {
        $endpoint = $this->view_end_point . "/share/userpermissions";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"]["permissions"];
    }

    /**
     * Returns the URL to access the specified view.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string View URL.
     */
    function getViewURL($config = array()) {
        $endpoint = $this->view_end_point . "/publish";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, $config, $this->req_headers);
        return $response["data"]["viewUrl"];
    }

    /**
     * Returns embed URL to access the specified view.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string Embed URL.
     */
    function getEmbedURL($config = array()) {
        $endpoint = $this->view_end_point . "/publish/embed";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, $config, $this->req_headers);
        return $response["data"]["embedUrl"];
    }

    /**
     * Returns private URL to access the specified view.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string Private URL.
     */
    function getPrivateURL($config = array()) {
        $endpoint = $this->view_end_point . "/publish/privatelink";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, $config, $this->req_headers);
        return $response["data"]["privateUrl"];
    }

    /**
     * Create a private URL for the specified view.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string private URL.
     */
    function createPrivateURL($config = array()) {
        $endpoint = $this->view_end_point . "/publish/privatelink";
        $response = $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
        return $response["data"]["privateUrl"];
    }

    /**
     * Remove private link access for the specified view.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function removePrivateAccess() {
        $endpoint = $this->view_end_point . "/publish/privatelink";
        $this->ac->sendAPIRequest("DELETE", $endpoint, NULL, $this->req_headers);
    }

    /**
     * Make the specified view publically accessible.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string public URL.
     */
    function makeViewPublic($config = array()) {
        $endpoint = $this->view_end_point . "/publish/public";
        $response = $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
        return $response["data"]["publicUrl"];
    }

    /**
     * Remove public access for the specified view.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function removePublicAccess() {
        $endpoint = $this->view_end_point . "/publish/public";
        $this->ac->sendAPIRequest("DELETE", $endpoint, NULL, $this->req_headers);
    }

    /**
     * Returns publish configurations for the specified view.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Publish details.
     */
    function getPublishConfigurations() {
        $endpoint = $this->view_end_point . "/publish/config";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"];
    }

    /**
     * Update publish configurations for the specified view.
     * @param array() $config Contains the control parameters.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function updatePublishConfigurations($config = array()) {
        $endpoint = $this->view_end_point . "/publish/config";
        $this->ac->sendAPIRequest("PUT", $endpoint, $config, $this->req_headers);
    }

    /**
     * Add a column in the specified table.
     * @param string $column_name The name of the column.
     * @param string $data_type The data-type of the column.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string Created column id.
     */
    function addColumn($column_name, $data_type, $config = array()) {
        $endpoint = $this->view_end_point . "/columns";
        $config["columnName"] = $column_name;
        $config["dataType"] = $data_type;
        $response = $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
        return $response["data"]["columnId"];
    }

    /**
     * Rename a specified column in the table.
     * @param string $column_id Id of the column.
     * @param string $column_name New name for the column.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function renameColumn($column_id, $column_name) {
        $config = array();
        $config["columnName"] = $column_name;
        $endpoint = $this->view_end_point . "/columns/" . $column_id;
        $this->ac->sendAPIRequest("PUT", $endpoint, $config, $this->req_headers);
    }

    /**
     * Delete a specified column in the table.
     * @param string $column_id Id of the column.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function deleteColumn($column_id, $config = array()) {
        $endpoint = $this->view_end_point . "/columns/" . $column_id;
        $this->ac->sendAPIRequest("DELETE", $endpoint, $config, $this->req_headers);
    }

    /**
     * Add a lookup in the specified child table.
     * @param string $column_id Id of the column.
     * @param string $ref_view_id The id of the table contains the parent column.
     * @param string $ref_column_id The id of the parent column.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function createLookup($column_id, $ref_view_id, $ref_column_id, $config = array()) {
        $endpoint = $this->view_end_point . "/columns/" . $column_id . "/lookup";
        $config["referenceViewId"] = $ref_view_id;
        $config["referenceColumnId"] = $ref_column_id;
        $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
    }

    /**
     * Remove the lookup for the specified column in the table.
     * @param string $column_id Id of the column.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function removeLookup($column_id, $config = array()) {
        $endpoint = $this->view_end_point . "/columns/" . $column_id . "/lookup";
        $this->ac->sendAPIRequest("DELETE", $endpoint, $config, $this->req_headers);
    }

    /**
     * Auto generate reports for the specified column.
     * @param string $column_id Id of the column.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function autoAnalyseColumn($column_id, $config = array()) {
        $endpoint = $this->view_end_point . "/columns/" . $column_id . "/autoanalyse";
        $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
    }

    /**
     * Hide the specified columns in the table.
     * @param array() $column_ids Ids of the columns to be hidden.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function hideColumns($column_ids) {
        $endpoint = $this->view_end_point . "/columns/hide";
        $config = array();
        $config["columnIds"] = $column_ids;
        $this->ac->sendAPIRequest("PUT", $endpoint, $config, $this->req_headers);
    }

    /**
     * Show the specified hidden columns in the table.
     * @param array() $column_ids Ids of the columns to be hidden.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function showColumns($column_ids) {
        $endpoint = $this->view_end_point . "/columns/show";
        $config = array();
        $config["columnIds"] = $column_ids;
        $this->ac->sendAPIRequest("PUT", $endpoint, $config, $this->req_headers);
    }

    /**
     * Sync data from available datasource for the specified view.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function refetchData($config = array()) {
        $endpoint = $this->view_end_point . "/sync";
        $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
    }

    /**
     * Returns last import details of the specified view.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Last data import details.
     */
    function getLastImportDetails() {
        $endpoint = $this->view_end_point . "/importdetails";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"];
    }

    /**
     * Returns list of all formula columns for the specified table.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Formula column list.
     */
    function getFormulaColumns() {
        $endpoint = $this->view_end_point . "/customformulas";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"]["customFormulas"];
    }

    /**
     * Add a formula column in the specified table.
     * @param string $formula_name Name of the formula column to be created.
     * @param string $expression Formula expression.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string Created column id.
     */
    function addFormulaColumn($formula_name, $expression, $config = array()) {
        $endpoint = $this->view_end_point . "/customformulas";
        $config["formulaName"] = $formula_name;
        $config["expression"] = $expression;
        $response = $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
        return $response["data"]["formulaId"];
    }

    /**
     * Edit the specified formula column.
     * @param string $formula_id Id of the formula column to be updated.
     * @param string $expression Formula expression.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function editFormulaColumn($formula_id, $expression, $config = array()) {
        $endpoint = $this->view_end_point . "/customformulas/" . $formula_id;
        $config["expression"] = $expression;
        $this->ac->sendAPIRequest("PUT", $endpoint, $config, $this->req_headers);
    }

    /**
     * Delete the specified formula column.
     * @param string $formula_id Id of the formula column to be deleted.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function deleteFormulaColumn($formula_id, $config = array()) {
        $endpoint = $this->view_end_point . "/customformulas/" . $formula_id;
        $this->ac->sendAPIRequest("DELETE", $endpoint, $config, $this->req_headers);
    }

    /**
     * Returns list of all aggregate formulas for the specified table.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Aggregate formula list.
     */
    function getAggregateFormulas() {
        $endpoint = $this->view_end_point . "/aggregateformulas";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"]["aggregateFormulas"];
    }

    /**
     * Add an aggregate formula in the specified table.
     * @param string $formula_name Name of the aggregate formula to be created.
     * @param string $expression Formula expression.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string Created column id.
     */
    function addAggregateFormula($formula_name, $expression, $config = array()) {
        $endpoint = $this->view_end_point . "/aggregateformulas";
        $config["formulaName"] = $formula_name;
        $config["expression"] = $expression;
        $response = $this->ac->sendAPIRequest("POST", $endpoint, $config, $this->req_headers);
        return $response["data"]["formulaId"];
    }

    /**
     * Edit the specified aggregate formula.
     * @param string $formula_id Id of the aggregate formula to be updated.
     * @param string $expression Formula expression.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function editAggregateFormula($formula_id, $expression, $config = array()) {
        $endpoint = $this->view_end_point . "/aggregateformulas/" . $formula_id;
        $config["expression"] = $expression;
        $this->ac->sendAPIRequest("PUT", $endpoint, $config, $this->req_headers);
    }

    /**
     * Delete the specified aggregate formula.
     * @param string $formula_id Id of the aggregate formula to be deleted.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function deleteAggregateFormula($formula_id, $config = array()) {
        $endpoint = $this->view_end_point . "/aggregateformulas/" . $formula_id;
        $this->ac->sendAPIRequest("DELETE", $endpoint, $config, $this->req_headers);
    }

    /**
     * Returns list of dependents views for the specified view.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Dependent view list.
     */
    function getViewDependents() {
        $endpoint = $this->view_end_point . "/dependents";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"]["views"];
    }

    /**
     * Returns dependent details for the specified column.
     * @param string $formula_id Id of the formula column to be updated.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Dependent details.
     */
    function getColumnDependents($column_id) {
        $endpoint = $this->view_end_point . "/columns/" . $column_id . "/dependents";
        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"];
    }

    /**
     * Update the existing sharing configuration for the specified view.
     * @param $config Contains the control parameters.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function updateSharedDetails($config = array()) {
        $endpoint = $this->view_end_point . "/share";
        $this->ac->sendAPIRequest("PUT", $endpoint, $config, $this->req_headers);
    }
}

class BulkAPI extends AnalyticsClient
{
    private $ac;
    private $data_end_point;
    private $bulk_data_end_point;
    private $req_headers = array();

    /**
     * Creates a new BulkAPI instance.
     * @param AnalyticsClient $ac AnalyticsClient instance.
     * @param string $org_id The ID of the organization.
     * @param string $workspace_id The ID of the workspace.
     */
    function __construct($ac, $org_id, $workspace_id) {
        $this->ac = $ac;
        $this->data_end_point = "/restapi/v2/workspaces/" . $workspace_id;
        $this->bulk_data_end_point = "/restapi/v2/bulk/workspaces/" . $workspace_id;
        $this->req_headers[] = "ZANALYTICS-ORGID: " . $org_id;
    }

    /**
     * Create a new table and import the data contained in the mentioned file into the created table.
     * @param string $table_name Name of the new table to be created.
     * @param string $file_type Type of the file to be imported.
     * @param string $auto_identify Used to specify whether to auto identify the CSV format.
     * @param string $file_path Path of the file to be imported.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Import result.
     */
    function importDataInNewTable($table_name, $file_type, $auto_identify, $file_path, $config = array()) {
        $endpoint = $this->data_end_point . "/data";
        $config["tableName"] = $table_name;
        $config["fileType"] = $file_type;
        $config["autoIdentify"] = $auto_identify;

        $post_fields = array();
        $post_fields["CONFIG"] = json_encode($config);
        $file_data = explode('/', $file_path);
        $filename = end($file_data);
        $post_fields['FILE'] = new CURLFile($file_path, 'json/csv', $filename);

        $response = $this->ac->sendImportAPIRequest($endpoint, $post_fields, $this->req_headers);
        return $response["data"];
    }

    /**
     * Import the data contained in the mentioned file into the table.
     * @param string $view_id Id of the view where the data to be imported.
     * @param string $import_type The type of import. Can be one of - append, truncateadd, updateadd.
     * @param string $file_type Type of the file to be imported.
     * @param string $auto_identify Used to specify whether to auto identify the CSV format.
     * @param string $file_path Path of the file to be imported.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Import result.
     */
    function importData($view_id, $import_type, $file_type, $auto_identify, $file_path, $config = array()) {
        $endpoint = $this->data_end_point . "/views/" . $view_id . "/data";
        $config["importType"] = $import_type;
        $config["fileType"] = $file_type;
        $config["autoIdentify"] = $auto_identify;

        $post_fields = array();
        $post_fields["CONFIG"] = json_encode($config);
        $file_data = explode('/', $file_path);
        $filename = end($file_data);
        $post_fields['FILE'] = new CURLFile($file_path, 'json/csv', $filename);

        $response = $this->ac->sendImportAPIRequest($endpoint, $post_fields, $this->req_headers);
        return $response["data"];
    }

    /**
     * Create a new table and import the raw data provided into the created table.
     * @param string $table_name Name of the new table to be created.
     * @param string $file_type Type of the file to be imported.
     * @param string $auto_identify Used to specify whether to auto identify the CSV format.
     * @param string $data Raw data to be imported.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Import result.
     */
    function importRawDataInNewTable($table_name, $file_type, $auto_identify, $data, $config = array()) {
        $endpoint = $this->data_end_point . "/data";
        $config["tableName"] = $table_name;
        $config["fileType"] = $file_type;
        $config["autoIdentify"] = $auto_identify;

        $post_fields = array();
        $post_fields["CONFIG"] = json_encode($config);
        $post_fields['DATA'] = $data;

        $response = $this->ac->sendImportAPIRequest($endpoint, $post_fields, $this->req_headers);
        return $response["data"];
    }

    /**
     * Import the data contained in the mentioned file into the table.
     * @param string $view_id Id of the view where the data to be imported.
     * @param string $import_type The type of import. Can be one of - append, truncateadd, updateadd.
     * @param string $file_type Type of the file to be imported.
     * @param string $auto_identify Used to specify whether to auto identify the CSV format.
     * @param string $data Raw data to be imported.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Import result.
     */
    function importRawData($view_id, $import_type, $file_type, $auto_identify, $data, $config = array()) {
        $endpoint = $this->data_end_point . "/views/" . $view_id . "/data";
        $config["importType"] = $import_type;
        $config["fileType"] = $file_type;
        $config["autoIdentify"] = $auto_identify;

        $post_fields = array();
        $post_fields["CONFIG"] = json_encode($config);
        $post_fields['DATA'] = $data;

        $response = $this->ac->sendImportAPIRequest($endpoint, $post_fields, $this->req_headers);
        return $response["data"];
    }

    /**
     * Asynchronously create a new table and import the data contained in the mentioned file into the created table.
     * @param string $table_name Name of the new table to be created.
     * @param string $file_type Type of the file to be imported.
     * @param string $auto_identify Used to specify whether to auto identify the CSV format.
     * @param string $file_path Path of the file to be imported.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string Import job id.
     */
    function importBulkDataInNewTable($table_name, $file_type, $auto_identify, $file_path, $config = array()) {
        $endpoint = $this->bulk_data_end_point . "/data";
        $config["tableName"] = $table_name;
        $config["fileType"] = $file_type;
        $config["autoIdentify"] = $auto_identify;

        $post_fields = array();
        $post_fields["CONFIG"] = json_encode($config);
        $file_data = explode('/', $file_path);
        $filename = end($file_data);
        $post_fields['FILE'] = new CURLFile($file_path, 'json/csv', $filename);

        $response = $this->ac->sendImportAPIRequest($endpoint, $post_fields, $this->req_headers);
        return $response["data"]["jobId"];
    }

    /**
     * Asynchronously import the data contained in the mentioned file into the table.
     * @param string $view_id Id of the view where the data to be imported.
     * @param string $import_type The type of import. Can be one of - append, truncateadd, updateadd.
     * @param string $file_type Type of the file to be imported.
     * @param string $auto_identify Used to specify whether to auto identify the CSV format.
     * @param string $file_path Path of the file to be imported.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string Import job id.
     */
    function importBulkData($view_id, $import_type, $file_type, $auto_identify, $file_path, $config = array()) {
        $endpoint = $this->bulk_data_end_point . "/views/" . $view_id . "/data";
        $config["importType"] = $import_type;
        $config["fileType"] = $file_type;
        $config["autoIdentify"] = $auto_identify;

        $post_fields = array();
        $post_fields["CONFIG"] = json_encode($config);
        $file_data = explode('/', $file_path);
        $filename = end($file_data);
        $post_fields['FILE'] = new CURLFile($file_path, 'json/csv', $filename);

        $response = $this->ac->sendImportAPIRequest($endpoint, $post_fields, $this->req_headers);
        return $response["data"]["jobId"];
    }

    /**
     * Asynchronously create a new table and import the data contained in the mentioned file into the created table.
     * @param string $table_name Name of the new table to be created.
     * @param string $auto_identify Used to specify whether to auto identify the CSV format.
     * @param string $file_path Path of the file to be imported.
     * @param int $batch_size Number of lines per batch.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string Import job id.
     */
    function importBulkDataInNewTableAsBatches($table_name, $auto_identify, $file_path, $batch_size, $config = array(), $tool_config = array()) {
        $endpoint = $this->bulk_data_end_point . "/data" . "/batch";
        $config["tableName"] = $table_name;
        $config["autoIdentify"] = $auto_identify;
        $response = $this->ac->sendBatchImportAPIRequest($endpoint, $file_path, $batch_size, $config, $this->req_headers);
        return $response["data"]["jobId"];
    }

    /**
     * Asynchronously import the data contained in the mentioned file into the table.
     * @param string $view_id Id of the view where the data to be imported.
     * @param string $import_type The type of import. Can be one of - append, truncateadd, updateadd.
     * @param string $auto_identify Used to specify whether to auto identify the CSV format.
     * @param string $file_path Path of the file to be imported.
     * @param int $batch_size Number of lines per batch.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string Import job id.
     */
    function importBulkDataAsBatches($view_id, $import_type, $auto_identify, $file_path, $batch_size, $config = array()) {
        $endpoint = $this->bulk_data_end_point . "/views/" . $view_id . "/data" . "/batch";
        $config["importType"] = $import_type;
        $config["autoIdentify"] = $auto_identify;
        $response = $this->ac->sendBatchImportAPIRequest($endpoint, $file_path, $batch_size, $config, $this->req_headers);
        return $response["data"]["jobId"];
    }

    /**
     * Returns the details of the import job.
     * @param string $job_id Id of the job.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Import job details.
     */
    function getImportJobDetails($job_id) {
        $endpoint = $this->bulk_data_end_point . "/importjobs/" . $job_id;

        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"];
    }

    /**
     * Export the mentioned table (or) view data.
     * @param string $view_id Id of the view to be exported.
     * @param string $response_format The format in which the data is to be exported.
     * @param string $file_path Path of the file where the data exported to be stored.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function exportData($view_id, $response_format, $file_path, $config = array()) {
        $endpoint = $this->data_end_point . "/views/" . $view_id . "/data";
        $config["responseFormat"] = $response_format;

        $this->ac->sendExportAPIRequest($file_path, $endpoint, $config, $this->req_headers);
    }

    /**
     * Initiate asynchronous export for the mentioned table (or) view data.
     * @param string $view_id Id of the view to be exported.
     * @param string $response_format The format in which the data is to be exported.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string Export job id.
     */
    function initiateBulkExport($view_id, $response_format, $config = array()) {
        $endpoint = $this->bulk_data_end_point . "/views/" . $view_id . "/data";
        $config["responseFormat"] = $response_format;

        $response = $this->ac->sendAPIRequest("GET", $endpoint, $config, $this->req_headers);
        return $response["data"]["jobId"];
    }

    /**
     * Initiate asynchronous export with the given SQL Query.
     * @param string $sql_query The SQL Query whose output is exported.
     * @param string $response_format The format in which the data is to be exported.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return string Export job id.
     */
    function initiateBulkExportUsingSQL($sql_query, $response_format, $config = array()) {
        $endpoint = $this->bulk_data_end_point . "/data";
        $config["sqlQuery"] = $sql_query;
        $config["responseFormat"] = $response_format;

        $response = $this->ac->sendAPIRequest("GET", $endpoint, $config, $this->req_headers);
        return $response["data"]["jobId"];
    }

    /**
     * Returns the details of the export job.
     * @param string $job_id Id of the job.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     * @return array() Export job details.
     */
    function getExportJobDetails($job_id) {
        $endpoint = $this->bulk_data_end_point . "/exportjobs/" . $job_id;

        $response = $this->ac->sendAPIRequest("GET", $endpoint, NULL, $this->req_headers);
        return $response["data"];
    }

    /**
     * Download the exported data for the mentioned job id.
     * @param string $job_id Id of the job.
     * @param string $file_path Path of the file where the data exported to be stored.
     * @param array() $config Contains any additional control parameters. Can be null.
     * @throws IOException If any communication related error(s) like request time out occurs when trying to contact the service.
     * @throws ServerException If the server has received the request but did not process the request due to some error.
     * @throws ParseException If the server has responded but client was not able to parse the response.
     */
    function exportBulkData($job_id, $file_path, $config = array()) {
        $endpoint = $this->bulk_data_end_point . "/exportjobs/" . $job_id . "/data";

        $this->ac->sendExportAPIRequest($file_path, $endpoint, NULL, $this->req_headers);
    }
}


/**
 *ServerException is thrown if the report server has received the request but did not process the request due to some error.
 */
class ServerException extends \Exception
{
    /**
     * @var int The error code sent by the server.
     */
    private $error_code;
    /**
     * @var string The error message sent by the server.
     */
    private $error_message;

    /**
     * @internal Creates a new Server_Exception instance.
     */
    function __construct($resp_content, $is_iam_error){
        $json_response = json_decode($resp_content, TRUE);
        if(json_last_error() != JSON_ERROR_NONE) {
            $resp_content = stripslashes($resp_content);
            $json_response = json_decode($resp_content, TRUE);
        }
        if(json_last_error()){
            throw new ParseException("API response is not proper. Invalid JSON data. Response - " . $resp_content);
        }

        if($is_iam_error){
            $this->error_message = "Exception while generating oauth token. Response - " . $resp_content;
            $this->error_code = 0;
        }
        else{
            $this->error_message = $json_response["data"]["errorMessage"];
            $this->error_code = $json_response["data"]["errorCode"];
        }

    }

    /**
     * Get the error message sent by the server.
     * @return string The error message.
     */
    function getErrorMessage() {
        return $this->error_message;
    }

    /**
     * Get the error code sent by the server.
     * @return int The error code.
     */
    function getErrorCode() {
        return $this->error_code;
    }
}

/**
 * ParseException is thrown if the server has responded but client was not able to parse the response. Possible reasons could be version mismatch.The client might have to be updated to a newer version.
 */
class ParseException extends \Exception
{
    /**
     * @var string The error message sent by the server.
     */
    private $error_message;

    /**
     * @internal Creates a new Parse_Exception instance.
     */
    function __construct($error_message){
        $this->error_message = $error_message;
    }

    /**
     * Get the complete response content as sent by the server.
     * @return string The complete response content.
     */
    function getErrorMessage(){
        return $this->error_message;
    }
}

/**
 *IOException is thrown when an input or output operation is failed or interpreted.
 */

class IOException extends \Exception
{
    /**
     * @var string The error message sent by the server.
     */
    private $error_message;

    /**
     * @internal Creates a new IO_Exception instance.
     */
    function __construct($error_message){
        $this->error_message = $error_message;
    }

    /**
     * Get the complete response content as sent by the server.
     * @return string The complete response content.
     */
    function getErrorMessage(){
        return $this->error_message;
    }
}
