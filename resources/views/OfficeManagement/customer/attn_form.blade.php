<div class="form-group">
    <label for="">ATT Name</label>
    <select name="customer_id" class="form-control attn-customer select2" required>
        <option value="">Choose Customer</option>
        @foreach($customers as $row)
        <option value="{{ $row->id }}" @if($customer->id == $row->id) selected @endif>{{ $row->name}}</option>
        @endforeach
    </select>
    {!! Form::hidden('Attn', $customer->name, ['class' => 'attn-name']) !!}
</div>

<div class="form-group">
    <label for="company">Company Name</label>
    <select name="Company_name" class="form-control attn-company select2" required>
        <option value="">Company Name</option>
        @foreach($customers as $row)
        <option value="{{ $row->company }}" @if($customer->company == $row->company) selected @endif>{{ $row->company}}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="">Phone No</label>
    {!! Form::text('Contact_phone', $customer->phone, ['placeholder' => 'Phone Number', 'class' => 'form-control']) !!}
</div>

<div class="form-group">
    <label for="">Address</label>
    {!! Form::textarea('Address', $customer->address, ['placeholder' => 'Address', 'class' => 'form-control','cols'=>5,'rows'=>5]) !!}
</div>