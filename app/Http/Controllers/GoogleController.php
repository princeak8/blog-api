<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\HTTP\JsonResponse;

use App\Services\ReaderService;
use App\Http\Resources\ReaderResource;

class GoogleController extends Controller
{
    private $readerService;

    /**
     * Create a new GoogleController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->readerService = new ReaderService;
    }
        /**
     * Gets a google client
     *
     * @return \Google_Client
     * INCOMPLETE
     */
    private function getClient():\Google_Client
    {
        try{
            // load our config.json that contains our credentials for accessing google's api as a json string
            $configJson = base_path().'/config.json';

            // define an application name
            $applicationName = 'blog';

            // create the client
            $client = new \Google_Client();
            $client->setApplicationName($applicationName);
            $client->setAuthConfig($configJson);
            $client->setAccessType('offline'); // necessary for getting the refresh token
            $client->setApprovalPrompt ('force'); // necessary for getting the refresh token
            // scopes determine what google endpoints we can access. keep it simple for now.
            $client->setScopes(
                [
                    \Google\Service\Oauth2::USERINFO_PROFILE,
                    \Google\Service\Oauth2::USERINFO_EMAIL,
                    \Google\Service\Oauth2::OPENID,
                ]
            );
            $client->setIncludeGrantedScopes(true);
            return $client;
        }catch(\Throwable $th) {
            \Log::stack(['project'])->info('could not get google client '.$th->getMessage().' in '.$th->getFile().' at Line '.$th->getLine());
            return response()->json([
                'statusCode' => 500,
                'message' => 'An error occured.. could not get google client.. please contact the administrator'
            ], 500);
        }
    } // getClient


    /**
     * Return the url of the google auth.
     * FE should call this and then direct to this url.
     *
     * @return JsonResponse
     * INCOMPLETE
     */
    public function getAuthUrl(Request $request):JsonResponse
    {
        try{
            /**
             * Create google client
             */
            $client = $this->getClient();

            /**
             * Generate the url at google we redirect to
             */
            $authUrl = $client->createAuthUrl();

            /**
             * HTTP 200
             */
            //return response()->json($authUrl, 200);
            return response()->json([
                'statusCode' => 200,
                'data' => $authUrl
            ], 200);
        }catch(\Throwable $th) {
            \Log::stack(['project'])->info('could not get google Login url '.$th->getMessage().' in '.$th->getFile().' at Line '.$th->getLine());
            return response()->json([
                'statusCode' => 500,
                'message' => 'An error occured.. could not get google Login url.. please contact the administrator'
            ], 500);
        }
    } // getAuthUrl


    /**
     * Login and register
     * Gets registration data by calling google Oauth2 service
     *
     * @return JsonResponse
     */
    public function postLogin(Request $request):JsonResponse
    {
        try{
            $post = $request->all();
            /**
             * Get authcode from the query string
             * Url decode if necessary
             */
            if(isset($post['auth_code'])) {
                $authCode = urldecode($post['auth_code']);

                /**
                 * Google client
                 */
                $client = $this->getClient();

                /**
                 * Exchange auth code for access token
                 * Note: if we set 'access type' to 'force' and our access is 'offline', we get a refresh token. we want that.
                 */
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

                /**
                 * Set the access token with google. nb json
                 */
                //dd(json_encode($accessToken));
                $client->setAccessToken(json_encode($accessToken));

                /**
                 * Get user's data from google
                 */
                $service = new \Google\Service\Oauth2($client);
                $userFromGoogle = $service->userinfo->get();

                /**
                 * Select user if already exists
                 */
                $reader = $this->readerService->getReaderByProvider('google', $userFromGoogle->id);
                /**
                 */
                if (!$reader) {
                    if($this->readerService->getReaderByEmail($userFromGoogle->email)) {
                        return response()->json([
                            'statusCode' => 403,
                            'message' => 'user already exists with this email, please login'
                        ], 403);
                    }
                    $data = [
                            'provider_id' => $userFromGoogle->id,
                            'provider_name' => 'google',
                            // 'google_access_token_json' => json_encode($accessToken),
                            'name' => $userFromGoogle->name,
                            'email' => $userFromGoogle->email,
                            //'avatar' => $providerUser->picture, // in case you have an avatar and want to use google's
                        ];
                    $reader = $this->readerService->saveGoogleUser($data);
                }

                /**
                 * Log in and return token
                 * HTTP 201
                 */
                //$token = $reader->createToken("Google")->accessToken;
                //Attempt to login the reader automatically
                $token = Auth::guard('reader')->login($reader);
                $user = new ReaderResource($reader);
                return response()->json([
                    'statusCode' => 200,
                    'data' => [
                        'token' => $token,
                        'token_type' => 'bearer',
                        'token_expires_in' => Auth::guard('reader')->factory()->getTTL() * 60, 
                        'user' => $user
                    ]
                ], 200);
                //return response()->json($token, 201);
            }else{
                return response()->json([
                    'statusCode' => 402,
                    'message' => 'code is required'
                ], 402);
            }
        }catch(\Exception $e) {
            \Log::stack(['project'])->info('could not authenticate google user '.$e->getMessage().' in '.$e->getFile().' at Line '.$e->getLine());
            return response()->json([
                'statusCode' => 500,
                'message' => 'An error occured.. could not authenticate google user.. please contact the administrator'
            ], 500);
        }
    } // postLogin

}
