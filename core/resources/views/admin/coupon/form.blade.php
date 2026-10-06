<div class="row p-4">
    <div class="col-md-8">
        <div class="form-group">
            <label for="">Name </label>
            <input type="text" class="form-control" required name="name" placeholder="Enter Name" value="{{isset($coupon)?$coupon->name:old('name')}}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Price</label>
            <input type="text" class="form-control" required name="price" placeholder="Enter price" value="{{isset($coupon)?$coupon->price:old('price')}}">
        </div>
    </div>

    <div class="card">
        <div class="card-body" style="background: #df121217;border-radius: 5px;margin-bottom: 15px;">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <h5>Coupon Volume</h5>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Total Buy (Users) </label>
                        <input type="text" name="total_buy" class="form-control" value="{{isset($coupon)?$coupon->total_buy:old('total_buy')}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Total Buy (Amount) </label>
                        <input type="text" name="total_buy_amount" class="form-control" value="{{isset($coupon)?$coupon->total_buy_amount:old('total_buy_amount')}}">
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-6">
        <div class="form-group">
            <label for="">Wining Date</label>
            <input type="date" class="form-control" required name="wining_date" placeholder="Enter wining_date" value="{{isset($coupon)?$coupon->wining_date:old('wining_date')}}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="">Expire Date</label>
            <input type="date" class="form-control" required name="expire_date" placeholder="Enter expire_date" value="{{isset($coupon)?$coupon->expire_date:old('expire_date')}}">
        </div>
    </div>


    <div class="form-group">
        <label for="">Icon <span class="text-danger">*</span></label>
        <input type="file" class="form-control" name="icon">
    </div>


    <div class="form-group">
        <label for="">Description <span class="text-danger">*</span></label>
        <textarea name="description" cols="7" rows="7" class="form-control desc--summernote">{{isset($coupon)?$coupon->description:old('description')}}</textarea>
    </div>



    <div class="form-group mt-3">
        <button class="btn btn-success" type="submit">Submit</button>
    </div>
</div>
