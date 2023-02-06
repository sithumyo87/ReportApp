<div class="attn-form">
    <div class="form-group">
        <label for="">ATT Name</label>
        <input class="form-control" name="Attn" value="{{$invoice->Attn}}" type="text" readonly/>
        <input class="form-control" name="customer_id" value="{{$invoice->customer_id }}" type="hidden" />
    </div>

    <div class="form-group">
        <label for="company">Company Name</label>
        <input class="form-control" name="Company_name" value="{{$invoice->Company_name}}" type="text" readonly />
    </div>

    <div class="form-group">
        <label for="">Phone No</label>
        <input class="form-control" name="Contact_phone" value="{{$invoice->Contact_phone}}" type="text" readonly />
    </div>

    <div class="form-group">
        <label for="">Address</label>
        {!! Form::textarea('Address', $invoice->Address, ['placeholder' => 'Address', 'class' => 'form-control','cols'=>5,'rows'=>5, 'readonly' => 'readonly']) !!}
    </div>
</div>

<div class="form-group">
    {{ Form::label('sub', 'Subject') }}
    <input class="form-control" id="sub" name="Sub" placeholder="subject" type="text" value="{{ $invoice->Sub}}" readonly/>
</div>

<div class="form-group">
    {{ Form::label('currency', 'Currency') }}
    <div class="form-inline">
        @foreach($currency as $row)
            <div class="form-check mr-4">
                <input type="radio" id="{{$row->Currency_name}}" name="Currency_type" class="form-check-input currency readonly" value="{{$row->id}}" @if($invoice->Currency_type == $row->id) checked @endif> <label class="form-check-label currency readonly" for="{{$row->Currency_name}}">{{$row->Currency_name}}</label>
            </div>
        @endforeach
    </div>    
</div>
<div class="form-group">
    <label for="">Payment Term</label>
    <select name="Advance" class="form-control form-select readonly payment" required >
        <option value="">Choose Payment</option>
        @foreach($payments as $key => $value)
            <option value="{{ $key }}" @if($key == $invoice->Advance) selected @endif>{{ ($value)}}</option>
        @endforeach
    </select>
</div>