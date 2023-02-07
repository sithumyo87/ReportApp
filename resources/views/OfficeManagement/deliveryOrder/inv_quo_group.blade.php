<div class="inv-quo-group">
    <div class="form-group">
        {{ Form::label('sub', 'Subject') }}
        <input class="form-control" name="sub" placeholder="subject" type="text" value="{{ $data != null ? $data->Sub : ''}}" {{ $data != null ? 'readonly' : ''}} />
    </div>
    <div class="form-group">
        <label for="">ATT Name</label>
        <input class="form-control" name="Attn" placeholder="ATT Name" type="text" value="{{ $data != null ? $data->Attn : ''}}" {{ $data != null ? 'readonly' : ''}}/>
    </div>
    <div class="form-group">
        <label for="company">Company Name</label>
        <input class="form-control" name="Company_name" placeholder="Company Name" type="text" value="{{ $data != null ? $data->Company_name : ''}}" {{ $data != null ? 'readonly' : ''}}/>
    </div>
    <div class="form-group">
        <label for="">Contact phone</label>
        <input class="form-control" name="Contact_phone" placeholder="Company Name" type="text" value="{{ $data != null ? $data->Contact_phone : ''}}" {{ $data != null ? 'readonly' : ''}}/>
    </div>
    <div class="form-group">
        <label for="">Address</label>
        {!! Form::textarea('Address', $data!= null? $data->Address : null, ['placeholder' => 'Address', 'class' => 'form-control','cols'=>5,'rows'=>5, $data != null ? 'readonly' : '']) !!}
    </div>
</div>