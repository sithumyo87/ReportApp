<?php

namespace App\Http\Controllers\OfficeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Receipt;
use App\Models\Customer;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\PaymentTerm;

class ReceiptController extends Controller
{
    public function index(Request $request)
    {
        $data = Receipt::where('id','>',0)->orderBy('id','DESC')->paginate(5);
        return view('OfficeManagement.receipt.index',compact('data'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::where('action',true)->get(); 
        $currency = Currency::all();
        $invoices = Invoice::where('submit_status', true)->get();
        $payments = payments();
        return view('OfficeManagement.receipt.create',compact('customers','currency','invoices','payments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        $input =$request->all();
        
        $input['Receipt_No'] = 'RN-'.strtotime($input['Date'].' '.date('H:i:s'));
        $input['Date'] =date('Y-m-d',strtotime(str_replace('/', '-', $input['Date'])));
        
        $receipt = Receipt::create($input);

        return redirect()->route('OfficeManagement.receipt.index')
                        ->with('success','Receipt created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Receipt = Receipt::findOrFail($id);
        $customers = Customer::where('action',true)->get(); 
        $currency = Currency::all();
        $quotations = Receipt::all();
        return view('OfficeManagement.receipt.edit',compact('Receipt','customers','currency','quotations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            // 'name' => 'required',
            // 'email' => 'required',
            // 'phone' => 'required',
            // 'phone' => 'required',
            // 'company' => 'required',
            // 'address' => 'required',
            // 'phone' => 'required',
        ]);
        
        $refer_no = $request->refer_no;
        $Refer = true;
		if ($refer_no == '' || $refer_no == 'Refer No:') {
			$refer_no = '';
			$Refer = false;
		};

        $Date = date('Y-m-d',strtotime(str_replace('/', '-', $request->date)));

        $customerName = Customer::find($request->customer_id);

        $Receipt = Receipt::find($id);
        $Receipt->update([
            'customer_id'=>$request->customer_id,
            'Attn'=>$customerName->name,
	        'Company_name' => $request->Company_name,
	        'Contact_phone' => $request->Contact_phone,
	        'Address' => $request->Address,
	        'Sub'=>$request->Sub,
	        'Date'=>$Date,
	        'Refer_No'=>$refer_no,
	        'Refer_status' => false,
	        'Currency_type' => $request->Currency_type,
        ]);
    
        return redirect()->route('OfficeManagement.receipt.index')
                        ->with('success','Receipt updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    //JS function
	public function get_data_from_quo_name() {
		$id = $this->uri->segment(3);
		$data = $this->db->get_where('customers_tbl', array('action' => true, 'id' => $id))->row_array();
		echo '<input type="hidden" name="name" value="'.$data['name'].'">
		<div class="form-group">
				<label class="col-sm-3 control-label text-right text-uppercase">company name</label>
				<div class="col-sm-9" id="quo-company-data">';
					echo form_input('company', $data['company'], 'class="form-control" readonly=""');
				echo '</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label text-right text-uppercase">phone no:</label>
				<div class="col-sm-9">';
				if ($data['phone_other'] == "") {
					echo form_input('phone', $data['phone'], 'class="form-control" readonly=""');
				} else {
					echo '<select name="phone" class="form-control">
						<option value="'.$data['phone'].'">'.$data['phone'].'</option>
						<option value="'.$data['phone_other'].'">'.$data['phone_other'].'</option>
					</select>';
				}
				echo '</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label text-right text-uppercase">address</label>
				<div class="col-sm-9">';
					echo form_textarea('address', $data['address'], 'class="form-control" readonly=""');
				echo '</div>
			</div>';
	}


    public function invAttnOnChange(Request $request){
        $invId = $request->invId;
        if($invId != ''){
            $invoice = Invoice::findOrFail($invId);
        }else{
            $invoice = new Invoice();
        }
        $currency = Currency::all();
        $payments = payments();
        return view('OfficeManagement.receipt.attn_form',compact('invoice','currency', 'payments'));
    }
}
