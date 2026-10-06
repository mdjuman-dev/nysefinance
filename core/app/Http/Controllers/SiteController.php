<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\AdminNotification;
use App\Models\CoinPair;
use App\Models\Currency;
use App\Models\Frontend;
use App\Models\Language;
use App\Models\Page;
use App\Models\Subscriber;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\UserStock;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use WebSocket\Client as webSocketClient;


class SiteController extends Controller
{

    public function ercLatestTransaction()
    {

        try{

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])
                ->post("https://eth-mainnet.g.alchemy.com/v2/82bmfGgtDTzuEcoHHG5Jt", [
                    "jsonrpc" => "2.0",
                    "method" => "eth_getBlockByNumber",
                    "params" => ["latest", true],
                    "id" => 1
                ]);

            $data = $response->json();
            $transactions = collect($data['result']['transactions'] ?? [])->take(10)->values();

            $allData=[];

            foreach ($transactions as $tx) {
                $allData[]=[
                    'url'=>"https://etherscan.io/tx/".$tx['hash'],
                    'trx_hash'=>substr($tx['hash'], 0, 12) . '...' . substr($tx['hash'], -6),
                    'time'=>'8 secs ago',
                    'from'=>substr($tx['from'], 0, 10) . '...' . substr($tx['from'], -6),
                    'to'=>isset($tx['to'])?substr($tx['to'], 0, 10) . '...' . substr($tx['to'], -6):'Contract Creation',
                    'value_amount'=>number_format(hexdec($tx['value']) / 1e18, 6).'ETH',
                ];
            }


            return response()->json(['status'=>'success', 'data'=>$allData]);

        }catch(\Exception $e){

            return response()->json(['status'=>'error', 'message'=>$e->getMessage()]);
        }

    }
    public function index(Request $request)
    {
        if (!config('app.public_home')) {
            return redirect()->route('user.login');
        }

        $reference = @$_GET['reference'];
        if ($reference) {
            session()->put('reference', $reference);
        }

        return Inertia::render('Home', [
            'markets' => $this->marketSnapshot(8),
        ]);
    }

    public function indexEn(Request $request)
    {


        try{
            $client = new Client(['verify' => false]);


            $response = $client->request('GET', "https://ipinfo.io/json?token=4adbcad940234f");

            // Decode the response body
            $locationData = $response->getBody()->getContents();
            $locationData = json_decode($locationData);
            $locationData = isset($locationData->country)?$locationData->country:'';

            if($locationData && strtolower($locationData) == 'jp'){
                return view('home_en_block');
            }
        }catch(\Exception $ex){

        }


        return view('home_en');
    }

    public function indexJjp()
    {
//        return redirect()->route('user.login');

        $reference = @$_GET['reference'];
        if ($reference) {
            session()->put('reference', $reference);
        }

        $pageTitle = 'Home';
        $sections = Page::where('tempname', activeTemplate())->where('slug', '/')->first();
        $seoContents = $sections->seo_content;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('home_jp', compact('pageTitle', 'sections', 'seoContents', 'seoImage'));
    }

    public function downloadApk()
    {
        $apkFile = public_path('NyseFinance.apk');


        return response()->download($apkFile);

    }

    public function pages($slug)
    {
//        return redirect()->route('user.login');

        $page = Page::where('tempname', activeTemplate())->where('slug', $slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections = $page->secs;
        $seoContents = $page->seo_content;
        return Inertia::render('Page', [
            'title'   => $pageTitle,
            'slug'    => $page->slug,
            'content' => $page->details ? json_decode($page->details) : null,
        ]);
    }


    public function contact()
    {
//        return redirect()->route('user.login');

        $pageTitle = "Contact Us";
        $user = auth()->user();
        $sections = Page::where('tempname', activeTemplate())->where('slug', 'contact')->first();
        $seoContents = $sections->seo_content;
        $content = getContent('contact_us.content', true);
        return Inertia::render('Contact', [
            'content' => [
                'heading'    => __(@$content->data_values->heading),
                'subheading' => __(@$content->data_values->subheading),
                'email'      => @$content->data_values->email,
                'mobile'     => @$content->data_values->mobile,
            ],
            'user' => $user ? ['name' => $user->fullname, 'email' => $user->email] : null,
        ]);
    }


    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        $request->session()->regenerateToken();

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $random = getNumber();

        $ticket = new SupportTicket();
        $ticket->user_id = auth()->id() ?? 0;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->priority = Status::PRIORITY_MEDIUM;


        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = Status::TICKET_OPEN;
        $ticket->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title = 'A new contact message has been submitted';
        $adminNotification->click_url = urlPath('admin.ticket.view', $ticket->id);
        $adminNotification->save();

        $message = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $notify[] = ['success', 'Ticket created successfully!'];

        session()->flash('notify', $notify);
        return Inertia::location(route('ticket.view', [$ticket->ticket]));
    }

    public function policyPages($slug)
    {
//        return redirect()->route('user.login');

        $page=Page::where('slug', $slug)->firstOrFail();
        $policy = Frontend::where('slug', $slug)->where('data_keys', 'policy_pages.element')->firstOrFail();
        $pageTitle = $policy->data_values->title;
        $seoContents = $policy->seo_content;
        $seoImage = @$seoContents->image ? frontendImage('policy_pages', $seoContents->image, getFileSize('seo'), true) : null;
        return Inertia::render('Page', [
            'title'   => $pageTitle,
            'slug'    => $slug,
            'content' => $policy->data_values->details,
        ]);
    }

    public function changeLanguage($lang = null)
    {
        return redirect()->route('user.login');

        $language = Language::where('code', $lang)->first();
        if (!$language) $lang = 'en';
        session()->put('lang', $lang);
        return back();
    }

    public function blogDetails($slug)
    {
        return redirect()->route('user.login');

        $blog = Frontend::where('slug', $slug)->where('data_keys', 'blog.element')->firstOrFail();
        $pageTitle = $blog->data_values->title;
        $seoContents = $blog->seo_content;
        $seoImage = @$seoContents->image ? frontendImage('blog', $seoContents->image, getFileSize('seo'), true) : null;
        return view('Template::blog_details', compact('blog', 'pageTitle', 'seoContents', 'seoImage'));
    }


    public function cookieAccept()
    {

        Cookie::queue('gdpr_cookie', gs('site_name'), 43200);
    }

    public function cookiePolicy()
    {
        return redirect()->route('user.login');

        $cookieContent = Frontend::where('data_keys', 'cookie.data')->first();
        abort_if($cookieContent->data_values->status != Status::ENABLE, 404);
        $pageTitle = 'Cookie Policy';
        $cookie = Frontend::where('data_keys', 'cookie.data')->first();
        return view('Template::cookie', compact('pageTitle', 'cookie'));
    }

    public function placeholderImage($size = null)
    {
        return redirect()->route('user.login');

        $imgWidth = explode('x', $size)[0];
        $imgHeight = explode('x', $size)[1];
        $text = $imgWidth . '×' . $imgHeight;
        $fontFile = realpath('assets/font/solaimanLipi_bold.ttf');
        $fontSize = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if ($imgHeight < 100 && $fontSize > 30) {
            $fontSize = 30;
        }

        $image = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $bgFill);
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX = ($imgWidth - $textWidth) / 2;
        $textY = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function maintenance()
    {
        $pageTitle = 'Maintenance Mode';
        if (gs('maintenance_mode') == Status::DISABLE) {
            return to_route('home');
        }
        $maintenance = Frontend::where('data_keys', 'maintenance.data')->first();
        return view('Template::maintenance', compact('pageTitle', 'maintenance'));
    }

    public function pusherAuthentication($socketId, $channelName)
    {
        $general = gs();
        $pusherSecret = @$general->pusher_config->pusher_app_secret;
        $str = $socketId . ":" . $channelName;
        $hash = hash_hmac('sha256', $str, $pusherSecret);

        return response()->json([
            'success' => true,
            'message' => "Pusher authentication successfully",
            'auth' => @$general->pusher_config->pusher_app_key . ":" . $hash,
        ]);
    }

    public function market()
    {
        return redirect()->route('user.login');

        $pageTitle = 'Market List';
        $sections = Page::where('tempname', activeTemplate())->where('slug', 'market')->first();
        return view('Template::market_list', compact('pageTitle', 'sections'));
    }

    public function crypto()
    {
        $pageTitle = 'Cryptocurrency';
        $sections = Page::where('tempname', activeTemplate())->where('slug', 'crypto-currency')->first();
        return Inertia::render('Crypto', [
            'listUrl' => route('crypto_currency.list'),
        ]);
    }

    public function marketList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:all,crypto,fiat',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
            ]);
        }

        $query = CoinPair::searchable(['symbol'])->select('id', 'market_id', 'coin_id', 'symbol');

        if ($request->type != 'all') {
            $query->whereHas('market', function ($q) use ($request) {
                $q->whereHas('currency', function ($c) use ($request) {
                    if ($request->type == 'crypto') {
                        return $c->crypto();
                    }
                    $c->fiat();
                });
            });
        }

        $query = $query->with('market:id,name,currency_id', 'coin:id,name,symbol,image', 'market.currency:id,name,symbol,image', 'marketData')
            ->withCount('trade as total_trade')
            ->orderBy('total_trade', 'desc');

        $total = (clone $query)->count();
        $pairs = (clone $query)->skip($request->skip ?? 0)
            ->take($request->limit ?? 20)
            ->get();

        return response()->json([
            'success' => true,
            'pairs' => $pairs,
            'total' => $total,
        ]);
    }

    public function cryptoCurrencyList(Request $request)
    {
        $query = Currency::active()->crypto()->with('marketData')->rankOrdering()
            ->searchable(['name', 'symbol']);

        $total = (clone $query)->count();
        $currencies = (clone $query)->skip($request->skip ?? 0)
            ->take($request->limit ?? 20)
            ->get();

        return response()->json([
            'success' => true,
            'currencies' => $currencies,
            'total' => $total,
        ]);
    }

    public function pwaConfiguration()
    {
        $gs = gs();
        $json = [
            "name" => $gs->site_name,
            "sign" => $gs->site_name,
            "start_url" => route('trade'),
            "display" => "standalone",
            "background_color" => "#5900b3",
            "theme_color" => "black",
            "description" => $gs->site_name . " PWA",
            "icons" => [
                [
                    "src" => getImage(getFilePath('logo_icon') . '/pwa_favicon.png'),
                    "sizes" => "192x192",
                    "type" => "image/png",
                ],
                [
                    "src" => getImage(getFilePath('logo_icon') . '/pwa_thumb.png'),
                    "sizes" => "512x512",
                    "type" => "image/png",
                ],
            ],
        ];

        return response()->json($json);
    }

    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribers,email',
        ], [
            'email.unique' => "You have already subscribed",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all(),
                'success' => false,
            ]);
        }

        $subscribe = new Subscriber();
        $subscribe->email = $request->email;
        $subscribe->save();

        return response()->json([
            'message' => "Thank you for subscribing us",
            'success' => true,
        ]);
    }


    public function about()
    {
        $pageTitle = "About Us";
        $page = Page::where('tempname', activeTemplate())->whereIn('slug', ['about-us', 'about'])->firstOrFail();

        return Inertia::render('About', [
            'content' => $page->details ? json_decode($page->details) : null,
            'markets' => $this->marketSnapshot(4),
        ]);
    }

    private function marketSnapshot(int $limit): array
    {
        return Currency::active()->crypto()->with('marketData')->rankOrdering()
            ->take($limit)->get()
            ->map(fn ($c) => [
                'name'      => $c->name,
                'symbol'    => $c->symbol,
                'image'     => $c->image_url,
                'price'     => (float) ($c->marketData->price ?? $c->rate),
                'change24h' => (float) ($c->marketData->percent_change_24h ?? 0),
                'marketCap' => (float) ($c->marketData->market_cap ?? 0),
                'volume'    => (float) ($c->marketData->volume_24h ?? 0),
            ])->all();
    }
}
