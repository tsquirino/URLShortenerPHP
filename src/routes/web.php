<?php

use App\URL;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Auxiliary function: check if string has a valid date
 */
function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

/**
 * Home page
 */
Route::get('/', function (Request $request) {

    $messages = [];
    
    // If user is not logged in, redirect to login page
    if (!Session::has('username')) {
        return redirect()->route('login');
    }

    // Define current page for pagination (default: first page)
    $page = $request->input('page', '1');
    if (!ctype_digit($page)) {
        array_push($messages, 'Requested page is not valid. Displaying first page as default.');
        $page = 1;
    }
    else {
        $page = +$page;
    }

    // Define entries per page for pagination (default: 5)
    $limit = $request->input('limit', '5');
    if (!ctype_digit($limit)) {
        array_push($messages, 'Requested number of items per page is not valid. Setting 5 items per page as default.');
        $limit = '5';
    }
    else {
        $limit = +$limit;
    }

    // Query URLs in database
    $items = URL::offset(($page - 1) * $limit)->limit($limit)->get();
    $urls = [];
    foreach ($items as $item) {
        array_push($urls, ['id' => $item->id,
                            'shortened_url' => $item->shortened_url,
                            'original_url' => $item->original_url,
                            'expiration_date' => $item->expiration_date]);
    }

    // Evaluate number of pages
    $total = URL::count();
    $last = intdiv($total, $limit);
    if ($total % $limit > 0) {
        $last += 1;
    }

    // Paging links
    $format = '%s?page=%d&limit=%d';
    $base_url = route('home');
    $links = [
        'self' => sprintf($format, $base_url, $page, $limit),
        'first' => sprintf($format, $base_url, 1, $limit),
        'last' => sprintf($format, $base_url, $last, $limit)
    ];
    if ($page > 1) {
        $links['prev'] = sprintf($format, $base_url, $page - 1, $limit);
    }
    if ($page < $last) {
        $links['next'] = sprintf($format, $base_url, $page + 1, $limit);
    }

    // Structure with pagination data
    $pagination = [
        '_links' => $links,
        'current' => $page,
        'total' => $last
    ];

    // Otherwise, proceed to home page
    return view('home')->with(['messages' => $messages, 'pagination' => $pagination, 'urls' => $urls]);

})->name('home');

/**
 * Logout
 */
Route::get('/logout', function () {

    // End current session and go to home page
    Session::forget('username');
    return redirect()->route('home');

})->name('logout');

/**
 * Login page
 */
Route::get('/login', function () {

    // If user is already logged in, redirect to home page
    if (Session::has('username')) {
        return redirect()->route('home');
    }
    
    // Load login page
    return view('login');

})->name('login');

/**
 * Short URL redirection
 */
Route::get('/{code}', function ($code) {

    // Check if given code exists in database
    $search = URL::where('shortened_url', $code)->first();
    if (empty($search)) {
        abort(404);
    }

    // Redirect to original page
    $url = $search->original_url;
    return Redirect::to($url);

})->name('code');

/**
 * Login request
 */
Route::post('/login', function (Request $request) {

    // Get user inputs
    $username = $request->input('username');
    $password = $request->input('password');
    
    // If credentials are not valid, stay in login page
    if ($username !== 'admin' || $password !== '123456') {
        return view('login')->with('messages', ['Invalid credentials.']);
    }

    // Start session and proceed to home page
    Session::put('username', $username);
    return redirect()->route('home');

})->name('login_post');
