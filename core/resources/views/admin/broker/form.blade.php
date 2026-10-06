<div class="row p-4">

    <div class="form-group col-12">
        <label for="">Name</label>
        <input type="text" class="form-control" value="{{isset($broker)?$broker->name:old('name')}}" name="name" placeholder="Enter Name">
    </div>
    <div class="form-group col-6">
        <label for="">Status</label>
        <select name="status" class="form-control">
            <option {{isset($broker) && $broker->status=='active'?'active':''}} value="active">Active</option>
            <option {{isset($broker) && $broker->status=='inactive'?'active':''}} value="inactive">Inactive</option>
        </select>
    </div>
    <div class="form-group col-6">
        <label for="">Type</label>
        <select name="type" class="form-control">
            <option {{isset($broker) && $broker->type=='mutual'?'active':''}} value="mutual">Mutual</option>
            <option {{isset($broker) && $broker->type=='mutual'?'active':''}} value="live">Live Market</option>
        </select>
    </div>


    <div class="form-group col-md-12">
        <label for="">Description</label>
        <textarea name="description" class="form-control summernote" cols="10" rows="8">{{isset($broker)?$broker->description:old('description')}}</textarea>
    </div>

    <div class="form-group mt-3">
        <button class="btn btn-success" type="submit">Submit</button>
    </div>
</div>
