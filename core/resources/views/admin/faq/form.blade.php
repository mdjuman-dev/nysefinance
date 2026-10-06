<div class="row p-4">
    <div class="col-md-8">
        <div class="form-group">
            <label for="">Name </label>
            <input type="text" class="form-control" required name="name" placeholder="Enter Name" value="{{isset($trade)?$trade->name:old('name')}}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Trade Type</label>
            <select name="trade_type"  class="form-control">
                <option {{isset($trade) && $trade->trade_type=='classic'?'selected':''}} value="classic">Classic</option>
                <option {{isset($trade) && $trade->trade_type=='puzzle_hunt'?'selected':''}} value="puzzle_hunt">Puzzle Hunt</option>
                <option {{isset($trade) && $trade->trade_type=='by_votes'?'selected':''}} value="by_votes">By Votes</option>
                <option {{isset($trade) && $trade->trade_type=='token_splash'?'selected':''}} value="token_splash">Token Splash</option>
                <option {{isset($trade) && $trade->trade_type=='gold_fx'?'selected':''}} value="gold_fx">Gold FX</option>
                <option {{isset($trade) && $trade->trade_type=='spot_x'?'selected':''}} value="spot_x">Spot-X</option>
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="">Type</label>
            <select name="type" class="form-control">
                <option {{isset($trade) && $trade->type=='daily'?'selected':''}} value="daily">Daily</option>
                <option {{isset($trade) && $trade->type=='weekly'?'selected':''}} value="weekly">Weekly</option>
                <option {{isset($trade) && $trade->type=='monthly'?'selected':''}} value="monthly">Monthly</option>
                <option {{isset($trade) && $trade->type=='yearly'?'selected':''}} value="yearly">Yearly</option>
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="">Status</label>
            <select name="status" class="form-control">
                <option {{isset($trade) && $trade->status=='active'?'selected':''}} value="active">Active</option>
                <option {{isset($trade) && $trade->status=='inactive'?'selected':''}} value="inactive">Inactive</option>
            </select>
        </div>
    </div>


    <div class="col-md-6">
        <div class="form-group">
            <label for="">Start Date</label>
            <input type="date" class="form-control" name="start_date" placeholder="Choose Start Date" value="{{isset($trade)?$trade->start_date:old('start_date')}}">
        </div>
    </div>


    <div class="col-md-6">
        <div class="form-group">
            <label for="">End Date</label>
            <input type="date" class="form-control" name="end_date" placeholder="Choose End Date" value="{{isset($trade)?$trade->end_date:old('end_date')}}">
        </div>
    </div>


    <div class="col-md-6">
        <div class="form-group">
            <label for="">Countdown(Days)</label>
            <input type="number" class="form-control" name="count_day_one" value="{{isset($trade)?$trade->count_day_one:old('count_day_one')}}" placeholder="Enter Countdown Days">
        </div>
        <div class="form-group">
            <label for="">Countdown Profit</label>
            <input type="number" class="form-control" name="count_profit_one" value="{{isset($trade)?$trade->count_profit_one:old('count_profit_one')}}" placeholder="Enter Countdown Profit">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="">Countdown(Days)</label>
            <input type="number" class="form-control" name="count_day_two" value="{{isset($trade)?$trade->count_day_two:old('count_day_two')}}" placeholder="Enter Countdown Days">
        </div>
        <div class="form-group">
            <label for="">Countdown Profit</label>
            <input type="number" class="form-control" name="count_profit_two" value="{{isset($trade)?$trade->count_profit_two:old('count_profit_two')}}" placeholder="Enter Countdown Profit">
        </div>
    </div>


    <div class="col-md-4">
        <div class="form-group">
            <label for="">Total Prize</label>
            <input type="text" name="total_prize" value="{{isset($trade)?$trade->total_prize:(old('total_prize')?old('total_prize'):0)}}" class="form-control" placeholder="Enter total prize">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Drawdown Day</label>
            <input type="text" name="dw_day" value="{{isset($trade)?$trade->dw_day:old('dw_day')}}" class="form-control" placeholder="Enter drawdown day">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Drawdown Profit</label>
            <input type="text" name="dw_profit" value="{{isset($trade)?$trade->dw_profit:old('dw_profit')}}" class="form-control" placeholder="Enter Drawdown Profit">
        </div>
    </div>


    <div class="col-md-4 col-4">
        <div class="form-group">
            <label for="">Price <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="amount" placeholder="Enter price" value="{{isset($trade)?$trade->amount:old('amount')}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
        </div>
    </div>

    <div class="col-md-4 col-4">
        <div class="form-group">
            <label for="">Interest <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="interest" placeholder="Enter interest" value="{{isset($trade)?$trade->interest:old('interest')}}">
        </div>
    </div>
    <div class="col-md-4 col-4">
        <div class="form-group">
            <label for="">Logo <span class="text-danger">*</span></label>
            <input type="file" class="form-control" name="image">
        </div>
    </div>


    <div class="form-group">
        <label for="">Short Description</label>
        <textarea cols="3" rows="3" class="form-control" name="short_details" placeholder="Enter Short Description.....">{{isset($trade)?$trade->short_details:old('short_details')}}</textarea>
    </div>

    <div class="form-group">
        <label for="">Description <span class="text-danger">*</span></label>
        <textarea name="details" cols="5" rows="5" class="form-control desc--summernote">{{isset($trade)?$trade->details:old('details')}}</textarea>
    </div>


    <div class="form-group mt-3">
        <button class="btn btn-success" type="submit">Submit</button>
    </div>
</div>
