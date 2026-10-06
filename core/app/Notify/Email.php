<?php

namespace App\Notify;

use App\Notify\NotifyProcess;
use App\Notify\Notifiable;
use Illuminate\Support\Facades\Config;
use Mailjet\Client;
use Mailjet\Resources;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use SendGrid;
use SendGrid\Mail\Mail;

class Email extends NotifyProcess implements Notifiable
{

	/**
	 * Email of receiver
	 *
	 * @var string
	 */
	public $email;

	/**
	 * Assign value to properties
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->statusField = 'email_status';
		$this->body = 'email_body';
		$this->globalTemplate = 'email_template';
		$this->notifyConfig = 'mail_config';
	}

	/**
	 * Send notification
	 *
	 * @return void|bool
	 */
	public function send()
	{

		//get message from parent
		$message = $this->getMessage();
		if (gs('en') && $message) {
			//Send mail
			$methodName = gs('mail_config')->name;
			$method = $this->mailMethods($methodName);
			try {
				$this->$method();
				$this->createLog('email');
			} catch (\Exception $e) {
				$this->createErrorLog($e->getMessage());
				session()->flash('mail_error', $e->getMessage());
			}
		}
	}

	/**
	 * Get the method name
	 *
	 * @return string
	 */
	protected function mailMethods($name)
	{
		$methods = [
			'php'      => 'sendPhpMail',
			'smtp'     => 'sendSmtpMail',
			'sendgrid' => 'sendSendGridMail',
			'mailjet'  => 'sendMailjetMail',
		];
		return $methods[$name];
	}

	protected function sendPhpMail()
	{
		$sentFromName = $this->getEmailFrom()['name'];
		$sentFromEmail = $this->getEmailFrom()['email'];
		$headers = "From: $sentFromName <$sentFromEmail> \r\n";
		$headers .= "From: $sentFromName <$sentFromEmail> \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=utf-8\r\n";
		@mail($this->email, $this->subject, $this->finalMessage, $headers);
	}

	protected function sendSmtpMail()
	{
        try{

            $config = gs('mail_config');
//            $config = array(
//                'driver' => 'smtp',
//                'host' => $config->host,
//                'port' => $config->port,
//                'from' => array('address' => $this->getEmailFrom()['email'], 'name' => $this->getEmailFrom()['name']),
//                'encryption' => $config->enc,
//                'username' => $config->username,
//                'password' => $config->password,
//            );
//
//            $configsss = array(
//                'driver' => 'smtp',
//                'host' => 'NyseFinancejp.com',
//                'port' => 465,
//                'from' => array('address' => 'support@NyseFinancejp.com', 'name' => 'NyseFinanceJP'),
//                'encryption' => 'ssl',
//                'username' => 'support@NyseFinancejp.com',
//                'password' => 'BAp(FH2%L.Zv',
//            );

            $configsss = array(
                'driver' => 'smtp',
                'host' => 'nysefinance.com',
                'port' => 587,
                'from' => array('address' => 'info@nysefinance.com', 'name' => 'NyseFinance'),
                'encryption' => 'tls',
                'username' => 'info@nysefinance.com',
                'password' => 'Hc5eA(GUpxLdW{NW',
            );
            
            // $cA24KEI5)^JDwNG
            
            // info #$(LV$zG6Ba75qj6
            // support $cA24KEI5)^JDwNG
            // zaifstock 3d[1T]0!IXIgq{z4
            

            Config::set('mail', $configsss);



            \Illuminate\Support\Facades\Mail::send('sendMail', ['htmlData' => $this->finalMessage], function ($message) {
                $message->to($this->email)->subject
                ($this->subject);
            });

//            $mail = new PHPMailer(true);
//            //Server settings
//            $mail->isSMTP();
//            $mail->Host       = $config->host;
//            $mail->SMTPAuth   = true;
//            $mail->Username   = $config->username;
//            $mail->Password   = $config->password;
//            if ($config->enc == 'ssl') {
//                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
//            } else {
//                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
//            }
//            $mail->Port       = $config->port;
//            $mail->CharSet = 'UTF-8';
//            //Recipients
//            $mail->setFrom($this->getEmailFrom()['email'], $this->getEmailFrom()['name']);
//            $mail->addAddress($this->email, $this->receiverName);
//            $mail->addReplyTo($this->getEmailFrom()['email'], $this->getEmailFrom()['name']);
//            // Content
//            $mail->isHTML(true);
//            $mail->Subject = $this->subject;
//            $mail->Body    = $this->finalMessage;
//            $mail->send();
        }catch(\Exception $ex){

        }
	}

	protected function sendSendGridMail()
	{
		$sendgridMail = new Mail();
		$sendgridMail->setFrom($this->getEmailFrom()['email'], $this->getEmailFrom()['name']);
		$sendgridMail->setSubject($this->subject);
		$sendgridMail->addTo($this->email, $this->receiverName);
		$sendgridMail->addContent("text/html", $this->finalMessage);
		$sendgrid = new SendGrid(gs('mail_config')->appkey);
		$response = $sendgrid->send($sendgridMail);
		if ($response->statusCode() != 202) {
			throw new Exception(json_decode($response->body())->errors[0]->message);
		}
	}

	protected function sendMailjetMail()
	{
		$mj = new Client(gs('mail_config')->public_key, gs('mail_config')->secret_key, true, ['version' => 'v3.1']);
		$body = [
			'Messages' => [
				[
					'From' => [
						'Email' => $this->getEmailFrom()['email'],
						'Name' => $this->getEmailFrom()['name'],
					],
					'To' => [
						[
							'Email' => $this->email,
							'Name' => $this->receiverName,
						]
					],
					'Subject' => $this->subject,
					'TextPart' => "",
					'HTMLPart' => $this->finalMessage,
				]
			]
		];
		$response = $mj->post(Resources::$Email, ['body' => $body]);
	}

	/**
	 * Configure some properties
	 *
	 * @return void
	 */
	public function prevConfiguration()
	{
		if ($this->user) {
			$this->email = $this->user->email;
			$this->receiverName = $this->user->fullname;
		}
		$this->toAddress = $this->email;
	}

	private function getEmailFrom()
	{
		$this->sentFrom = $this->template->email_sent_from_address ?? gs('email_from');
		return [
			'email' => $this->sentFrom,
			'name' => $this->replaceTemplateShortCode($this->template->email_sent_from_name ?? gs('site_name')),
		];
	}
}
