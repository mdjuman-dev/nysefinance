<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Support\InertiaData;
use App\Traits\SupportTicketManager;
use Inertia\Inertia;

class TicketController extends Controller
{
    use SupportTicketManager {
        supportTicket as traitSupportTicket;
        openSupportTicket as traitOpenSupportTicket;
        viewTicket as traitViewTicket;
    }

    public function __construct()
    {
        parent::__construct();
        $this->layout = 'frontend';
        $this->redirectLink = 'ticket.view';
        $this->userType     = 'user';
        $this->column       = 'user_id';
        $this->user = auth()->user();
        if ($this->user) {
            $this->layout = 'master';
        }
    }

    // Logged-in users get the Vue pages; guests (tickets opened from the
    // contact form) keep the Blade views from the trait.

    public function supportTicket()
    {
        if (!$this->user) {
            return $this->traitSupportTicket();
        }

        $tickets = SupportTicket::where('user_id', $this->user->id)->orderBy('id', 'desc')->paginate(getPaginate());

        return Inertia::render('User/Support/Index', [
            'tickets' => InertiaData::paginate($tickets, fn ($t) => [
                'id'        => $t->id,
                'ticket'    => $t->ticket,
                'subject'   => __($t->subject),
                'status'    => $this->ticketStatus($t->status),
                'priority'  => $this->ticketPriority($t->priority),
                'lastReply' => InertiaData::date($t->last_reply ? \Carbon\Carbon::parse($t->last_reply) : null),
                'url'       => route('ticket.view', $t->ticket),
            ]),
            'urls' => ['open' => route('ticket.open')],
        ]);
    }

    public function openSupportTicket()
    {
        if (!$this->user) {
            return $this->traitOpenSupportTicket();
        }

        return Inertia::render('User/Support/Create', [
            'urls' => ['store' => route('ticket.store'), 'index' => route('ticket.index')],
        ]);
    }

    public function viewTicket($ticket)
    {
        if (!$this->user) {
            return $this->traitViewTicket($ticket);
        }

        $myTicket = SupportTicket::where('ticket', $ticket)->where('user_id', $this->user->id)->orderBy('id', 'desc')->firstOrFail();
        $messages = SupportMessage::where('support_ticket_id', $myTicket->id)->with('admin', 'attachments')->get();

        return Inertia::render('User/Support/View', [
            'ticket' => [
                'id'       => $myTicket->id,
                'ticket'   => $myTicket->ticket,
                'subject'  => __($myTicket->subject),
                'status'   => $this->ticketStatus($myTicket->status),
                'priority' => $this->ticketPriority($myTicket->priority),
                'closed'   => $myTicket->status == Status::TICKET_CLOSE,
                'name'     => $myTicket->name,
            ],
            'messages' => $messages->map(fn ($m) => [
                'id'      => $m->id,
                'mine'    => !$m->admin_id,
                'author'  => $m->admin_id ? ($m->admin->name ?? 'Support') : $myTicket->name,
                'message' => $m->message,
                'date'    => InertiaData::date($m->created_at),
                'files'   => $m->attachments->values()->map(fn ($a, $i) => [
                    'name' => 'Attachment ' . ($i + 1),
                    'url'  => route('ticket.download', encrypt($a->id)),
                ]),
            ]),
            'urls' => [
                'reply' => route('ticket.reply', $myTicket->id),
                'close' => route('ticket.close', $myTicket->id),
                'index' => route('ticket.index'),
            ],
        ]);
    }

    private function ticketStatus($status): string
    {
        return [Status::TICKET_OPEN => 'Open', Status::TICKET_ANSWER => 'Answered', Status::TICKET_REPLY => 'Customer reply', Status::TICKET_CLOSE => 'Closed'][$status] ?? 'Open';
    }

    private function ticketPriority($priority): string
    {
        return [Status::PRIORITY_LOW => 'Low', Status::PRIORITY_MEDIUM => 'Medium', Status::PRIORITY_HIGH => 'High'][$priority] ?? 'Low';
    }
}
