<div class="attn-form">
    <div class="form-group">
        <label for="">ATT Name</label>
        <input class="form-control" name="Attn" value="{{$quotation->Attn}}" type="text" readonly/>
        <input class="form-control" name="customer_id" value="{{$quotation->customer_id }}" type="hidden" />
    </div>

    <div class="form-group">
        <label for="company">Company Name</label>
        <input class="form-control" name="Company_name" value="{{$quotation->Company_name}}" type="text" readonly />
    </div>

    <div class="form-group">
        <label for="">Phone No</label>
        <input class="form-control" name="Contact_phone" value="{{$quotation->Contact_phone}}" type="text" readonly />
    </div>

    <div class="form-group">
        <label for="">Address</label>
        {!! Form::textarea('Address', $quotation->Address, ['placeholder' => 'Address', 'class' => 'form-control','cols'=>5,'rows'=>5, 'readonly' => 'readonly']) !!}
    </div>
</div>

<div class="form-group">
    {{ Form::label('sub', 'Subject') }}
    <input class="form-control" id="sub" name="Sub" placeholder="subject" type="text" value="{{ $quotation->Sub}}" readonly/>
</div>

<div class="form-group">
    {{ Form::label('currency', 'Currency') }}
    <div class="form-inline">
        @foreach($currency as $row)
            <div class="form-check mr-4">
                <input type="radio" id="{{$row->Currency_name}}" name="Currency_type" class="form-check-input currency readonly" value="{{$row->id}}" @if($quotation->Currency_type == $row->id) checked @endif> <label class="form-check-label currency readonly" for="{{$row->Currency_name}}">{{$row->Currency_name}}</label>
            </div>
        @endforeach
    </div>    
</div>