<div class="form-group">
    <label for="">ATT Name</label>
    <select name="customer_id" class="form-control inv-customer select2" required>
        <option value="">Choose Customer</option>
        @foreach($personInvoices as $row)
        <option value="{{ $row->id }}" @if($personInvoice->id == $row->id) selected @endif>{{ $row->name}}</option>
        @endforeach
    </select>
    {!! Form::hidden('Attn', $personInvoice->name, ['class' => 'inv-name']) !!}
</div>

<div class="form-group">
    <label for="company">Company Name</label>
    <select name="Company_name" class="form-control inv-company select2" required>
        <option value="">Company Name</option>
        @foreach($personInvoices as $row)
        <option value="{{ $row->company }}" @if($personInvoice->company == $row->company) selected @endif>{{ $row->company}}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="">Phone No</label>
    {!! Form::text('Contact_phone', $personInvoice->phone, ['placeholder' => 'Phone Number', 'class' => 'form-control']) !!}
</div>

<div class="form-group">
    <label for="">Address</label>
    {!! Form::textarea('Address', $personInvoice->address, ['placeholder' => 'Address', 'class' => 'form-control','cols'=>5,'rows'=>5]) !!}
</div>