<?php

namespace App\Http\Controllers;

use App\URL;
use Illuminate\Http\Request;

class URLController extends Controller {

    /**
     * @SWG\Post(
     *      path="/api/new",
     *      operationId="newURL",
     *      tags={"URL"},
     *      summary="Register new URL",
     *      description="Register a new short URL for redirection.",
     *      @SWG\Parameter(
     *          name="shortened_url",
     *          in="formData",
     *          description="Desired shortened URL (leave blank to generate random code).",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="original_url",
     *          in="formData",
     *          description="Original URL for redirection.",
     *          required=true,
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="expiration_date",
     *          in="formData",
     *          description="Expiration date (leave blank to set it as 7 days from now).",
     *          type="string"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="URL successfully registered.",
     *       ),
     *      @SWG\Response(
     *          response=400,
     *          description="URL could not be added to database.",
     *      )
     * )
     */
    public function __invoke(Request $request)
    {
        // Get request parameters
        $shortened_url = $request->input('shortened_url', '');
        $original_url = $request->input('original_url', '');
        $expiration_date = $request->input('expiration_date', '');

        // If a desired code was provided, check if it already exists
        if ($shortened_url !== '') {
            $results = URL::where('shortened_url', $shortened_url);
            if ($results->count() > 0) {
                return response(['error' => 'Desired short URL is already taken.'], 400)
                    ->header('Content-Type', 'application/json');
            }
        }

        // Generate random code if desired short URL is not given
        else {

            // Maximum of 100 iterations to generate code
            for ($i = 0; $i < 100; $i++) {

                // Update seed for random code generation
                srand(time());

                // Try to generate a new code
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $characters_length = strlen($characters);
                $random_string = '';
                for ($j = 0; $j < 10; $j++) {
                    $random_string .= $characters[rand(0, $characters_length - 1)];
                }

                // Exit loop if a new code was generated
                $results = URL::where('shortened_url', $random_string);
                if ($results->count() == 0) {
                    $shortened_url = $random_string;
                    break;
                }
            }

            // If a random code could not be generated, return error message
            if ($shortened_url == '') {
                return response(['error' => 'Could not generate a random short URL. Try again.'], 400)
                        ->header('Content-Type', 'application/json');
            }
        }

        // If no original URL was received, return error message
        if ($original_url == '') {
            return response(['error' => 'Could not process request. An original URL for redirection must be given.'], 400)
                    ->header('Content-Type', 'application/json');
        }

        // If received expiration date is not valid, return error message
        if ($expiration_date !== '' && !validateDate($expiration_date)) {
            return response(['error' => 'Could not process request. Expiration date must be in YYYY-mm-dd format.'], 400)
                        ->header('Content-Type', 'application/json');
        }

        // If an expiration date was not received, set it as 7 days from now
        elseif ($expiration_date == '') {
            $expiration_date = strtotime('+7 day');
            $expiration_date = date('Y-m-d', $expiration_date);
        }

        // Insert new URL into table
        $url = new URL;
        $url->shortened_url = $shortened_url;
        $url->original_url = $original_url;
        $url->expiration_date = $expiration_date;
        $url->save();

        // Return JSON with inserted data
        return response(['url' => route('home') . '/' . $shortened_url,
                            'data' => ['id' => $url->id,
                                        'shortened_url' => $shortened_url,
                                        'original_url' => $original_url,
                                        'expiration_date' => $expiration_date]
                            ], 200)->header('Content-Type', 'application/json');
    }
}
