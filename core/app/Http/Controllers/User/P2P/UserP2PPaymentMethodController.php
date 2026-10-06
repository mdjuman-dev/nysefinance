<?php

namespace App\Http\Controllers\User\P2P;

use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\Form;
use App\Models\P2P\PaymentMethod;
use App\Models\P2P\UserPaymentMethod;
use Illuminate\Http\Request;

class UserP2PPaymentMethodController extends Controller
{
    public function list()
    {
        return $this->page();
    }

    public function create()
    {
        return $this->page(['mode' => 'create']);
    }

    public function edit($id)
    {
        $paymentMethod = UserPaymentMethod::where('user_id', auth()->id())->where('id', $id)->firstOrFail();
        return $this->page(['mode' => 'edit', 'id' => $paymentMethod->id]);
    }

    /** One Vue page: the saved methods plus an add/edit sheet driven by each method's form. */
    private function page(array $editing = [])
    {
        $saved = UserPaymentMethod::where('user_id', auth()->id())->latest('id')->with('paymentMethod')->paginate(getPaginate(10));

        $methods = PaymentMethod::with('userData')->active()->orderBy('name')->get()->map(fn ($m) => [
            'id'     => $m->id,
            'name'   => __($m->name),
            'color'  => $m->branding_color ? '#' . ltrim($m->branding_color, '#') : null,
            'fields' => collect((array) @$m->userData->form_data)->map(fn ($field) => [
                'key'         => $field->label,
                'label'       => __($field->name),
                'type'        => $field->type,
                'required'    => $field->is_required === 'required',
                'options'     => array_values((array) ($field->options ?? [])),
                'instruction' => $field->instruction ?? null,
            ])->values(),
        ])->values();

        return \Inertia\Inertia::render('User/P2P/PaymentMethods', [
            'saved' => \App\Support\InertiaData::paginate($saved, fn ($p) => [
                'id'       => $p->id,
                'methodId' => $p->payment_method_id,
                'name'     => __(@$p->paymentMethod->name),
                'color'    => @$p->paymentMethod->branding_color ? '#' . ltrim($p->paymentMethod->branding_color, '#') : null,
                'remark'   => $p->remark,
                'data'     => collect((array) $p->user_data)->map(fn ($v) => [
                    'name'  => __(keyToTitle($v->name)),
                    'raw'   => $v->name,
                    'type'  => $v->type,
                    'value' => $v->type == 'checkbox' ? implode(', ', (array) ($v->value ?? [])) : ($v->type == 'file' ? null : (string) $v->value),
                    'values'=> $v->type == 'checkbox' ? array_values((array) ($v->value ?? [])) : null,
                    'file'  => $v->type == 'file' && $v->value ? route('user.download.attachment', encrypt(getFilePath('verify') . '/' . $v->value)) : null,
                ])->values(),
                'edit'     => route('user.p2p.payment.method.edit', $p->id),
                'save'     => route('user.p2p.payment.method.save', $p->id),
                'delete'   => route('user.p2p.payment.method.delete', $p->id),
            ]),
            'methods' => $methods,
            'editing' => $editing ?: null,
            'urls'    => [
                'list'  => route('user.p2p.payment.method.list'),
                'store' => route('user.p2p.payment.method.save'),
            ],
        ]);
    }
    public function save(Request $request, $id = 0)
    {
        
        $request->validate([
            'payment_method' => 'required|integer',
            'remark'         => 'nullable|string|max:255',
        ]);

        if (UserPaymentMethod::where('user_id', auth()->id())->where('payment_method_id', $request->payment_method)->exists() && !$id) {
            return $this->response("You have already added this payment method");
        }

        $paymentMethod = PaymentMethod::active()->where('id', $request->payment_method)->first();
        if (!$paymentMethod) {
            return $this->response("Payment method not found.");
        }
        $form          = Form::where('act', 'p2p_payment_method')->where('id', $paymentMethod->form_id)->first();
        $formData      = $form->form_data;

        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);

        $request->validate($validationRule);

        $userData = $formProcessor->processFormData($request, $formData);
        $user     = auth()->user();

        if ($id) {
            $gateway = UserPaymentMethod::where('user_id', $user->id)->findOrFail($id);
            $message = "P2P Payment Method updated successfully";
        } else {
            $gateway                    = new UserPaymentMethod();
            $gateway->user_id           = $user->id;
            $gateway->payment_method_id = $paymentMethod->id;
            $message                    = "P2P Payment Method added successfully";
        }
        $gateway->remark    = $request->remark;
        $gateway->user_data = $userData;
        $gateway->save();

        return $this->response($message, "success");
    }

    public function delete($id)
    {
        $paymentMethod = UserPaymentMethod::where('user_id', auth()->id())->where('id', $id)->firstOrFail();
        $paymentMethod->delete();

        return returnBack("Payment method deleted successfully", "success");
    }

    public function response($message, $type = "error")
    {
        if (request()->ajax()) {
            return jsonResponse($message, $type == 'success');
        } else {
            return returnBack($message, $type);
        }
    }
}
