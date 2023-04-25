<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Currency;
use App\Models\PaymentTerm;
use App\Models\QuotationDetail;
use App\Models\QuotationNote;
use App\Models\Customer;
use App\Models\PersonInvoice;
use App\Models\Dealer;
use App\Models\Authorizer;
use App\Models\Advance;
use App\Models\BankInfo;
use App\Models\BankInfoDetail;
use Carbon\Carbon;
use DB;
use PDF;
use Laravel\Sanctum\PersonalAccessToken; 
use Laravel\Sanctum\Sanctum;

class InvoiceController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:invoice-index|invoice-create|invoice-edit|invoice-delete', ['only' => ['index']]);
        $this->middleware('permission:invoice-show', ['only' => ['show']]);
        $this->middleware('permission:invoice-create', ['only' => ['create','store']]);
        $this->middleware('permission:invoice-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:invoice-delete', ['only' => ['destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data               = Invoice::searchDataPaginate($request);
        $total              = $data->total();
        $limit              = pagination();
        $inv_codes          = Invoice::invoiceNoDropDown();
        $company_names      = Customer::companyDropDown();
        $customer_names     = Customer::customerDropDown();
        $search             = $request;
        return response()->json([
            'status'            => true,
            'total'             => $total,
            'limit'             => $limit,
            'invoices'          => $data,
            'inv_codes'         => $inv_codes,
            'company_names'     => $company_names,
            'customer_names'    => $customer_names,
            'search'            => $request,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers      = Customer::where('action', true)->get(); 
        $personInvoices = PersonInvoice::get();
        $currency       = Currency::all();
        $quotations     = Quotation::where('SubmitStatus', true)->get();
        $payments       = paymentsForApi();
        return response()->json([
            'status'            => true,
            'customers'         => $customers,
            'person_invoices'   => $personInvoices,
            'currency'          => $currency,
            'quotations'        => $quotations,
            'payments'          => $payments
        ], 200);
    }

    public function getQuoData($id){
        $quotation = Quotation::find($id);
        $currency = Currency::all();
        return response()->json([
            'status'   => true,
            'quotation'=> $quotation,
            'currency' => $quotation->Currency_type,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $quo_Id = $request->Quotation_Id;

        if($request->hasFile('file')){
            $request->validate([
                'file' => 'required|mimes:png,jpg,jpeg,pdf|max:10240'
            ]);
            $fileNameWithExtension = $request->file->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
            $fileExtension = $request->file->getClientOriginalExtension();

            $datetime = strtotime(date('Y-m-d H:i:s'));

            $fileNameToStore = $fileName.$datetime.$fileExtension;
            $request->file->move(public_path('attachments/officeManagement/'), $fileNameToStore);
            $storedFileName= 'attachments/officeManagement/'.$fileNameToStore;
        }else{
            $storedFileName = null;
        }

        if($input['customer_id']  != ''){
            $input['Attn'] = Customer::find($input['customer_id'])['name'];
        }

        $input['Invoice_No'] = 'IV-'.strtotime($input['Date'].' '.date('H:i:s'));
        $input['Date'] =date('Y-m-d',strtotime(str_replace('/', '-', $input['Date'])));
        
        $input['form31_files'] = $storedFileName;
        $input['submit_status'] = false;

        // dd($input);
       $this->validate($request, [
            // 'name' => 'required',
            // 'company' => 'required',
            // 'Attn' => 'required',
            // 'Company_name' => 'required',
            // 'Contact_phone' => 'required',
            // 'Sub' => 'required',
            // 'Serial_No' => 'required',
            // 'Date' => 'required',
        ]);
        $invoice = Invoice::create($input);

        if ($quo_Id != "") {
            DB::table('quotation_details')->where('Quotation_Id',$quo_Id)->update(['Invoice_Id' => $invoice->id]);
        }
        return response()->json([
            'status'            => true,
            'invoice'         => $invoice,
        ], 200);  
    }

    public function detail($id,$type=null){
        $invoice    = Invoice::find($id);
        $currency   = Currency::where('id',$invoice->Currency_type)->first();
        $invDetails = QuotationDetail::where('Invoice_Id',$id)->get();
        $invNotes       = QuotationNote::where('InvoiceId', $id)->where('Note','!=',"")->get();
        $authorizers    = Authorizer::get();
        $bankInfos = BankInfo::get();

        $bankInfoDetails = [];
        if($invoice->submit_status == 1 && $invoice->bank_info != ''){
            $banks = explode(',', $invoice->bank_info);
            foreach($banks as $bank){
                $banInfo = BankInfo::find($bank);
                $bInfo['name'] = $banInfo->name;
                $bInfo['details'] = BankInfoDetail::where('bank_info_id', $bank)->get();
                array_push($bankInfoDetails, $bInfo);
            }
        }

        // data for other payment
        if(is_numeric($type)){
            $advance_data = Advance::where('Invoice_Id', $invoice->id)->where('nth_time', $type)->first();
        }else{
            $advance_data = null;
        }
        $advance_last = Advance::where('Invoice_Id', $invoice->id)->orderBy('id', 'desc')->first();
        $advances = Advance::where('Invoice_Id', $invoice->id)->orderBy('id', 'asc')->get();

        // selected bank info
        $selected_bank_info = explode(',', $invoice->bank_info);

        return response()->json([
            'status'            => true,
            'invoice'           => $invoice,
            'currency'          => $currency,
            'invDetails'        => $invDetails,
            'invNotes'          => $invNotes,
            'bankInfos'         => $bankInfos,
            'selected_bank_info'=> $selected_bank_info,
            'authorizers'       => $authorizers,
            'bankInfoDetails'   => $bankInfoDetails,
            'advance_last'      => $advance_last,
            'advances'          => $advances,
            'advance_data'      => $advance_data,
            'type'              => $type,
        ], 200);   
    }

    public function detail_create($id){
        $invoice = Invoice::find($id);
        $dealers = Dealer::where('action',true)->get(); 
        return response()->json([
            'status'       => true,
            'invoice'      => $invoice,
            'dealers'      => $dealers,
        ], 200);   
    }
    public function detail_store(Request $request, $id){
        $inv = Invoice::findOrFail($id);

        $this->validate($request, [
        ]);

        if($request->tax == "1"){
            $tax_amount = 5;
        }else{
            $tax_amount = 0;
        }

        $input = QuotationDetail::create([
            'Quotation_Id'  => $inv->Quotation_Id,
            'Invoice_Id'    => $inv->id,
            'Description'   => $request->Description,
	        'Unit_Price'    => $request->Unit_Price,
	        'Qty'           => $request->Qty,
	        'percent'       => $request->percent,
	        'dealer_id'     => $request->dealer_id,
	        'form31_no'     => $request->form31_no,
            'invoice_no'    => $request->invoice_no,
	        'tax'           => $request->tax,
	        'tax_amount'    => $tax_amount,
        ]);
        return response()->json([
            'status'         => true,
            'detail'         => $input,
        ], 200);  
    }
    public function detail_edit($id){
        $invDetail      = QuotationDetail::find($id);
        $dealers        = Dealer::where('action',true)->get();
        
        $selectedDealer = null;
        if($invDetail->dealer_id != ''){
            $selectedDealer = Dealer::find($invDetail->dealer_id);
        }
        return response()->json([
            'status'            => true,
            'invDetail'         => $invDetail,
            'dealers'           => $dealers,
            'selectedDealer'    => $selectedDealer,
        ], 200); 
    }
    public function detail_update(Request $request, $id){
        if($request->tax == "1"){
            $tax_amount = 5;
        }else{
            $tax_amount = 0;
        }
        $detail = QuotationDetail::find($id);
        $detail->update([
            'Description'   => $request->Description,
	        'Unit_Price'    => $request->Unit_Price,
	        'Qty'           => $request->Qty,
	        'percent'       => $request->percent,
	        'dealer_id'     => $request->dealer_id,
	        'form31_no'     => $request->form31_no,
            'invoice_no'    => $request->invoice_no,
	        'tax'           => $request->tax,
	        'tax_amount'    => $tax_amount,
        ]);
        return response()->json([
            'status'    => true,
            'invDetail' => $detail,
        ], 200); 
    }
    public function detail_delete($id){
        $detail = QuotationDetail::find($id);
        $detail->Invoice_Id = null;
        $detail->save();
        return response()->json([
            'status'    => true,
        ], 200);
    }

    public function tax_check(Request $request, $id){
        $tax = $request->tax;
        $total = $request->total;

        $inv = Invoice::find($id);
        $inv->tax_id = $tax;
        $inv->save();

        $tax_amount     = ($inv->tax_id * $total)/100;
        $grand_total    = $total + $tax_amount;
        return response()->json([
            'status'    => true,
            'tax_amount' => number_format($tax_amount,2),
            'grand_total' => number_format($grand_total,2),
        ]);
    }

    public function bank_check(Request $request, $id){
        $bankId = $request->bankId;
        $check = $request->check;

        $bankInfo = [];

        $inv = Invoice::find($id);
        if($inv->bank_info != ''){
            $bankInfo = explode(',', $inv->bank_info);
        }
        if($check == '1'){ // add 
            if (array_search($bankId, $bankInfo) === false) {
                array_push($bankInfo, $bankId);
            }
        }else if($check == '0'){ // remove
            if (($key = array_search($bankId, $bankInfo)) !== false) {
                unset($bankInfo[$key]);
            }
        }

        $inv->bank_info = implode(',', $bankInfo);
        $inv->save();
        return response()->json([
            'status'    => true,
            'invoice'  => $inv,
        ]);
    }

    public function discount(Request $request, $id){
            $inv = Invoice::find($id);
            $inv->update([
                'Discount' => $request->discount,
            ]);
            return response()->json([
            'status'    => true,
            'invoice'  => $inv,
        ]);
    }

    public function sign_store(Request $request, $id){
        $inv = Invoice::find($id);
        $authorizer = Authorizer::where('file_name', $request->authorizer)->first();
        $inv->update([
            'sign_name' =>  $authorizer->authorized_name,
            'file_name' =>  $authorizer->file_name,
        ]);
        return response()->json([
            'status'    => true,
            'invoice'  => $inv,
        ]);
    }

    public function confirm(Request $request, $id){
        $invoice = Invoice::find($id);
        // dd($invoice);
        $invoice->update([
            'submit_status' => 1,
        ]);
        return response()->json([
        'status'    => true,
        'invoice'  => $invoice,
        ]);
    }
    
    public function note_store(Request $request, $id){
        $invId = $request->invId;
        $input = QuotationNote::create([
            'InvoiceId'     => $invId,
            'Note'          => $request->Note,
        ]);
        return response()->json([
            'status'    => true,
            'note'      => $input
        ], 200);
    }
    public function note_edit($id){
        $note = QuotationNote::findorfail($id);
        return response()->json([
            'status'    => true,
            'note'      => $note,
        ], 200);
    }
    public function note_update(Request $request,$id){
        $invId = $request->invId;
        $note = QuotationNote::find($id);
        $input = $note->update([
            'InvoiceId'     => $invId,
            'Note'          => $request->Note,
        ]);
        return response()->json([
            'status'    => true,
            'note'      => $note,
        ], 200);
    }

    public function note_delete(Request $request,$id){
        $invId = $request->invId;
        $note = QuotationNote::find($id);
        $note->destroy($id);
        return response()->json([
            'status'    => true,
            'note'      => $note,
        ], 200);
    }
    public function quo_attn_on_change(Request $request){
            $quoId = $request->quoId;
            if($quoId != ''){
                $quotation = Quotation::findOrFail($quoId);
            }
            $currency = Currency::all();
            return response()->json([
                'status'    => true,
                'currency'  => $currency,
                'quotation' => $quotation
            ], 200);
    }

    public function get_invoice(Request $request, $id){
        $type = $request->type;
        // first invoice  = 1; 50/50, 60/40, 80/20
        // second invoice = 2; 50/50, 60/40, 80/20
        // cash/credit = 3
        // other => 4

        // used for other payment
        $amount     = $request->amount; // total amount
		$other_amt  = $request->other_amt; // apply amount
        if($other_amt == ''){
            $other_amt = 0;
        }

        $invoice = Invoice::find($id);
        $details = QuotationDetail::where('Invoice_Id', $id)->get();

        $advances = Advance::where('Invoice_Id', $id)->get();

        $total = 0;
        foreach ($details as $value) {
			$total += percent_price($value->Unit_Price, $value->percent) * $value->Qty;
		}

        $taxAmount = ($invoice->tax_id * ($total - $invoice->Discount))/100;
        $grandTotal = $total - $invoice->Discount + $taxAmount;
        $grandTotalWithoutTax = $total - $invoice->Discount;

        // $tax_amount = ($total - $invoice->Discount) * ($invoice->tax_id/100);

        if ($type == 1){ // first payment 50/50 60/40 80/20
			if ($invoice->Advance == 1) { // 50/50
				$multiply = 50/100;
			} elseif ($invoice->Advance == 2) { // 60/40
				$multiply = 60/100;
			} else { // 80/20
				$multiply = 80/100; 
			}
			$invoice->update([
				'FirstInvoice'          => true,
				'finv_date'             => date("Y-m-d"),
				'First_payment_amount'  => round($grandTotalWithoutTax * $multiply, 2)
            ]);
		} elseif ($type == 2) { // second payment
			$invoice->update([
				'SecondInvoice'         => true,
				'sinv_date'             => date("Y-m-d"),
				'Second_payment_amount' => round($grandTotal - $invoice->First_payment_amount, 2),
			]);
		} elseif ($type == 3) {
            $invoice->update([
				'FirstInvoice'  => true, 
                'SecondInvoice' => true,
                'finv_date'     => date("Y-m-d"),
                'sinv_date'     => date("Y-m-d"),
			]);
		} elseif ($type == 4) {
            $invoice->update([
				'FirstInvoice'  => true, 
                'SecondInvoice' => true
			]);

			$last_row = Advance::where('Invoice_Id', $invoice->id)->orderBy('id', 'desc')->first();

            $remain_amt = isset($last_row) ? $last_row->Balance : $amount;
			$balance    = $remain_amt - $other_amt;

			$last_row_count = isset($last_row) ? $last_row->nth_time : 0;
			$last_row_count = $last_row_count + 1;
            
            $advan = Advance::create([
				'Invoice_Id'    => $invoice->id,
				'Advance_value' => $other_amt,
				'Balance'       => $balance,
				'Date'          => date('Y-m-d h:i:s'),
				'nth_time'      => $last_row_count
			]);
		}
        return response()->json([
            'status'    => true,
            'invoice' => $invoice,
            'quo_detail' => $details,
        ], 200);
    }


    public function test(){
        $array1 = ['accounts.dhpi', 'dd2a.ese', 'gma.ese', 'ad1f.dptsc', 'dd2.eco.depp', 'gma.mesc', 'ad2f.dptsc', 'dd2f.dptsc', 'gmdistribution.yesc', 'ad3f.dptsc', 'dd2f.ese', 'gmd.mesc', 'ad.admin', 'dd2.finance', 'gmf.ese', 'adeayy.ese', 'dd2.hyre.depp', 'gmfinance.yesc', 'adebgoe.ese', 'dd2.ir.info', 'gmf.mesc', 'adebgow.ese', 'dd2mp.ese', 'gmmaterial.yesc', 'adechn.ese', 'dd2.pdp.depp', 'gmmp.ese', 'adekcn.ese', 'dd2.policy', 'gmplanning.yesc', 'adekya.ese', 'dd2ptp.dptsc', 'gmp.mesc', 'adekyn.ese', 'dd2.statistics', 'gmpm.mesc', 'ademgy.ese', 'dd2.thermal.depp', 'hlawga.epge', 'ademon.ese', 'dd3f.dptsc', 'ictoffice.yesc', 'adenpt.ese', 'dd3f.ese', 'implementation.nep', 'aderke.ese', 'dd3.finance', 'information.nep', 'adesgg.ese', 'dd3.pdp.depp', 'ir.info', 'adeshne.ese', 'dd.admin', 'khabaung.epge', 'adeshnn.ese', 'ddg1.depp', 'khuchaung.epge', 'adeshns.ese', 'ddg2.depp', 'kinda.epge', 'adetni.ese', 'ddg.dhpi', 'kyaingtaung.epge', 'adf.dptsc', 'ddg.dptsc', 'kyaukphyu.epge', 'adimp.dptsc', 'dd.gen.depp', 'kyeeonkyeewa.epge', 'adlptp.dptsc', 'ddgoffice.dhpi', 'kyunchaung.epge', 'admin', 'ddgps.dptsc', 'libadmin.dptsc', 'admin2.eform', 'dd.hm.depp', 'maamrpr.mesc', 'admin.dhpi', 'dd.hr.depp', 'maamtz.mesc', 'admin.emeter', 'ddir1con1.dhpi', 'macatz.mesc', 'admin.epge', 'ddir1con2.dhpi', 'macmtz.mesc', 'adminoffice.epge', 'ddir1design.dhpi', 'makpd.mesc', 'adminoffice.yesc', 'ddir1maintenance.dhpi', 'maks.mesc', 'admpsd1.dptsc', 'ddir1mech.dhpi', 'mamdy.mesc', 'adsptp.dptsc', 'ddir1meinstall.dhpi', 'mamgk.mesc', 'adssact.dptsc', 'ddir1pw.dhpi', 'mamg.mesc', 'adssahl.dptsc', 'ddir2con1.dhpi', 'mamham.mesc', 'adssank.dptsc', 'ddir2con2.dhpi', 'mamh.mesc', 'adssann.dptsc', 'ddir2design.dhpi', 'mamtl.mesc', 'adssapl.dptsc', 'ddir2maintenance.dhpi', 'mamt.mesc', 'adssatk.dptsc', 'ddir2mech.dhpi', 'mann.epge', 'adssbdk.dptsc', 'ddir2meinstall.dhpi', 'mantg.mesc', 'adssbln.dptsc', 'ddir2pw.dhpi', 'manu.mesc', 'adssbmw.dptsc', 'ddiracc.dhpi', 'manz.mesc', 'adssbyn.dptsc', 'ddiradmin.dhpi', 'mapb.mesc', 'adsschk.dptsc', 'ddircon3.dhpi', 'mapgtg.mesc', 'adsschu.dptsc', 'ddircon4.dhpi', 'mapol.mesc', 'adssedg.dptsc', 'ddircon5.dhpi', 'maptg.mesc', 'adssggw.dptsc', 'ddirgeol.dhpi', 'masg.mesc', 'adsshlg.dptsc', 'ddirict1.dptsc', 'masgu.mesc', 'adsshty.dptsc', 'ddirict2.dptsc', 'matbk.mesc', 'adssing.dptsc', 'ddirinvest.dhpi', 'matdu.mesc', 'adssklw.dptsc', 'ddirldc.dptsc', 'materialoffice.yesc', 'adsskly.dptsc', 'ddirl.dptsc', 'matt.mesc', 'adsskmn.dptsc', 'ddirmb.dptsc', 'matz.mesc', 'adsskng.dptsc', 'ddirncc.dptsc', 'mawd.mesc', 'adsskph.dptsc', 'ddirpln1.dptsc', 'maymt.mesc', 'adsskpu.dptsc', 'ddirpln2.dptsc', 'md.epge', 'adsskyl.dptsc', 'ddirpln3.dptsc', 'md.ese', 'adsslkw.dptsc', 'ddirpro.dhpi', 'mdoffice.ese', 'adsslph.dptsc', 'ddirpzl.dhpi', 'minister', 'adssmgn.dptsc', 'ddirss.dptsc', 'minister.office', 'adssmgs.dptsc', 'ddirtl1.dptsc', 'mksdis.mesc', 'adssmgw.dptsc', 'ddirtl2.dptsc', 'mmdydis.mesc', 'adssmhl.dptsc', 'deayy.ese', 'mmgdis.mesc', 'adssmiz2.dptsc', 'debgoe.ese', 'mmtldis.mesc', 'adssmlm.dptsc', 'debgow.ese', 'mnudis.mesc', 'adssmsn.dptsc', 'dechn.ese', 'monechaung.epge', 'adssmtg.dptsc', 'dekcn.ese', 'mpoldis.mesc', 'adssmwd.dptsc', 'dekya.ese', 'mtadmin.dptsc', 'adssmyp.dptsc', 'dekyn.ese', 'myanaung.epge', 'adssnbg.dptsc', 'demgy.ese', 'myittha.epge', 'adssnbr.dptsc', 'demon.ese', 'mymtdis.mesc', 'adssnpd.dptsc', 'denorthern.yesc', 'myogyi.epge', 'adssnpt2.dptsc', 'deputychairman.mesc', 'nancho.epge', 'adssnpt.dptsc', 'derke.ese', 'no-reply', 'adssnsm.dptsc', 'desgg.ese', 'noreply', 'adssoht.dptsc', 'deshane.ese', 'northoffice.yesc', 'adssosp.dptsc', 'deshann.ese', 'office.admin.depp', 'adsspmn.dptsc', 'deshans.ese', 'office.eco.depp', 'adsspnk.dptsc', 'design.dhpi', 'office.hyre.depp', 'adssptn.dptsc', 'desouthern.yesc', 'office.pdp.depp', 'adssshb.dptsc', 'detni.ese', 'office.policy', 'adssshm.dptsc', 'dewestern.yesc', 'office.thermal.depp', 'adsssit.dptsc', 'dg.depp', 'offict1.dptsc', 'adssssy.dptsc', 'dg.dhpi', 'offict2.dptsc', 'adsssty.dptsc', 'dg.dptsc', 'offldc1.dptsc', 'adsstak.dptsc', 'dgmadmin.yesc', 'offl.dptsc', 'adsstdg.dptsc', 'dgma.mesc', 'offmb.dptsc', 'adsstgd.dptsc', 'dgmd.mesc', 'offncc1.dptsc', 'adsstgi.dptsc', 'dgmf.mesc', 'offpln.dptsc', 'adsstgo.dptsc', 'dgmp1.mesc', 'offss.dptsc', 'adsstly.dptsc', 'dgmp2.mesc', 'offtl1.dptsc', 'adsstny.dptsc', 'dgmpm.mesc', 'offtl2.dptsc', 'adsstpu.dptsc', 'dgoff.dptsc', 'paunglaung.epge', 'adsstpw.dptsc', 'dgoffice.dhpi', 'pdirs.dptsc', 'adssttk.dptsc', 'dicbgo.ese', 'pdn.dptsc', 'adsstyg.dptsc', 'dicblk.ese', 'permanentsecretary', 'adsstzi.dptsc', 'dicbmw.ese', 'phyuchaung.epge', 'adsswmw.dptsc', 'dicdkn.ese', 'planning.nep', 'adsswty.dptsc', 'dicdwi.ese', 'planningoffice.yesc', 'adssymp.dptsc', 'dicggw.ese', 'planningstatistics.office', 'adssynp.dptsc', 'dichkr.ese', 'pm1n.dptsc', 'adssyps.dptsc', 'dichpn.ese', 'pm2n.dptsc', 'aeegov.ese', 'dichtd.ese', 'pm3n.dptsc', 'agmaksdis.mesc', 'dickky.ese', 'pmcivils.dptsc', 'agmamdydis.mesc', 'dicklg.ese', 'pmcj1s.dptsc', 'agma.mesc', 'dickly.ese', 'pmcj2s.dptsc', 'agmamgdis.mesc', 'dickme.ese', 'pmcj3s.dptsc', 'agmamtldis.mesc', 'dickpu.ese', 'pmcn.dptsc', 'agmanudis.mesc', 'dickta.ese', 'procurement.dhpi', 'agmapoldis.mesc', 'dickte.ese', 'procurement.epge', 'agmaymtdis.mesc', 'dicktg.ese', 'procurement.nep', 'agmd1.mesc', 'dicktn.ese', 'procurementoffice.epge', 'agmd2.mesc', 'diclke.ese', 'projectmanager.nep', 'agmf.mesc', 'diclkg.ese', 'ps', 'agmp1.mesc', 'diclkw.ese', 'ps.office', 'agmp2.mesc', 'dicllm.ese', 'pw.dhpi', 'agmpm.mesc', 'diclpt.ese', 'renewabeenergy.epge', 'ahlone.epge', 'diclso.ese', 'renewabeenergyoffice.epge', 'amamrpr.mesc', 'dicmbu.ese', 'sa1admin.dptsc', 'amamtz.mesc', 'dicmdw.ese', 'sa3admin.dptsc', 'ambg.mesc', 'dicmgy.ese', 'sa5admin.dptsc', 'amcatz.mesc', 'dicmkn.ese', 'sa6admin.dptsc', 'amcmtz.mesc', 'dicmku.ese', 'se1es.ese', 'amkm.mesc', 'dicmle.ese', 'se1.tpd.epge', 'amkpd.mesc', 'dicmlm.ese', 'se2es.ese', 'amks.mesc', 'dicmma.ese', 'se2.tpd.epge', 'ammdy.mesc', 'dicmnn.ese', 'sedawgyi.epge', 'ammgk.mesc', 'dicmse.ese', 'sen.ese', 'ammg.mesc', 'dicmst.ese', 'sepl.ese', 'ammham.mesc', 'dicmtt.ese', 'ses.ese', 'ammh.mesc', 'dicmub.ese', 'sespec.ese', 'ammtl.mesc', 'dicmya.ese', 'shiftldc1.dptsc', 'ammt.mesc', 'dicmyk.ese', 'shiftncc.dptsc', 'amntg.mesc', 'dicota.ese', 'shwekyin.epge', 'amnty.mesc', 'dicpan.ese', 'shwetaung.epge', 'amnu.mesc', 'dicpku.ese', 'southoffice.yesc', 'amnz.mesc', 'dicpln.ese', 'teahl.yesc', 'ampb.mesc', 'dicppn.ese', 'teap.yesc', 'ampgtg.mesc', 'dicptn.ese', 'teay.yesc', 'ampl.mesc', 'dicpto.ese', 'tebhn.yesc', 'ampol.mesc', 'dicpyi.ese', 'tebtg.yesc', 'amptg.mesc', 'dicsgg.ese', 'technician.dhpi', 'amsg.mesc', 'dicspo.ese', 'tedg.yesc', 'amsgu.mesc', 'dicsty.ese', 'tedl.yesc', 'amtbk.mesc', 'dictde.ese', 'tedpm.yesc', 'amtdu.mesc', 'dictgi.ese', 'tedp.yesc', 'amtg.mesc', 'dictgo.ese', 'teedg.yesc', 'amtt.mesc', 'dicttn.ese', 'tehk.yesc', 'amtz.mesc', 'dictyt.ese', 'tehla.yesc', 'amwd.mesc', 'dictywt.ese', 'tehlg.yesc', 'amya.mesc', 'dicymp.ese', 'tehtp.yesc', 'amymt.mesc', 'dir1ptp.dptsc', 'tehty.yesc', 'as.admin', 'dir2ptp.dptsc', 'teid.yesc', 'as.finance', 'diracc.dhpi', 'teis.yesc', 'as.ir.info', 'dir.admin.depp', 'tekck.yesc', 'as.policy', 'diradmin.dhpi', 'tekh.yesc', 'as.statistics', 'diradmin.dptsc', 'tekmd.yesc', 'asstdirannl.dptsc', 'dira.ese', 'tekm.yesc', 'asstdirchkl.dptsc', 'dircon1.dhpi', 'tekmy.yesc', 'asstdirggwl.dptsc', 'dircon2.dhpi', 'tektd.yesc', 'asstdirkmnl.dptsc', 'dircon3.dhpi', 'tekt.yesc', 'asstdirkptl.dptsc', 'dircon4.dhpi', 'teky.yesc', 'asstdirlkwl.dptsc', 'dircon5.dhpi', 'telkk.yesc', 'asstdirmsml.dptsc', 'dirdesign.dhpi', 'telmt.yesc', 'asstdirnsml.dptsc', 'dir.eco.depp', 'telth.yesc', 'asstdirowtl.dptsc', 'dirf.dptsc', 'temb.yesc', 'asstdirpyil.dptsc', 'dirf.ese', 'temgd.yesc', 'asstdirtgol.dptsc', 'dirgeol.dhpi', 'temtk.yesc', 'asstdirthtl.dptsc', 'dir.hyre.depp', 'temtn.yesc', 'asstdirtzil.dptsc', 'dirict.dptsc', 'temyg.yesc', 'asstdirwmwl.dptsc', 'dirinvest.dhpi', 'tendg.yesc', 'asstdirykil.dptsc', 'dirldcygn.dptsc', 'tenok.yesc', 'beluchaung-1.epge', 'dirl.dptsc', 'teok.yesc', 'beluchaung-2.epge', 'dirmaintenance.dhpi', 'tepbt.yesc', 'bod1.mesc', 'dirmech.dhpi', 'tepdg.yesc', 'bod-1office.yesc', 'dirmeinstall.dhpi', 'tepg.yesc', 'bod1.yesc', 'dirmp.dptsc', 'tepzd.yesc', 'bod-2office.yesc', 'dirmp.ese', 'tescg.yesc', 'bod2.yesc', 'dirnccnpt.dptsc', 'tesdg.yesc', 'bod-3office.yesc', 'dir.pdp.depp', 'teskk.yesc', 'bod3.yesc', 'dirpln.dptsc', 'tesk.yesc', 'bod-4office.yesc', 'dirpro.dhpi', 'teslp.yesc', 'bod4.yesc', 'dirpw.dhpi', 'tesok.yesc', 'bod-5office.yesc', 'dirpzl.dhpi', 'tespk.yesc', 'bod5.yesc', 'dirss.dptsc', 'tespt.yesc', 'bodoffice.mesc', 'dir.thermal.depp', 'test', 'bodoffice.yesc', 'distributionoffice.yesc', 'tetgk.yesc', 'callcenter.yesc', 'dmeastern.yesc', 'tetg.yesc', 'cedist.ese', 'dmnorthern.yesc', 'tetkk.yesc', 'cees.ese', 'dmsouthern.yesc', 'tetk.yesc', 'cenpt.ese', 'dmwestern.yesc', 'tetl.yesc', 'ceo.mesc', 'dycees.ese', 'tetmw.yesc', 'ceooffice.mesc', 'dycen.ese', 'tettm.yesc', 'ceooffice.yesc', 'dycepl.ese', 'tett.yesc', 'ceooyesc', 'dyces.ese', 'teykn.yesc', 'ceo.yesc', 'dycespec.ese', 'thaketa.epge', 'chairman.mesc', 'dymd.epge', 'thaphanseik.epge', 'chairmanoffice.yesc', 'dym.electricity', 'thaton.epge', 'chairman.yesc', 'dym.electricity.office', 'thermal.epge', 'cliadmin.dptsc', 'dyminister', 'thermaloffice.epge', 'comadmin.dptsc', 'dyminister.office', 'thilawa.epge', 'consultant.wg.dptsc', 'dyps.electric', 'tigyit.epge', 'dd1admin.dptsc', 'dyps.electric.office', 'unionminister', 'dd1a.ese', 'eastoffice.yesc', 'upperkyaingtaung.epge', 'dd1.eco.depp', 'eeegov.ese', 'upperpaunglaung.epge', 'dd1f.dptsc', 'ee.tpd.epge', 'vcoffice.yesc', 'dd1f.ese', 'egovoffice.yesc', 'vicechairman.yesc', 'dd1.finance', 'egovsection', 'westoffice.yesc', 'dd1.hyre.depp', 'egovsection1', 'yazagyo.epge', 'dd1.ir.info', 'egovsection2', 'yenwe.epge', 'dd1mp.ese', 'egov.yesc', 'yeywa.epge', 'dd1.pdp.depp', 'electricpower.dyminister', 'ywama.epge', 'dd1.policy', 'finance.epge', 'zaungtu.epge', 'dd1ptp.dptsc', 'financeoffice.epge', 'zawgyi-1.epge', 'dd1.statistics', 'financeoffice.yesc', 'zawgyi-2.epge', 'dd1.thermal.depp', 'finpsd1.dptsc', 'dd2admin.dptsc', 'gmadmin.yesc'];

        $array2 = ['unionminister', 'minister', 'minister.office', 'dyminister', 'dyminister.office', 'electricpower.dyminister', 'dym.electricity', 'dym.electricity.office', 'permanentsecretary', 'ps', 'ps.office', 'dyps.electric', 'dyps.electric.office', 'as.admin', 'dd.admin', 'ad.admin', 'as.policy', 'dd1.policy', 'dd2.policy', 'office.policy', 'as.statistics', 'dd1.statistics', 'dd2.statistics', 'planningstatistics.office', 'as.finance', 'dd1.finance', 'dd2.finance', 'dd3.finance', 'as.ir.info', 'dd1.ir.info', 'dd2.ir.info', 'ir.info', 'admin', 'admin.emeter', 'admin2.eform', 'egovsection', 'egovsection1', 'egovsection2', 'no-reply', 'noreply', 'test', 'dg.depp', 'ddg1.depp', 'ddg2.depp', 'dir.admin.depp', 'dir.eco.depp', 'dir.pdp.depp', 'dir.thermal.depp', 'dir.hyre.depp', 'dd.gen.depp', 'dd.hm.depp', 'dd.hr.depp', 'dd1.eco.depp', 'dd1.hyre.depp', 'dd1.pdp.depp', 'dd1.thermal.depp', 'dd2.eco.depp', 'dd2.hyre.depp', 'dd2.pdp.depp', 'dd2.thermal.depp', 'dd3.pdp.depp', 'office.admin.depp', 'office.eco.depp', 'office.pdp.depp', 'office.thermal.depp', 'office.hyre.depp', 'dg.dptsc', 'dgoff.dptsc', 'ddg.dptsc', 'ddgps.dptsc', 'dirss.dptsc', 'dirl.dptsc', 'dirpln.dptsc', 'dirict.dptsc', 'dirnccnpt.dptsc', 'dirldcygn.dptsc', 'dir1ptp.dptsc', 'dir2ptp.dptsc', 'diradmin.dptsc', 'dirf.dptsc', 'dirmp.dptsc', 'dd1f.dptsc', 'dd2f.dptsc', 'dd3f.dptsc', 'dd1ptp.dptsc', 'dd2ptp.dptsc', 'dd2admin.dptsc', 'ddirict1.dptsc', 'ddirict2.dptsc', 'ddirl.dptsc', 'ddirldc.dptsc', 'ddirmb.dptsc', 'ddirncc.dptsc', 'ddirpln1.dptsc', 'ddirpln2.dptsc', 'ddirpln3.dptsc', 'ddirss.dptsc', 'ddirtl1.dptsc', 'ddirtl2.dptsc', 'offict1.dptsc', 'offict2.dptsc', 'offl.dptsc', 'offldc1.dptsc', 'offmb.dptsc', 'offncc1.dptsc', 'offpln.dptsc', 'offss.dptsc', 'offtl1.dptsc', 'offtl2.dptsc', 'shiftldc1.dptsc', 'shiftncc.dptsc', 'ad1f.dptsc', 'ad2f.dptsc', 'ad3f.dptsc', 'adf.dptsc', 'adimp.dptsc', 'adlptp.dptsc', 'admpsd1.dptsc', 'adsptp.dptsc', 'adssact.dptsc', 'adssahl.dptsc', 'adssank.dptsc', 'adssann.dptsc', 'adssapl.dptsc', 'adssatk.dptsc', 'adssbdk.dptsc', 'adssbln.dptsc', 'adssbmw.dptsc', 'adssbyn.dptsc', 'adsschk.dptsc', 'adsschu.dptsc', 'adssedg.dptsc', 'adssggw.dptsc', 'adsshlg.dptsc', 'adsshty.dptsc', 'adssing.dptsc', 'adssklw.dptsc', 'adsskly.dptsc', 'adsskmn.dptsc', 'adsskng.dptsc', 'adsskph.dptsc', 'adsskpu.dptsc', 'adsskyl.dptsc', 'adsslkw.dptsc', 'adsslph.dptsc', 'adssmgn.dptsc', 'adssmgs.dptsc', 'adssmgw.dptsc', 'adssmhl.dptsc', 'adssmiz2.dptsc', 'adssmlm.dptsc', 'adssmsn.dptsc', 'adssmtg.dptsc', 'adssmwd.dptsc', 'adssmyp.dptsc', 'adssnbg.dptsc', 'adssnbr.dptsc', 'adssnpd.dptsc', 'adssnpt.dptsc', 'adssnpt2.dptsc', 'adssnsm.dptsc', 'adssoht.dptsc', 'adssosp.dptsc', 'adsspmn.dptsc', 'adsspnk.dptsc', 'adssptn.dptsc', 'adssshb.dptsc', 'adssshm.dptsc', 'adsssit.dptsc', 'adssssy.dptsc', 'adsssty.dptsc', 'adsstak.dptsc', 'adsstdg.dptsc', 'adsstgd.dptsc', 'adsstgi.dptsc', 'adsstgo.dptsc', 'adsstly.dptsc', 'adsstny.dptsc', 'adsstpu.dptsc', 'adsstpw.dptsc', 'adssttk.dptsc', 'adsstyg.dptsc', 'adsstzi.dptsc', 'adsswmw.dptsc', 'adsswty.dptsc', 'adssymp.dptsc', 'adssynp.dptsc', 'adssyps.dptsc', 'asstdirannl.dptsc', 'asstdirchkl.dptsc', 'asstdirggwl.dptsc', 'asstdirkmnl.dptsc', 'asstdirkptl.dptsc', 'asstdirlkwl.dptsc', 'asstdirmsml.dptsc', 'asstdirnsml.dptsc', 'asstdirowtl.dptsc', 'asstdirpyil.dptsc', 'asstdirtgol.dptsc', 'asstdirthtl.dptsc', 'asstdirtzil.dptsc', 'asstdirwmwl.dptsc', 'asstdirykil.dptsc', 'cliadmin.dptsc', 'comadmin.dptsc', 'consultant.wg.dptsc', 'dd1admin.dptsc', 'finpsd1.dptsc', 'libadmin.dptsc', 'mtadmin.dptsc', 'pdirs.dptsc', 'pdn.dptsc', 'pm1n.dptsc', 'pm2n.dptsc', 'pm3n.dptsc', 'pmcivils.dptsc', 'pmcj1s.dptsc', 'pmcj2s.dptsc', 'pmcj3s.dptsc', 'pmcn.dptsc', 'sa1admin.dptsc', 'sa3admin.dptsc', 'sa5admin.dptsc', 'sa6admin.dptsc', 'dg.dhpi', 'dgoffice.dhpi', 'ddg.dhpi', 'ddgoffice.dhpi', 'technician.dhpi', 'diradmin.dhpi', 'diracc.dhpi', 'dircon1.dhpi', 'dircon2.dhpi', 'dircon3.dhpi', 'dircon4.dhpi', 'dircon5.dhpi', 'dirdesign.dhpi', 'dirgeol.dhpi', 'dirinvest.dhpi', 'dirmaintenance.dhpi', 'dirmech.dhpi', 'dirmeinstall.dhpi', 'dirpro.dhpi', 'dirpw.dhpi', 'dirpzl.dhpi', 'ddir1con1.dhpi', 'ddir1con2.dhpi', 'ddir2con1.dhpi', 'ddir2con2.dhpi', 'ddircon3.dhpi', 'ddircon4.dhpi', 'ddircon5.dhpi', 'ddir1design.dhpi', 'ddir2design.dhpi', 'ddir1maintenance.dhpi', 'ddir2maintenance.dhpi', 'ddir1mech.dhpi', 'ddir2mech.dhpi', 'ddir1meinstall.dhpi', 'ddir2meinstall.dhpi', 'ddir1pw.dhpi', 'ddir2pw.dhpi', 'ddiracc.dhpi', 'ddiradmin.dhpi', 'ddirgeol.dhpi', 'ddirinvest.dhpi', 'ddirpro.dhpi', 'ddirpzl.dhpi', 'admin.dhpi', 'accounts.dhpi', 'design.dhpi', 'procurement.dhpi', 'pw.dhpi', 'md.epge', 'dymd.epge', 'admin.epge', 'adminoffice.epge', 'finance.epge', 'financeoffice.epge', 'procurement.epge', 'procurementoffice.epge', 'renewabeenergy.epge', 'renewabeenergyoffice.epge', 'thermal.epge', 'thermaloffice.epge', 'beluchaung-1.epge', 'beluchaung-2.epge', 'kinda.epge', 'sedawgyi.epge', 'zawgyi-1.epge', 'zawgyi-2.epge', 'zaungtu.epge', 'thaphanseik.epge', 'monechaung.epge', 'paunglaung.epge', 'yenwe.epge', 'khabaung.epge', 'kyaingtaung.epge', 'kyunchaung.epge', 'shwekyin.epge', 'phyuchaung.epge', 'yeywa.epge', 'kyeeonkyeewa.epge', 'nancho.epge', 'upperpaunglaung.epge', 'myogyi.epge', 'myittha.epge', 'yazagyo.epge', 'upperkyaingtaung.epge', 'ywama.epge', 'thaton.epge', 'ahlone.epge', 'hlawga.epge', 'thaketa.epge', 'khuchaung.epge', 'myanaung.epge', 'mann.epge', 'shwetaung.epge', 'tigyit.epge', 'thilawa.epge', 'kyaukphyu.epge', 'ee.tpd.epge', 'se2.tpd.epge', 'se1.tpd.epge', 'md.ese', 'mdoffice.ese', 'gma.ese', 'gmf.ese', 'gmmp.ese', 'cedist.ese', 'cees.ese', 'cenpt.ese', 'dirf.ese', 'dira.ese', 'dirmp.ese', 'dycees.ese', 'dycen.ese', 'dycepl.ese', 'dyces.ese', 'dycespec.ese', 'adenpt.ese', 'adeayy.ese', 'adebgoe.ese', 'adebgow.ese', 'adechn.ese', 'adekcn.ese', 'adekya.ese', 'adekyn.ese', 'ademgy.ese', 'ademon.ese', 'aderke.ese', 'adesgg.ese', 'adeshne.ese', 'adeshnn.ese', 'adeshns.ese', 'adetni.ese', 'aeegov.ese', 'dd1a.ese', 'dd1f.ese', 'dd1mp.ese', 'dd2a.ese', 'dd2f.ese', 'dd2mp.ese', 'dd3f.ese', 'deayy.ese', 'debgoe.ese', 'debgow.ese', 'dechn.ese', 'dekcn.ese', 'dekya.ese', 'dekyn.ese', 'demgy.ese', 'demon.ese', 'derke.ese', 'desgg.ese', 'deshane.ese', 'deshann.ese', 'deshans.ese', 'detni.ese', 'dicbgo.ese', 'dicblk.ese', 'dicbmw.ese', 'dicdkn.ese', 'dicdwi.ese', 'dicggw.ese', 'dichkr.ese', 'dichpn.ese', 'dichtd.ese', 'dickky.ese', 'dicklg.ese', 'dickly.ese', 'dickme.ese', 'dickpu.ese', 'dickta.ese', 'dickte.ese', 'dicktg.ese', 'dicktn.ese', 'diclke.ese', 'diclkg.ese', 'diclkw.ese', 'dicllm.ese', 'diclpt.ese', 'diclso.ese', 'dicmbu.ese', 'dicmdw.ese', 'dicmgy.ese', 'dicmkn.ese', 'dicmku.ese', 'dicmle.ese', 'dicmlm.ese', 'dicmma.ese', 'dicmnn.ese', 'dicmse.ese', 'dicmst.ese', 'dicmtt.ese', 'dicmub.ese', 'dicmya.ese', 'dicmyk.ese', 'dicota.ese', 'dicpan.ese', 'dicpku.ese', 'dicpln.ese', 'dicppn.ese', 'dicptn.ese', 'dicpto.ese', 'dicpyi.ese', 'dicsgg.ese', 'dicspo.ese', 'dicsty.ese', 'dictde.ese', 'dictgi.ese', 'dictgo.ese', 'dicttn.ese', 'dictyt.ese', 'dictywt.ese', 'dicymp.ese', 'eeegov.ese', 'se1es.ese', 'se2es.ese', 'sen.ese', 'sepl.ese', 'ses.ese', 'sespec.ese', 'procurement.nep', 'planning.nep', 'projectmanager.nep', 'implementation.nep', 'information.nep', 'chairman.yesc', 'chairmanoffice.yesc', 'vicechairman.yesc', 'vcoffice.yesc', 'bodoffice.yesc', 'bod1.yesc', 'bod2.yesc', 'bod3.yesc', 'bod4.yesc', 'bod5.yesc', 'bod-1office.yesc', 'bod-2office.yesc', 'bod-3office.yesc', 'bod-4office.yesc', 'bod-5office.yesc', 'ceo.yesc', 'ceooffice.yesc', 'dgmadmin.yesc', 'gmplanning.yesc', 'gmmaterial.yesc', 'gmfinance.yesc', 'gmdistribution.yesc', 'gmadmin.yesc', 'egov.yesc', 'egovoffice.yesc', 'callcenter.yesc', 'adminoffice.yesc', 'ceooyesc', 'dewestern.yesc', 'desouthern.yesc', 'denorthern.yesc', 'distributionoffice.yesc', 'dmeastern.yesc', 'dmnorthern.yesc', 'dmsouthern.yesc', 'dmwestern.yesc', 'financeoffice.yesc', 'eastoffice.yesc', 'ictoffice.yesc', 'materialoffice.yesc', 'northoffice.yesc', 'planningoffice.yesc', 'southoffice.yesc', 'teahl.yesc', 'teap.yesc', 'teay.yesc', 'tebhn.yesc', 'tebtg.yesc', 'tedg.yesc', 'tedl.yesc', 'tedp.yesc', 'tedpm.yesc', 'teedg.yesc', 'tehk.yesc', 'tehla.yesc', 'tehlg.yesc', 'tehtp.yesc', 'tehty.yesc', 'teid.yesc', 'teis.yesc', 'tekck.yesc', 'tekh.yesc', 'tekm.yesc', 'tekmd.yesc', 'tekmy.yesc', 'tekt.yesc', 'tektd.yesc', 'teky.yesc', 'telkk.yesc', 'telmt.yesc', 'telth.yesc', 'temb.yesc', 'temgd.yesc', 'temtk.yesc', 'temtn.yesc', 'temyg.yesc', 'tendg.yesc', 'tenok.yesc', 'teok.yesc', 'tepbt.yesc', 'tepdg.yesc', 'tepg.yesc', 'tepzd.yesc', 'tescg.yesc', 'tesdg.yesc', 'tesk.yesc', 'teskk.yesc', 'teslp.yesc', 'tesok.yesc', 'tespk.yesc', 'tespt.yesc', 'tetg.yesc', 'tetgk.yesc', 'tetk.yesc', 'tetkk.yesc', 'tetl.yesc', 'tetmw.yesc', 'tett.yesc', 'tettm.yesc', 'teykn.yesc', 'westoffice.yesc', 'chairman.mesc', 'deputychairman.mesc', 'ceo.mesc', 'ceooffice.mesc', 'bod1.mesc', 'bodoffice.mesc', 'gmd.mesc', 'gma.mesc', 'gmf.mesc', 'gmp.mesc', 'gmpm.mesc', 'dgma.mesc', 'dgmd.mesc', 'dgmf.mesc', 'dgmp1.mesc', 'dgmp2.mesc', 'dgmpm.mesc', 'agma.mesc', 'agmd1.mesc', 'agmd2.mesc', 'agmf.mesc', 'agmp1.mesc', 'agmp2.mesc', 'agmpm.mesc', 'agmaksdis.mesc', 'agmamdydis.mesc', 'agmamgdis.mesc', 'agmamtldis.mesc', 'agmanudis.mesc', 'agmapoldis.mesc', 'agmaymtdis.mesc', 'amamrpr.mesc', 'amamtz.mesc', 'ambg.mesc', 'amcatz.mesc', 'amcmtz.mesc', 'amkm.mesc', 'amkpd.mesc', 'amks.mesc', 'ammdy.mesc', 'ammg.mesc', 'ammgk.mesc', 'ammh.mesc', 'ammham.mesc', 'ammt.mesc', 'ammtl.mesc', 'amntg.mesc', 'amnty.mesc', 'amnu.mesc', 'amnz.mesc', 'ampb.mesc', 'ampgtg.mesc', 'ampl.mesc', 'ampol.mesc', 'amptg.mesc', 'amsg.mesc', 'amsgu.mesc', 'amtbk.mesc', 'amtdu.mesc', 'amtg.mesc', 'amtt.mesc', 'amtz.mesc', 'amwd.mesc', 'amya.mesc', 'amymt.mesc', 'maamrpr.mesc', 'maamtz.mesc', 'macatz.mesc', 'macmtz.mesc', 'makpd.mesc', 'maks.mesc', 'mamdy.mesc', 'mamg.mesc', 'mamgk.mesc', 'mamh.mesc', 'mamham.mesc', 'mamt.mesc', 'mamtl.mesc', 'mantg.mesc', 'manu.mesc', 'manz.mesc', 'mapb.mesc', 'mapgtg.mesc', 'mapol.mesc', 'maptg.mesc', 'masg.mesc', 'masgu.mesc', 'matbk.mesc', 'matdu.mesc', 'matt.mesc', 'matz.mesc', 'mawd.mesc', 'maymt.mesc', 'mksdis.mesc', 'mmdydis.mesc', 'mmgdis.mesc', 'mmtldis.mesc', 'mnudis.mesc', 'mymtdis.mesc', 'mpoldis.mesc'];


        // if (empty(array_diff($array2, $array1))) {
        //     echo "arr1 contains all values from arr2";
        //     } else {
        //     echo "arr1 does not contain all values from arr2";
        //     }
            
            echo "1 to 2";
            print_r(array_diff($array1, $array2));
            echo "<br>";
            echo "2 to 1";
            print_r(array_diff($array2, $array1));
    }


    public function print(Request $request, $id, $type=null){
        $token = $request->token;

        $user = PersonalAccessToken::findToken($request->token);

        if(!(isset($user))){
            return response()->json([
                'status'    => false,
                'error'     => 'The login user is invalid!',
            ], 200);
        }
        $type   =   $request->type;
        $invoice    = Invoice::find($id);
        $currency   = Currency::where('id',$invoice->Currency_type)->first();
        $invDetails = QuotationDetail::where('Invoice_Id',$id)->get();
        $invNotes       = QuotationNote::where('InvoiceId', $id)->where('Note','!=',"")->get();
        $authorizers    = Authorizer::get();
        $bankInfos = BankInfo::get();

        $bankInfoDetails = [];
        if($invoice->submit_status == 1 && $invoice->bank_info != ''){
            $banks = explode(',', $invoice->bank_info);
            foreach($banks as $bank){
                $banInfo = BankInfo::find($bank);
                $bInfo['name'] = $banInfo->name;
                $bInfo['details'] = BankInfoDetail::where('bank_info_id', $bank)->get();
                array_push($bankInfoDetails, $bInfo);
            }
        }

        // data for other payment
        if(is_numeric($type)){
            $advance_data = Advance::where('Invoice_Id', $invoice->id)->where('nth_time', $type)->first();
        }else{
            $advance_data = null;
        }
        $advance_last = Advance::where('Invoice_Id', $invoice->id)->orderBy('id', 'desc')->first();
        $advances = Advance::where('Invoice_Id', $invoice->id)->orderBy('id', 'asc')->get();

        $data = [
            'invoice'           => $invoice,
            'invNotes'          => $invNotes,
            'invDetails'        => $invDetails,
            'currency'          => $currency,
            'authorizers'       => $authorizers,
            'bankInfos'         => $bankInfos,
            'bankInfoDetails'   => $bankInfoDetails,
            'type'              => $type,
            'advance_last'      => $advance_last,
            'advances'          => $advances,
            'advance_data'      => $advance_data,
        ]; 

        

        // if($request->pdf == 'kinzi'){
            $data['layout'] = 'layouts.kinzi_print';
            return view('OfficeManagement.invoice.print')->with($data);
        // }else{
        //     $data['layout'] = 'layouts.mpdf';
        //     $pdf = PDF::loadView('OfficeManagement.invoice.print', $data);
        //     return $pdf->stream($invoice->Invoice_No.'.pdf');
        // }
    }
}