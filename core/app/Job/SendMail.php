<?php

namespace App\Job;


use App\Models\Currency;
use App\Models\StockTransaction;
use App\Models\StockWallet;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;



class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;



    private $email;
    private $subject;
    private $template;
    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct($email, $subject, $template)
    {
        $this->email=$email;
        $this->subject=$subject;
        $this->template=$template;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

//        $configsss = array(
//            'driver' => 'smtp',
//            'host' => 'NyseFinancejp.com',
//            'port' => 465,
//            'from' => array('address' => 'support@NyseFinancejp.com', 'name' => 'NyseFinanceJP'),
//            'encryption' => 'ssl',
//            'username' => 'support@NyseFinancejp.com',
//            'password' => 'BAp(FH2%L.Zv',
//        );

        $configsss = array(
            'driver' => 'smtp',
            'host' => 'smtp.hostinger.com',
            'port' => 465,
            'from' => array('address' => 'info@nysefinance.com', 'name' => 'NyseFinance'),
            'encryption' => 'ssl',
            'username' => 'info@nysefinance.com',
            'password' => '|hG5IlW9p',
        );


        Config::set('mail', $configsss);

        $template=$this->template;
        $email=$this->email;
        $subject=$this->subject;

        \Illuminate\Support\Facades\Mail::send('sendMail', ['htmlData' => $template], function ($message) use($email,$subject) {
            $message->to($email)->subject
            ($subject);
        });

    }

    public function failed(\Exception $exception)
    {





    }
}
