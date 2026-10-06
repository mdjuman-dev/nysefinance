<div class="row p-4">

    <div class="form-group col-12">
        <label for="">Choose Product</label>
        <select name="product_id" class="form-control">
            <option value="">
                --------------
            </option>
            @foreach($products as $product)
                <option {{isset($stock_video) && $stock_video->product_id==$product->id?'selected':''}} value="{{$product->id}}">{{$product->name}}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-12">
        <label for="">Video</label>
        <input type="file" class="form-control" name="video">
    </div>




    <div class="form-group mt-3">
        <button class="btn btn-success" type="submit">Submit</button>
    </div>
</div>
