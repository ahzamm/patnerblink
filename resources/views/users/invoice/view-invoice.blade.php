<head>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.css">
    <style type="">
        @page { margin: 0px;}
        body { margin: 0px; }
        div{
            font-size: 93%;
        }
        th,
        td{
            font-size: 12px;
            padding:0px 10px;
            vertical-align: middle;
        }
    </style>
</head>
<div class="header__image">
    <img src="{{$get_image}}" alt="" class="w-100">
</div>
<div style="height: 10px;"></div>
<div class="container-fluid">
    <table style="width: 100%">
        <tbody>
            <tr>
                <td style="width: 100%; vertical-align: top;">
                    <table style="border:1px solid #0d4dab; width: 100%;">
                        <tbody>
                            <tr style="background-color: #0d4dab;color: #fff;">
                                <td colspan="2" style="text-align: center; vertical-align:middle; padding: 5px">Billing Summary</td>
                            </tr>
                            <tr>
                                <td style="padding:0px 10px;">Invoice Number:</td>
                                <td style="width: 80px">{{$get_invoice_data->id}}</td>
                            </tr>
                            <tr>
                                <td>Date:</td>
                                <td>{{date('d-M-Y', strtotime($get_invoice_data->date))}}</td>
                            </tr>
                            <tr>
                                <td><strong>Due Date:</strong></td>
                                <td><strong>{{date('d-M-Y', strtotime($get_invoice_data->date))}}</strong></td>
                            </tr>
                            <tr>
                                <td>Billing Month/Year:</td>
                                <td>{{$year}}</td>
                            </tr>
                            <tr>
                                <td><strong style="color:#0d4dab">Total Amount Due (PKR):</strong></td>
                                <td><strong style="color: #0d4dab">{{number_format($grand_total,2)}}</strong></td>
                            </tr>
                            <tr>
                                <td>
                                </td>
                                <td>
                                    <br>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="width: 100%; vertical-align: top;">
                    <table style="border:1px solid #0d4dab; width: 100%;">
                        <tbody>
                            <tr style="background-color: #0d4dab;color: #fff;">
                                <td colspan="2" style="text-align: center; vertical-align:middle;padding: 5px">Customer Detail</td>
                            </tr>
                            <tr>
                                <td>User ID:</td>
                                <td>{{$get_invoice_data->username}}</td>
                            </tr>
                            <tr>
                                <td>Name:</td>
                                <td style="vertical-align:top">{{substr($get_user_data->firstname.' '.$get_user_data->lastname,0,40)}}</td>
                            </tr>
                            <tr>
                                <td>Address:</td>
                                <td>{{ substr($get_user_data->address,0,40)}}</td>
                            </tr>
                            <tr>
                                <td>Cell #:</td>
                                <td>{{$get_user_data->mobilephone}}</td>
                            </tr>
                            <tr>
                                <td>Email:</td>
                                <td>{{$get_user_data->email}}</td>
                            </tr>
                            <tr>
                                <td>NTN/CNIC:</td>
                                <td>{{$get_user_data->nic}}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <div style="height: 10px;"></div> 
    <!-- Billing Info -->
    <table style="width: 100%; border:1px solid #0d4dab;padding: 0 10px;" >
        <tbody>
            <tr style="background-color: #0d4dab; color: #fff;">
                <th style="text-align: center;padding: 5px">Service</th>
                <th style="text-align: center">Billing Period</th>
                <th style="text-align: center">Charges (PKR)</th>
            </tr>
            @if($get_invoice_data->fll_tax > 0)
            <tr>
                <td style="border-left: 1px solid #fff; border-right: 1px solid #fff;" colspan="3">LOCAL LOOP (FLL)</td>
            </tr>
            <tr>
                <td style="border-top: 1px solid #0d4dab;border-bottom: 1px solid #0d4dab">Optical Line Rent:</td>
                <td style="border: 1px solid #0d4dab;text-align:center">{{$charge_on}}  to  {{$expire_on}}</td>
                <td style="border-top: 1px solid #0d4dab;border-bottom: 1px solid #0d4dab;text-align:right">{{number_format(round($get_invoice_data->fll_charges,2),2)}}</td>
            </tr>
            <tr>
                <td style="border-left: 1px solid #fff; border-right: 0; text-align:right" colspan="2"><strong>Total:</strong></td>
                <td style="border-left: 0; border-right: 1px solid #fff; text-align:right"><strong>{{number_format(round($get_invoice_data->fll_charges,2),2)}}</strong></td>
            </tr>
            @endif
            @if($get_invoice_data->cvas_tax > 0)
            <tr>
                <td colspan="3" style="border-left: 1px solid #fff; border-right: 1px solid #fff;">INTERNET (CVAS)</td>
            </tr>
            <tr>
                <td style="border-top: 1px solid #0d4dab;border-bottom: 1px solid #0d4dab">{{$get_invoice_data->name}}</td>
                <td style="border: 1px solid #0d4dab;text-align:center">{{$charge_on}}  to  {{$expire_on}}</td>
                <td style="border-top: 1px solid #0d4dab;border-bottom: 1px solid #0d4dab;text-align:right">{{number_format(round($get_invoice_data->cvas_charges,2),2)}}</td>
            </tr>
            <tr>
                <td style="border-left: 1px solid #fff; border-right: 0; text-align:right" colspan="2"><strong>Total:</strong></td>
                <td style="border-left: 0; border-right: 1px solid #fff; text-align:right"><strong>{{number_format(round($get_invoice_data->cvas_charges,2),2)}}</strong></td>
            </tr>
            @endif
            @if($get_invoice_data->tip_tax > 0)
            <tr>
                <td colspan="3" style="border-left: 1px solid #fff; border-right: 1px solid #fff;">INFRA (TIP)</td>
            </tr>
            <tr>
                <td style="border-top: 1px solid #0d4dab;border-bottom: 1px solid #0d4dab">Optical Infra Rent:</td>
                <td style="border: 1px solid #0d4dab;text-align:center">{{$charge_on}}  to  {{$expire_on}}</td>
                <td style="border-top: 1px solid #0d4dab;border-bottom: 1px solid #0d4dab;text-align:right">{{number_format(round($get_invoice_data->tip_charges,2),2)}}</td>
            </tr>
            <tr>
                <td style="border-left: 1px solid #fff; border-right: 0; text-align:right" colspan="2"><strong>Total:</strong></td>
                <td style="border-left: 0; border-right: 1px solid #fff; text-align:right"><strong>{{number_format(round($get_invoice_data->tip_charges,2),2)}}</strong></td>
            </tr>
            @endif
            @if($get_invoice_data->static_ip_amount > 0)
            <tr>
                <td colspan="3" style="border-left: 1px solid #fff; border-right: 1px solid #fff;">ADD-ON(S)</td>
            </tr>
            <tr>
                <td style="border-top: 1px solid #0d4dab;border-bottom: 1px solid #0d4dab">Static IPs:</td>
                <td style="border: 1px solid #0d4dab;text-align:center">{{$charge_on}}  to  {{$expire_on}}</td>
                <td style="border-top: 1px solid #0d4dab;border-bottom: 1px solid #0d4dab;text-align:right">{{number_format($static_ip,2)}}</td>
            </tr>
            <tr>
                <td style="border-left: 1px solid #fff; border-right: 0; text-align:right" colspan="2"><strong>Total:</strong></td>
                <td style="border-left: 0; border-right: 1px solid #fff; text-align:right"><strong>{{number_format($static_ip,2)}}</strong></td>
            </tr>
            @endif
            @if($get_invoice_data->fll_tax > 0 || $get_invoice_data->cvas_tax > 0 || $get_invoice_data->tip_tax > 0 || $get_invoice_data->static_ip_amount >  0)
            <tr>
                <td colspan="2" style="border-left: 1px solid #fff; border-right: 1px solid #fff;"><strong>Total</strong></td>
                <td style="border-left: 1px solid #fff; border-right: 1px solid #fff;text-align:right"><strong> {{number_format($total_amount_after_tax,2)}}</strong></td>
            </tr>
            @endif
            <tr>
                <td colspan="2" style="border-left: 1px solid #fff; border-right: 1px solid #fff;">Sindh Sales Tax</td>
                <td style="border-left: 1px solid #fff; border-right: 1px solid #fff;text-align:right">{{number_format($get_invoice_data->sst,2)}}</td>
            </tr>
            <tr>
                <td colspan="2" style="border-left: 1px solid #fff; border-right: 1px solid #fff;">Advance Income Tax</td>
                <td style="border-left: 1px solid #fff; border-right: 1px solid #fff;text-align:right">{{number_format($get_invoice_data->adv_tax,2)}}</td>
            </tr>
            <tr><td colspan="2" style="height: 20px;border-left: 1px solid #fff"></td><td style="border-right: 1px solid #fff"></td></tr>
            <tr>
                <td colspan="2" style="border-left: 1px solid #fff; border-right: 1px solid #fff;"><strong>Grand Total</strong></td>
                <td style="border-left: 1px solid #fff; border-right: 1px solid #fff;text-align:right"><strong>{{number_format($grand_total,2)}}</strong></td>
            </tr>
            <tr>
                <td colspan="2" style="border-left: 1px solid #fff; border-right: 1px solid #fff;">Carry Forward Amount</td>
                <td style="border-left: 1px solid #fff; border-right: 1px solid #fff;text-align:right">0</td>
            </tr>
            <tr>
                <td colspan="2" style="border-left: 1px solid #fff; border-right: 1px solid #fff;"><strong style="color: #0d4dab;">Total Amount Due</strong></td>
                <td style="border-left: 1px solid #fff; border-right: 1px solid #fff;text-align:right"><strong><u>{{number_format($grand_total,2)}}</u></strong></td>
            </tr>
            <tr>
                <td colspan="2" style="border-left: 1px solid #fff; border-right: 1px solid #fff;border-bottom: 1px solid #fff;"><strong>Payable Amount After Due Date</strong></td>
                <td style="border-left: 1px solid #fff; border-right: 1px solid #fff;border-bottom: 1px solid #fff;text-align:right"><strong><u>{{number_format($grand_total,2)}}</u></strong></td>
            </tr>
        </tbody>
    </table>
    <!-- Contact Info -->
    <hr style="border-top: 1px solid #0d4dab;">
    <table style="width: 100%" class="contact__info">
        <tbody>
            <tr><td><p style="margin-bottom: 0px; text-align:center;font-size: 12px;">please make all payment cheques in favor of <strong>Logon Broadband (Pvt.) Limited.</strong> Bank charges will be applicable.</p></td></tr>
            <tr><td><p style="margin-bottom: 0px; text-align:center; font-size: 12px;">For billing concerns, please contact us at <u style="color: blue">{{$get_domain_data->bm_invoice_email}}</u> {{$get_domain_data->bm_helpline_number}}</p></td></tr>
            <tr><td><p style="margin-bottom: 0px; text-align:center;font-size: 10px;">Note: Please pay all your outstanding dues before due date to avoid suspension of services.</p></td>
            </tr>
            <tr><td><p style="text-align:center; font-size: 10px;">Visit us: Executive Floor, Glass Tower, Main 3 Sword, Near PSO Head Office, Karachi. </p></td>
            </tr>
        </tbody>
    </table>
    <p style="text-align: center; font-size: 14px;color:#0d4dab;margin-bottom: 0; font-family: Arial"><strong><u>LOGON SMART PAYMENT SOLUTIONS</u></strong></p>
    <hr style="border-top: 1px solid #0d4dab; margin-top: 3px">
    <table style="width: 100%" class="payment__solution">
        <tbody>
            <tr>
                @foreach($get_bank_image as $bank)
                <td style="text-align: center"><img src="images/bank-images/{{$bank->image}}" alt="" style="width: 80px"></td> 
                @endforeach
            </tr>
        </tbody>
    </table>
    <hr style="border-top: 1px solid #0d4dab;margin-bottom: 5px">
    <table style="width: 100%">
        <tbody>
            <tr>
                <td><p style="text-align: center;font-size: 10px;margin-bottom: 0px;">This is a system generated invoice and does not required any signature.</p><p style="font-size: 10px;margin-bottom: 0px">For more inquiries, please call our 24x7x365 dedicated Customer Services Department UAN: {{$get_domain_data->bm_helpline_number}} or email us at <a href="mailto:{{$get_domain_data->bm_invoice_email}}"></a>{{$get_domain_data->bm_invoice_email}}</p></td>
                <td style="text-align: right; width: 50px">
                    <img src="images/frame.png" alt="" style="width: 50px">
                </td>
            </tr>
        </tbody>
    </table>
    <p style="text-align: center; font-size: 10px;margin-bottom: 0px;color:#0d4dab">Copyright 2023 {{strtoupper(Auth::user()->resellerid)}} All Rights Reserved.</p>
</div>