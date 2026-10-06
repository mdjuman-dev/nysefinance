<div class="row p-4">

    <div class="form-group col-6">
        <label for="">CUSIP-ID</label>
        <input type="text" class="form-control" value="{{isset($manual_stock)?$manual_stock->cusip_id:old('cusip_id')}}" name="cusip_id" placeholder="Enter CUSIP-ID">
    </div>

    <div class="form-group col-6">
        <label for="">Name</label>
        <input type="text" class="form-control" value="{{isset($manual_stock)?$manual_stock->name:old('name')}}" name="name" placeholder="Enter Name">
    </div>
    <div class="form-group col-6">
        <label for="">Amount</label>
        <input type="text" class="form-control" value="{{isset($manual_stock)?$manual_stock->amount:old('amount')}}" name="amount" placeholder="Enter Amount">
    </div>
    <div class="form-group col-6">
        <label for="">Holder</label>
        <input type="text" class="form-control" value="{{isset($manual_stock)?$manual_stock->holder:old('holder')}}" name="holder" placeholder="Enter Holder">
    </div>
    <div class="form-group col-md-4">
        <label for="">Buy Date</label>
        <input type="date" class="form-control" value="{{isset($manual_stock)?$manual_stock->buy_date:old('buy_date')}}" name="buy_date" placeholder="Enter Holder">
    </div>
    <div class="form-group col-md-4">
        <label for="">Sell Date</label>
        <input type="date" class="form-control" value="{{isset($manual_stock)?$manual_stock->sell_date:old('sell_date')}}" name="sell_date" placeholder="Enter Holder">
    </div>
    <div class="form-group col-md-4">
        <label for="">Transfer Date</label>
        <input type="date" class="form-control" value="{{isset($manual_stock)?$manual_stock->transfer_date:old('transfer_date')}}" name="transfer_date" placeholder="Enter Holder">
    </div>
    <div class="form-group col-md-6">
        <label for="">Status</label>
        <input type="text" class="form-control" value="{{isset($manual_stock)?$manual_stock->status:old('status')}}" name="status" placeholder="Enter Status">
    </div>
    <div class="form-group col-md-6">
        <label for="">Buyer email</label>
        <input type="text" class="form-control" value="{{isset($manual_stock)?$manual_stock->buyer_email:old('buyer_email')}}" name="buyer_email" placeholder="Enter Email Address">
    </div>
    <div class="form-group col-md-6">
        <label for="">E Contact</label>
        <input type="text" class="form-control" value="{{isset($manual_stock)?$manual_stock->buyer_number:old('buyer_number')}}" name="buyer_number" placeholder="Enter Contact Number">
    </div>
    <div class="form-group col-md-6">
        <label for="">Broker</label>
        <input type="text" class="form-control" value="{{isset($manual_stock)?$manual_stock->broker:old('broker')}}" name="broker" placeholder="Enter Broker Name">
    </div>

    <div class="form-group col-md-12">
        <label for="">Description</label>
        <textarea name="description" class="form-control summernote" cols="10" rows="8">{{isset($manual_stock)?$manual_stock->description:old('description')}}</textarea>
    </div>

    <div class="form-group mt-3">
        <button class="btn btn-success" type="submit">Submit</button>
    </div>
</div>
