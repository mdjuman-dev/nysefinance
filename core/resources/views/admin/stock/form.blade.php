<div class="row p-4">
    <div class="col-md-8">
        <div class="form-group">
            <label for="">Name </label>
            <input type="text" class="form-control" required name="name" placeholder="Enter Name" value="{{isset($product)?$product->name:old('name')}}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Stock Code </label>
            <input type="text" class="form-control" required name="stock_code" placeholder="Enter Name" value="{{isset($product)?$product->stock_code:old('stock_code')}}">
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Stock For</label>
                <select name="use_for" id="" class="form-control">
                    <option value="">--Choose Type--</option>
                    <option {{isset($product) && $product->use_for=='stock'?'selected':''}} value="stock">Stock</option>
                    <option {{isset($product) && $product->use_for=='bond'?'selected':''}} value="bond">Bond</option>
                </select>
            </div>
        </div>

        <div class="col-md-4 section-bond-type {{isset($product) && $product->use_for=='bond'?'':'d-none'}}">
            <div class="form-group">
                <label for="">Bond Type</label>
                <select name="bond_type"  class="form-control">
                    <option {{isset($product) && $product->bond_type=='overview'?'selected':''}} value="bonds">Bonds</option>
                    <option {{isset($product) && $product->bond_type=='stock'?'selected':''}} value="economy">Economy</option>
                    <option {{isset($product) && $product->bond_type=='crypto'?'selected':''}} value="indices">Indices</option>
                    <option {{isset($product) && $product->bond_type=='futures'?'selected':''}} value="options">Options</option>
                </select>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="">Short Description</label>
        <textarea cols="3" rows="3" class="form-control" name="short_description" placeholder="Enter Short Description.....">{{isset($product)?$product->short_description:old('short_description')}}</textarea>
    </div>
    <div class="col-md-4 col-4">
        <div class="form-group">
            <label for="">Price <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="price" placeholder="Enter price" value="{{isset($product)?$product->price:old('price')}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
        </div>
    </div>
    <div class="col-md-4 col-4">
        <div class="form-group">
            <label for="">Fix Rate</label>
            <input type="text" class="form-control" name="fix_rate" placeholder="Enter Fix Rate" value="{{isset($product)?$product->fix_rate:old('fix_rate')}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
        </div>
    </div>
    <div class="col-md-4 col-4">
        <div class="form-group">
            <label for="">Market Rate</label>
            <input type="text" class="form-control" name="unfix_rate" placeholder="Enter Market Rate" value="{{isset($product)?$product->unfix_rate:old('unfix_rate')}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
        </div>
    </div>

    <div class="form-group">
        <label for="">Description <span class="text-danger">*</span></label>
        <textarea name="description" cols="5" rows="5" class="form-control desc--summernote">{{isset($product)?$product->description:old('description')}}</textarea>
    </div>
    <div class="form-group">
        <label for="">Logo <span class="text-danger">*</span></label>
        <input type="file" class="form-control" name="image">
    </div>
    <div class="form-group">
        <label for="">Certificate Logo <span class="text-danger">*</span></label>
        <input type="file" class="form-control" name="certificate_image">
    </div>

    <div class="form-group mt-3">
        <button class="btn btn-success" type="submit">Submit</button>
    </div>
</div>
